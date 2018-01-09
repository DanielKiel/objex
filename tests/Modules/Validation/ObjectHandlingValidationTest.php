<?php
/**
 * Created by PhpStorm.
 * User: dk
 * Date: 06.01.18
 * Time: 11:05
 */


class ObjectHandlingValidationTest extends \PHPUnit\Framework\TestCase
{
    public function testValidation()
    {
        $definition = [
            'foo' => [
                'type' => 'text',
                'validation' => 'strpos(foo, "needed") !== false and strlen(foo) > 3'
            ],
            'bar' => [
                'type' => 'text',
                'validation' => 'strlen(bar) < 3',
                'errormessage' => 'bar must not have more than 2 signs'
            ]
        ];

        setSchema('MyNamespace', [
            'definition' => $definition
        ]);

        $this->expectException(\Objex\Validation\Exceptions\ValidationException::class);

        saveObject('MyNamespace',[
            'foo' => 'needed value',
            'bar' => 'value'
        ]);

        deleteSchema('MyNamespace');
    }
}
