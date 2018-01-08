<?php
/**
 * Created by PhpStorm.
 * User: dk
 * Date: 04.01.18
 * Time: 10:58
 */

namespace Simplex\Tests;

use Objex\DBStorage\Models\ObjectSchema;
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
            ->get('DBStorage')
            ->getRepository('Objex\DBStorage\Models\ObjectSchema')
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
            ->get('DBStorage')
            ->getRepository('Objex\DBStorage\Models\ObjectSchema')
            ->findOneBy(['name' => 'MyNamespace']));

        $this->expectException(\Exception::class);
        getSchema('MyNamespace');
    }

    public function testSchemaMethods()
    {
        $definition = [
            'foo' => [
                'type' => 'text',
                'validation' => 'required'
            ]
        ];

        $configuration = [
            'validationType' => 'min', //default is only;  'only' is: no more attributes, 'min' is there are more allowed - these will do not have validation
        ];

        setSchema('MyNamespace', [
            'definition' => $definition
        ]);

        $schema = getSchema('MyNamespace');
        $this->assertTrue(is_array($schema->getConfiguration()));
        $this->assertEquals('only', $schema->getValidationType());

        setSchema('MyNamespace', [
            'definition' => $definition,
            'configuration' => $configuration
        ]);

        $schema = getSchema('MyNamespace');

        $this->assertEquals($definition, $schema->getDefinition());
        $this->assertEquals($configuration, $schema->getConfiguration());
        $this->assertEquals('min', $schema->getValidationType());

        deleteSchema('MyNamespace');
    }
}