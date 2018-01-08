<?php
/**
 * Created by PhpStorm.
 * User: dk
 * Date: 04.01.18
 * Time: 10:58
 */

namespace Simplex\Tests;

use Objex\Core\Config\Config;
use Objex\Models\ObjectSchema;
use PHPUnit\Framework\TestCase;

class AppTest extends TestCase
{
    public function testBasic()
    {
        $this->assertTrue(true);
    }

    public function testConfig()
    {
        //Config::initDefaultConfig(objex());

        objex()->get('config')->setConfig([
            'logger' => [
                'path' => 'explicit/m-path'
            ]
        ]);

        dump(objex()->get('config')->getConfig('logger'));
    }
}