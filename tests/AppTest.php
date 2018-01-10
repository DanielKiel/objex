<?php
/**
 * Created by PhpStorm.
 * User: dk
 * Date: 04.01.18
 * Time: 10:58
 */

namespace Simplex\Tests;

use Objex\Core\Config\Config;
use PHPUnit\Framework\TestCase;

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