<?php
/**
 * Created by PhpStorm.
 * User: dk
 * Date: 08.01.18
 * Time: 19:20
 */
namespace Objex\Core\Modules;


use Objex\Core\Events\Booting;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

abstract class Extension implements EventSubscriberInterface
{
    /**
     * override to register stuff on booting here
     * @param Booting $event
     */
    public function boot(Booting $event)
    {

    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            'booting' => 'boot'
        ];
    }

    /**
     * @return array
     */
    public function requires(): array
    {
        return [];
    }

    /**
     * @param array $services
     * @throws \Exception
     */
    public function hasServices(array $services = []): void
    {
        foreach ($services as $service) {
            if (! objex()->has($service)) {
                throw new \Exception('service not defined: ' . $service);
            }
        }
    }
}