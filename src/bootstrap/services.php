<?php
/**
 * Created by PhpStorm.
 * User: dk
 * Date: 04.01.18
 * Time: 14:45
 */

use Symfony\Component\DependencyInjection;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpFoundation;
use Symfony\Component\HttpKernel;
use Symfony\Component\Routing;
use Symfony\Component\EventDispatcher;
use Symfony\Component\Dotenv\Dotenv;
use Objex\App;


final class Objex {
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

    public function setSc()
    {
        if ($this->sc instanceof DependencyInjection\ContainerBuilder) {
            return;
        }

        $dotenv = new Dotenv();
        $dotenv->load(__DIR__ .'/../../.env');

        $routes = include __DIR__.'/../routes.php';

        $this->sc = new DependencyInjection\ContainerBuilder();

        $this->sc->set('config', new \Objex\Core\Config\Config());

        $this->sc->register('context', Routing\RequestContext::class);
        $this->sc->register('matcher', Routing\Matcher\UrlMatcher::class)
            ->setArguments(array($routes, new Reference('context')))
        ;
        $this->sc->register('request_stack', HttpFoundation\RequestStack::class);
        $this->sc->register('controller_resolver', HttpKernel\Controller\ControllerResolver::class);
        $this->sc->register('argument_resolver', HttpKernel\Controller\ArgumentResolver::class);

        $this->sc->register('listener.router', HttpKernel\EventListener\RouterListener::class)
            ->setArguments(array(new Reference('matcher'), new Reference('request_stack')))
        ;
        $this->sc->register('listener.response', HttpKernel\EventListener\ResponseListener::class)
            ->setArguments(array('UTF-8'))
        ;
        $this->sc->register('listener.exception', HttpKernel\EventListener\ExceptionListener::class)
            ->setArguments(array('Objex\Controllers\ErrorController::exceptionAction'))
        ;
        $this->sc->register('dispatcher', EventDispatcher\EventDispatcher::class)
            ->addMethodCall('addSubscriber', array(new Reference('listener.router')))
            ->addMethodCall('addSubscriber', array(new Reference('listener.response')))
            ->addMethodCall('addSubscriber', array(new Reference('listener.exception')))
        ;

        $this->sc->register('app', App::class)
            ->setArguments(array(
                new Reference('dispatcher'),
                new Reference('controller_resolver'),
                new Reference('request_stack'),
                new Reference('argument_resolver'),
            ))
        ;

        $this->sc->set('crypto', new \Objex\Core\Cryptography\Cryptography());

        //that is the main event to hook into the framework at the actual dev: write subscriber to Modules!
        foreach ($this->sc->get('config')->getConfig('modules') as $module) {
            $this->sc->get('dispatcher')->addSubscriber(new $module);
        }

        $this->sc->get('dispatcher')->dispatch('booting', new \Objex\Core\Events\Booting($this->sc));
    }

    /**
     * @return DependencyInjection\ContainerBuilder
     */
    public function getSc()
    {
        if (! $this->sc instanceof DependencyInjection\ContainerBuilder) {
            $this->setSc();
        }
        return $this->sc;
    }

}

if (! function_exists('objex')) {
    /**
     * @return DependencyInjection\ContainerBuilder
     */
    function objex()
    {
        $objex = Objex::getInstance();

        return $objex->getSc();
    }
}

