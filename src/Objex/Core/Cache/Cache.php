<?php
/**
 * Created by PhpStorm.
 * User: dk
 * Date: 10.01.18
 * Time: 14:43
 */

namespace Objex\Core\Cache;


use Objex\Core\Cache\Exceptions\CacheException;
use Psr\Cache\CacheItemInterface;
use Symfony\Component\Cache\Adapter\AdapterInterface;

class Cache
{
    private $adapter;

    private $expiresAfter = 0;

    /**
     * Cache constructor.
     * @param AdapterInterface $adapter
     */
    public function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * @param int $expiresAfter
     * @return $this
     */
    public function setExpiresAfter(int $expiresAfter)
    {
        $this->expiresAfter = $expiresAfter;

        return $this;
    }

    /**
     * @param $key
     * @param null $defaultValue
     * @param null $tags
     * @return mixed
     * @throws CacheException
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function getItem($key, $defaultValue = null, $tags = null)
    {
        if (! $this->adapter->hasItem($key)) {
            if (is_null($defaultValue)) {
                throw new CacheException('cache item not exists: ' . $key);
            }
        }

        $cached = $this->adapter->getItem($key);

        if (! is_null($tags)) {
            $cached->tag($tags);
        }

        if (! $cached->isHit()) {
            $cached->set($defaultValue);

            if ($this->expiresAfter !== 0) {
                $cached->expiresAfter($this->expiresAfter);
            }

            $this->save($cached);
        }

        return $cached->get();
    }

    /**
     * @param array $keys
     * @return array|\Traversable
     */
    public function getItems(array $keys)
    {
        return $this->adapter->getItems($keys);
    }

    /**
     * @param CacheItemInterface $cacheItem
     */
    public function save(CacheItemInterface $cacheItem)
    {
        $this->adapter->save($cacheItem);
    }

    /**
     * @param $key
     * @throws CacheException
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function deleteItem($key) 
    {
        if (! $this->adapter->hasItem($key)) {
            throw new CacheException('cache item not exists: ' . $key);
        }

        $this->adapter->deleteItem($key);
    }

    /**
     *
     */
    public function clear()
    {
        $this->adapter->clear();
    }
}