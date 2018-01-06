<?php
/**
 * Created by PhpStorm.
 * User: dk
 * Date: 06.01.18
 * Time: 11:05
 */


class RemoveUnAllowedAttributesTest extends \PHPUnit\Framework\TestCase
{
    public function testRemovingAttributesNeeded()
    {
        //for this test, content of each attr is not relevant
        $definition = [
            'foo' => [

            ],
            'bar' => [

            ]
        ];

        $attributes = [
            'foo' => 'value',
            'bar' => 'value',
            'unallowed_foo' => 'value',
            'unallowed_bar' => 'value'
        ];

        $result = \Objex\Validation\Util\RemoveUnAllowedAttributes::remove($attributes, $definition);

        $this->assertEquals([
            'foo' => 'value',
            'bar' => 'value',
        ], $result);
    }

    public function testRemovingAttributesNotNeeded()
    {
        //for this test, content of each attr is not relevant
        $definition = [
            'foo' => [

            ],
            'bar' => [

            ]
        ];

        $attributes = [
            'foo' => 'value',
            'bar' => 'value'
        ];

        $result = \Objex\Validation\Util\RemoveUnAllowedAttributes::remove($attributes, $definition);

        $this->assertEquals($attributes,$result);
    }
}
