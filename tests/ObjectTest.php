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

class ObjectTest extends TestCase
{
    public function testBasic()
    {
        $this->assertTrue(true);
    }

    /**
     * thats all the basic functionallity to CRUD an ObjectSchema
     * @throws \PHPUnit\Framework\AssertionFailedError
     */
    public function testObjectCRUD()
    {
        setSchema('MyNamespace', [
            'foo' => 'bar'
        ]);

        $schema = getSchema('MyNamespace');

        saveObject('MyNamespace', [
            'foo' => 'bar'
        ]);

        $obj = objex()->get('orm')->getRepository('Objex\Models\BaseObject')->findBy(['schema' => $schema]);

        $saved = saveObject('MyNamespace', [
            'foo' => 'bar'
        ]);

        $obj = objex()->get('orm')->getRepository('Objex\Models\BaseObject')->findBy(['schema' => $schema]);

        $this->assertEquals(2, count($obj));

        $update = (array) $saved;
        $update['new'] = 'foo';
        $updObj = saveObject('MyNamespace',$update);
        $this->assertEquals('foo', $updObj->new);
        $this->assertEquals($saved->id, $updObj->id);

        $obj = objex()->get('orm')->getRepository('Objex\Models\BaseObject')->findBy(['schema' => $schema]);

        $this->assertEquals(2, count($obj));

        deleteObject('MyNamespace', $saved->id);

        $obj = objex()->get('orm')->getRepository('Objex\Models\BaseObject')->findBy(['schema' => $schema]);

        $this->assertEquals(1, count($obj));

        deleteSchema('MyNamespace');

        $obj = objex()->get('orm')->getRepository('Objex\Models\BaseObject')->findBy(['schema' => $schema]);

        $this->assertEquals(0, count($obj));
    }
}