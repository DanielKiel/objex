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
use Objex\Core\Modules\Extension;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class LoggerExtension extends Extension
{
    /**
     * @param Booting $event
     */
    public function boot(Booting $event)
    {
        $streamHandler = new StreamHandler($event->getServiceContainer()->get('config')->getConfig('logs')['path']);
        $streamHandler->setFormatter(new LineFormatter());
        $event->getServiceContainer()->set(
            'Logger',
            new Logger('objex', $streamHandler)
        );
    }

    /**
     * @param GetResponseForExceptionEvent $event
     * @throws \Exception
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        objex()->get('Logger')
            ->logException($event->getException());
    }

    public static function getSubscribedEvents()
    {
        return [
            'booting' => 'boot',
            KernelEvents::EXCEPTION => array('onKernelException', 0),
        ];
    }
}