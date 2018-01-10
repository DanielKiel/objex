<?php
/**
 * Created by PhpStorm.
 * User: dk
 * Date: 10.01.18
 * Time: 13:27
 */

namespace Objex\Core\Middleware;


use Objex\Core\Modules\Extension;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class JSONRequest extends Extension
{
    /**
     * decode JSON params
     * @param GetResponseEvent $event
     */
    public function onRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        if ($request->headers->get('Content-Type') !== 'application/json') {
            return;
        }

        $params = $data = json_decode($request->getContent(), true);

        $request->request->replace($params);
    }

    public static function getSubscribedEvents()
    {
        return [
            'booting' => 'boot',
            KernelEvents::REQUEST => array('onRequest', 0),
        ];
    }
}