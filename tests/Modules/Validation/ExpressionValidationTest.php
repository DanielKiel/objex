<?php
/**
 * Created by PhpStorm.
 * User: dk
 * Date: 06.01.18
 * Time: 11:05
 */


class ExpressionValidationTest extends \PHPUnit\Framework\TestCase
{
    public function testArrayGetFalseValidation()
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

        $attributes = [
            'foo' => 'needed value',
            'bar' => 'value'
        ];

        $validator = new \Objex\Validation\Validators\BaseObjectValidator(new \Objex\DBStorage\Models\BaseObject());

        $result = $validator->validate($attributes, $definition);

        $this->assertEquals(['bar'=>'bar must not have more than 2 signs'],$result);
    }
}
