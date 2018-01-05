<?php
/**
 * Created by PhpStorm.
 * User: dk
 * Date: 05.01.18
 * Time: 19:46
 */

namespace Objex\Validation;


use Objex\Core\Events\Booting;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ValidatorService implements EventSubscriberInterface
{
    public function onBooting(Booting $event)
    {
        $event->getServiceContainer()->get('orm')
            ->getEventManager()
            ->addEventSubscriber(new Validator());
    }

    public static function getSubscribedEvents()
    {
        return [
            'booting' => 'onBooting'
        ];
    }
}