<?php
/**
 * Created by PhpStorm.
 * User: dk
 * Date: 04.01.18
 * Time: 10:58
 */

namespace Simplex\Tests;

use Objex\Models\ObjectSchema;
use PHPUnit\Framework\TestCase;

class ObjectSchemapTest extends TestCase
{
    /**
     * thats all the basic functionallity to CRUD an ObjectSchema
     * @throws \PHPUnit\Framework\AssertionFailedError
     */
    public function testSchemaCRUD()
    {
        setSchema('MyNamespace', [
            'foo' => 'bar'
        ]);

        $this->assertInstanceOf(
            ObjectSchema::class,
            objex()
            ->get('orm')
            ->getRepository('Objex\Models\ObjectSchema')
            ->findOneBy(['name' => 'MyNamespace'])
        );

        $this->assertInstanceOf(
            ObjectSchema::class,
            getSchema('MyNamespace')
        );

        $schema = getSchema('MyNamespace');
        $this->assertEquals('bar', $schema->getData()['foo']);

        setSchema('MyNamespace', [
            'foo' => 'bar_2'
        ]);

        $schema = getSchema('MyNamespace');
        $this->assertEquals('bar_2', $schema->getData()['foo']);

        deleteSchema('MyNamespace');

        $this->assertEmpty(objex()
            ->get('orm')
            ->getRepository('Objex\Models\ObjectSchema')
            ->findOneBy(['name' => 'MyNamespace']));

        $this->expectException(\Exception::class);
        getSchema('MyNamespace');
    }
}