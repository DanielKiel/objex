<?php
/**
 * Created by PhpStorm.
 * User: dk
 * Date: 10.01.18
 * Time: 16:58
 */

namespace Simplex\Tests;


use Objex\Core\Cache\Cache;
use Objex\Core\Cache\Exceptions\CacheException;

class CacheTest extends \PHPUnit\Framework\TestCase
{
    public function testBasicCache()
    {
        /** @var Cache $cache */
        $cache = objex()->get('cache');

        $this->assertInstanceOf(Cache::class, $cache);

        $item = $cache->getItem('cached.item', (object) [
            'foo' => 'bar'
        ]);

        $this->assertEquals('bar', $item->foo);

        $cache->clear();

        $this->expectException(CacheException::class);
        $cache->getItem('cached.item');
    }

    public function testAdvancedCache()
    {
        /** @var Cache $cache */
        $cache = objex()->get('cache');

        $cache->getItem('cached.item1', (object) [
            'foo' => 'bar1'
        ]);

        $cache->getItem('cached.item2', (object) [
            'foo' => 'bar2'
        ]);

        $items = $cache->getItems(['cached.item1', 'cached.item2', 'cached.item3']);


        foreach ($items as $index => $item) {
            if ($index == 'cached.item1') {
                $this->assertEquals('bar1', $item->get()->foo);
            }

            if ($index == 'cached.item2') {
                $this->assertEquals('bar2', $item->get()->foo);
            }

            if ($index == 'cached.item3') {
                $this->assertEmpty($item->get());
            }
        }

        $cache->clear();
    }

    public function testCacheExpiresAt()
    {
        /** @var Cache $cache */
        $cache = objex()->get('cache');

        $this->assertInstanceOf(Cache::class, $cache);

        $item = $cache->setExpiresAfter(1)->getItem('cached.item', (object) [
            'foo' => 'bar'
        ]);

        $this->assertEquals('bar', $item->foo);

        sleep(1);

        $this->expectException(CacheException::class);
        $cache->getItem('cached.item');
    }

    public function testCallback()
    {
        /** @var Cache $cache */
        $cache = objex()->get('cache');

        setSchema('MyNamespace',[
            'definition' => [
                'foo' => [
                    'type' => 'text'
                ]
            ]
        ]);

        //saving an object!!!
        $object = $cache->getItem('cached.object', saveObject('MyNamespace', [
            'foo' => 'bar'
        ]),['tag1', 'tag2']);

        //normally the save object will produce a new object cause we have no id set - but here not at using existing cache key
        $again = $cache->getItem('cached.object', (object) ['foo' => 'not bar'],['tag1', 'tag2']);

        $this->assertEquals($object, $again);

        //now invalidate tags!
        $cache->deleteItem('cached.object');

        $this->expectException(CacheException::class);
        $cache->getItem('cached.object');

        deleteSchema('MyNamespace');
    }
}
