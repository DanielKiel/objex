<?php
/**
 * Created by PhpStorm.
 * User: dk
 * Date: 08.01.18
 * Time: 14:43
 */

namespace Objex\Logger;


use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Objex\Core\Events\Booting;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class LoggerService implements EventSubscriberInterface
{
    /**
     * @param Booting $event
     */
    public function onBooting(Booting $event)
    {
        $streamHandler = new StreamHandler(__DIR__ . '/../../storage/logs/objex.log');
        $streamHandler->setFormatter(new LineFormatter());
        objex()->set(
            'logger',
            new Logger('objex', $streamHandler)
        );
    }

    /**
     * @param GetResponseForExceptionEvent $event
     * @throws \Exception
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        objex()->get('logger')
            ->logException($event->getException());
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            'booting' => 'onBooting',
            KernelEvents::EXCEPTION => array('onKernelException', 9999),
        ];
    }
}