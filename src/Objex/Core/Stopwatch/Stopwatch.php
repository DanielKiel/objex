<?php
/**
 * Created by PhpStorm.
 * User: dk
 * Date: 08.01.18
 * Time: 22:22
 */
namespace Objex\Core\Stopwatch;


use Symfony\Component\Stopwatch\Stopwatch as Watch;

class Stopwatch
{
    private $watch;

    protected static $instance = null;

    protected $sc;

    protected function __construct()
    {
        //Thou shalt not construct that which is unconstructable!
    }

    protected function __clone()
    {
        //Me not like clones! Me smash clones!
    }

    public static function getInstance()
    {
        if (!isset(static::$instance)) {
            static::$instance = new static;
        }

        return static::$instance;
    }

    /**
     * @return Watch
     */
    public function getWatch()
    {
        if (! $this->watch instanceof Watch) {
            $this->watch = new Watch();
        }

        return $this->watch;
    }
}