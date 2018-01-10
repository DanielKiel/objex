<?php
/**
 * Created by PhpStorm.
 * User: dk
 * Date: 05.01.18
 * Time: 19:46
 */

namespace Objex\Validation;


use Objex\Core\Events\Booting;
use Objex\Core\Modules\Extension;
use Objex\Validation\Controllers\ErrorController;
use Objex\Validation\Exceptions\ValidationException;
use Objex\Validation\Validators\DBStorageValidator;
use Objex\Validation\Validators\Types\ConstraintsValidator;
use Objex\Validation\Validators\Types\ExpressionLanguageValidator;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Validator\Validation;

class ValidatorExtension extends Extension
{
    /**
     * when not validated, we will have our own response here
     * also we stop propagation, it is not necessary to make more stuff when request is not valid
     * @param GetResponseForExceptionEvent $event
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();

        if (! $exception instanceof ValidationException) {
            return;
        }

        $response = (new ErrorController())->errorAction($exception);

        $event->setResponse($response);
        $event->stopPropagation();
    }

    /**
     * we register at booting to register our global doctrine subscriber here
     * @param Booting $event
     * @throws \Exception
     */
    public function boot(Booting $event)
    {
        $this->hasServices([
            'ExpressionLanguage',
            'DBStorage'
        ]);

        $event->getServiceContainer()
            ->register('Validation.ExpressionLanguage', ExpressionLanguageValidator::class)
            ->setArgument('language', new Reference('ExpressionLanguage'));

        $event->getServiceContainer()
            ->register('Validation.Constraints', ConstraintsValidator::class)
            ->setArgument('validator', Validation::createValidator());

        $event->getServiceContainer()->get('DBStorage')
            ->getEventManager()
            ->addEventSubscriber(new DBStorageSubscriber());

    }

    public static function getSubscribedEvents()
    {
        return [
            'booting' => 'boot',
             KernelEvents::EXCEPTION => array('onKernelException', 0),
        ];
    }
}