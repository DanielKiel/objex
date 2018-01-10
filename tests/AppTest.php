<?php
/**
 * Created by PhpStorm.
 * User: dk
 * Date: 04.01.18
 * Time: 10:58
 */


use GuzzleHttp\Client;
use Objex\Core\Cache\Cache;
use Objex\Core\Cache\Exceptions\CacheException;
use Objex\Core\Config\Config;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Routing\Route;

class AppTest extends TestCase
{
    public function testBasic()
    {
        $this->assertTrue(true);
    }

    public function testConfig()
    {
        objex()->get('config')->setConfig([
            'logger' => [
                'path' => 'explicit/m-path'
            ]
        ]);

        $logs = objex()->get('config')->getConfig('logger');

        $this->assertEquals('explicit/m-path', $logs['path']);
    }
}