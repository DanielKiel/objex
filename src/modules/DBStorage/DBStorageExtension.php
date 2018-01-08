<?php
/**
 * Created by PhpStorm.
 * User: dk
 * Date: 08.01.18
 * Time: 19:32
 */

namespace Objex\DBStorage;


use Objex\Core\Events\Booting;
use Objex\Core\Modules\Extension;
use Doctrine\ORM\EntityManager;
use Doctrine\Common\EventManager;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\Proxy\ProxyFactory;

class DBStorageExtension extends Extension
{
    public function boot(Booting $event)
    {
        $sc = $event->getServiceContainer();
        $debug = $sc->get('config')->getConfig('env')['debug'];

        if ($debug === true) {
            $cache = new \Doctrine\Common\Cache\ArrayCache;
        } else {
            $cache = new \Doctrine\Common\Cache\ApcCache;
        }

        $config = new Configuration;
        $config->setMetadataCacheImpl($cache);
        $driverImpl = $config->newDefaultAnnotationDriver($sc->get('config')->getConfig('database')['entity_paths'], false);
        $config->setMetadataDriverImpl($driverImpl);
        $config->setQueryCacheImpl($cache);
        $config->setProxyDir(__DIR__.'/../Proxies');
        $config->setProxyNamespace('Objex\Proxies');
        $config->setAutoGenerateProxyClasses($debug);

        if ($debug === true) {
            $config->setAutoGenerateProxyClasses(ProxyFactory::AUTOGENERATE_EVAL);
        }

        $sc->set('DBStorage', EntityManager::create(
            $sc->get('config')->getConfig('database')['connection'],
            $config,
            new EventManager()
        ));

        require_once __DIR__ .'/Factories/schemas.php';
        require_once __DIR__ .'/Factories/objects.php';
    }
}