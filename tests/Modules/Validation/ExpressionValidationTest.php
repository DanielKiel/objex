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
                'validation' => 'strlen(foo) > 3'
            ],
            'bar' => [
                'type' => 'text',
                'validation' => 'strlen(bar) < 3',
                'errormessage' => 'bar must not have more than 2 signs'
            ]
        ];

        $attributes = [
            'foo' => 'va',
            'bar' => 'value'
        ];

        $validator = new \Objex\Validation\Validator();

        $result = $validator->validate($attributes, $definition);

        dump($result);
    }
}
