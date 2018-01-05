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
use Doctrine\ORM\EntityManager;
use Doctrine\Common\EventManager;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\Proxy\ProxyFactory;
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

        $routes = include __DIR__.'/../routes.php';
        $applicationMode = 'development';

        $this->sc = new DependencyInjection\ContainerBuilder();
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


        //------- some doctrine experiments
        if ($applicationMode == "development") {
            $cache = new \Doctrine\Common\Cache\ArrayCache;
        } else {
            $cache = new \Doctrine\Common\Cache\ApcCache;
        }

        $config = new Configuration;
        $config->setMetadataCacheImpl($cache);
        $driverImpl = $config->newDefaultAnnotationDriver(CONFIG_DATABASE_ENTITY_PATHS, false);
        $config->setMetadataDriverImpl($driverImpl);
        $config->setQueryCacheImpl($cache);
        $config->setProxyDir(__DIR__.'/../Proxies');
        $config->setProxyNamespace('Objex\Proxies');
        $config->setAutoGenerateProxyClasses($applicationMode === 'development');

        if ('development' === $applicationMode) {
            $config->setAutoGenerateProxyClasses(ProxyFactory::AUTOGENERATE_EVAL);
        }

        $this->sc->set('orm', EntityManager::create( CONFIG_DATABASE_CONNECTION, $config, new EventManager()));
        //-------------------

        $this->sc->register('app', App::class)
            ->setArguments(array(
                new Reference('dispatcher'),
                new Reference('controller_resolver'),
                new Reference('request_stack'),
                new Reference('argument_resolver'),
            ))
        ;

        //that is the main event to hook into the framework at the actual dev: write subscriber to Modules!
        foreach (MODULES as $module) {
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

