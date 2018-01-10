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
use Symfony\Component\DependencyInjection\ContainerBuilder;

class DBStorageExtension extends Extension
{
    /**
     * @param Booting $event
     * @throws \Doctrine\ORM\ORMException
     * @throws \Exception
     */
    public function boot(Booting $event)
    {
        $sc = $event->getServiceContainer();
        $debug = $sc->get('config')->getConfig('env')['debug'];

        $cache = $this->getCache($debug);

        $sc->set('DBStorage', EntityManager::create(
            $sc->get('config')->getConfig('database')['connection'],
            $this->getConfig($sc, $cache, $debug),
            new EventManager()
        ));

        require_once __DIR__ .'/Factories/schemas.php';
        require_once __DIR__ .'/Factories/objects.php';

        $this->registerRoutes();
    }

    /**
     *
     */
    protected function registerRoutes()
    {
        \Objex::getInstance()->addRoute('api.get', (new \Symfony\Component\Routing\Route('/api/{alias}', array(
            'name' => null,
            '_controller' => 'Objex\DBStorage\Controllers\APIController::getAction',
        )))->setMethods('GET'));

        \Objex::getInstance()->addRoute('api.post', (new \Symfony\Component\Routing\Route('/api/{alias}', array(
            'name' => null,
            '_controller' => 'Objex\DBStorage\Controllers\APIController::postAction',
        )))->setMethods('POST'));

        \Objex::getInstance()->addRoute('api.put', (new \Symfony\Component\Routing\Route('/api/{alias}', array(
            'name' => null,
            '_controller' => 'Objex\DBStorage\Controllers\APIController::putAction',
        )))->setMethods('PUT'));

        \Objex::getInstance()->addRoute('api.delete', (new \Symfony\Component\Routing\Route('/api/{alias}', array(
            'name' => null,
            '_controller' => 'Objex\DBStorage\Controllers\APIController::deleteAction',
        )))->setMethods('DELETE'));
    }

    /**
     * @param bool $debug
     * @return \Doctrine\Common\Cache\ApcCache|\Doctrine\Common\Cache\ArrayCache
     */
    protected function getCache(bool $debug)
    {
        if ($debug === true) {
            return new \Doctrine\Common\Cache\ArrayCache;
        } else {
            return new \Doctrine\Common\Cache\ApcCache;
        }
    }

    /**
     * @param ContainerBuilder $sc
     * @param \Doctrine\Common\Cache\ApcCache|\Doctrine\Common\Cache\ArrayCache $cache
     * @param bool $debug
     * @throws \Exception
     * @return Configuration
     */
    protected function getConfig($sc, $cache, bool $debug)
    {
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

        return $config;
    }
}