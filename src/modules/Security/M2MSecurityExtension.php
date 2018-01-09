<?php
/**
 * Created by PhpStorm.
 * User: dk
 * Date: 09.01.18
 * Time: 08:17
 */

namespace Objex\Security;


use Objex\Core\Events\Booting;
use Objex\Core\Modules\Extension;
use Objex\Security\Authentication\TokenAuthenticator;
use Objex\Security\Controllers\ErrorController;
use Objex\Security\Models\Machine;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestMatcher;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Objex\Security\Exceptions\AccessDeniedException;
use Objex\Security\Providers\MachineProvider;
class M2MSecurityExtension extends Extension
{
    public $authorizationChecker;

    public function boot(Booting $event)
    {
        $this->hasServices([
            'DBStorage'
        ]);

        $this->registerDBModels($event);
    }

    /**
     * @param Booting $event
     * @throws \Exception
     */
    public function registerDBModels(Booting $event)
    {
        //register models entity
        $database = $event->getServiceContainer()->get('config')->getConfig('database');

        if (! array_key_exists('entity_paths', $database)) {
            return;
        }

        $entityPaths = $database['entity_paths'];
        array_push($entityPaths, __DIR__ .'/Models');

        $database['entity_paths'] = $entityPaths;

        $event->getServiceContainer()->get('config')->setConfig([
            'database' => $database
        ]);
    }

    /**
     * @param GetResponseEvent $event
     * @throws AccessDeniedException
     */
    public function onRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        if ($this->isGuarded($request) === false) {
            return;
        }

        $authenticator = new TokenAuthenticator();

        if (! $authenticator->supports($request)) {
            throw new AccessDeniedException();
        }

        $credentials = $authenticator->getCredentials($request);

        $machine = $authenticator->getUser(
            $credentials,
            new MachineProvider()
        );

        if (! $machine instanceof Machine) {
            throw new AccessDeniedException();
        }

        if ($authenticator->checkCredentials($credentials, $machine) !== true) {
            throw new AccessDeniedException();
        }
    }

    /**
     * @param Request $request
     * @return bool
     */
    public function isGuarded(Request $request):bool
    {
        try {
            $map = objex()->get('config')->getConfig('firewall')['urlMap'];
        }
        catch(\Exception $e) {
            return true;
        }

        $guard = false;
        foreach ($map as $match) {
            $requestMatcher = new RequestMatcher($match);
            if ($requestMatcher->matches($request) === true) {
                $guard = true;
                break;
            };
        }

        return $guard;
    }

    /**
     * when not validated, we will have our own response here
     * also we stop propagation, it is not ncessary to make more stuff when request is not valid
     * @param GetResponseForExceptionEvent $event
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();

        if (! $exception instanceof AccessDeniedException) {
            return;
        }

        $response = (new ErrorController())->errorAction($exception);

        $event->setResponse($response);
        $event->stopPropagation();
    }

    public static function getSubscribedEvents()
    {
        return [
            'booting' => 'boot',
            KernelEvents::REQUEST => array('onRequest', 0),
            KernelEvents::EXCEPTION => array('onKernelException', 0),
        ];
    }
}