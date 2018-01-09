<?php
/**
 * Created by PhpStorm.
 * User: dk
 * Date: 04.01.18
 * Time: 10:58
 */

namespace Simplex\Tests;

use PHPUnit\Framework\TestCase;

class CryptographyTest extends TestCase
{
    public function testEnAndDecrypt()
    {
        $string = 'Hello Objex';
        $crypto = objex()->get('crypto');

        $encrypted = $crypto->encryt($string);

        $decryped = $crypto->decrypt($encrypted);

        $this->assertNotEquals($string, $encrypted);
        $this->assertNotEquals($decryped, $encrypted);
        $this->assertEquals($string, $decryped);
    }
}