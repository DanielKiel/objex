<?php
/**
 * Created by PhpStorm.
 * User: dk
 * Date: 05.01.18
 * Time: 20:05
 */

namespace Objex\Core\Events;


use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\EventDispatcher\Event;

class Booting extends Event
{
    /** @var ContainerBuilder  */
    private $sc;

    /**
     * Booting constructor.
     * @param ContainerBuilder $sc
     */
    public function __construct(ContainerBuilder $sc)
    {
        $this->sc = $sc;
    }

    /**
     * @return ContainerBuilder
     */
    public function getServiceContainer()
    {
        return $this->sc;
    }
}