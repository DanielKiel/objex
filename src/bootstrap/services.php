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
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\Proxy\ProxyFactory;
use Objex\App;

$routes = include __DIR__.'/../routes.php';
$applicationMode = 'development';

$sc = new DependencyInjection\ContainerBuilder();
$sc->register('context', Routing\RequestContext::class);
$sc->register('matcher', Routing\Matcher\UrlMatcher::class)
    ->setArguments(array($routes, new Reference('context')))
;
$sc->register('request_stack', HttpFoundation\RequestStack::class);
$sc->register('controller_resolver', HttpKernel\Controller\ControllerResolver::class);
$sc->register('argument_resolver', HttpKernel\Controller\ArgumentResolver::class);

$sc->register('listener.router', HttpKernel\EventListener\RouterListener::class)
    ->setArguments(array(new Reference('matcher'), new Reference('request_stack')))
;
$sc->register('listener.response', HttpKernel\EventListener\ResponseListener::class)
    ->setArguments(array('UTF-8'))
;
$sc->register('listener.exception', HttpKernel\EventListener\ExceptionListener::class)
    ->setArguments(array('Objex\Controllers\ErrorController::exceptionAction'))
;
$sc->register('dispatcher', EventDispatcher\EventDispatcher::class)
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

$sc->set('orm', EntityManager::create( CONFIG_DATABASE_CONNECTION, $config));
//-------------------

$sc->register('app', App::class)
    ->setArguments(array(
        new Reference('dispatcher'),
        new Reference('controller_resolver'),
        new Reference('request_stack'),
        new Reference('argument_resolver'),
    ))
;

return $sc;