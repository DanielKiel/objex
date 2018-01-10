<?php
/**
 * Created by PhpStorm.
 * User: dk
 * Date: 06.01.18
 * Time: 11:05
 */


class ConstraintValidationTest extends \PHPUnit\Framework\TestCase
{
    public function testArrayGetFalseValidation()
    {
        //see here to learn more: https://symfony.com/doc/master/validation.html
        $definition = [
            'foo' => [
                'type' => 'text',
                'validation' => [
                    new \Symfony\Component\Validator\Constraints\Length(['min' => 5]),
                    new \Symfony\Component\Validator\Constraints\Email()
                ]
            ],
            'bar' => [
                'type' => 'text',
                'validation' => new \Symfony\Component\Validator\Constraints\Length(['max' => 2]),
            ],
            'bar_2' => [
                'type' => 'text',
                'validation' => new \Symfony\Component\Validator\Constraints\Length(['max' => 2]),
                'errormessage' => 'bar must not have more than 2 signs - cause of default message'
            ]
        ];

        $attributes = [
            'foo' => 'needed value',
            'bar' => 'value',
            'bar_2' => 'value'
        ];

        $validator = new \Objex\Validation\Validators\BaseObjectValidator(new \Objex\DBStorage\Models\BaseObject());

        $result = $validator->validate($attributes, $definition);

        $this->assertEquals([
            "foo" => "This value is not a valid email address.",
            "bar" => "This value is too long. It should have 2 characters or less.",
            "bar_2" => "bar must not have more than 2 signs - cause of default message"
        ], $result);

        //when an object, it must be of instance Constraint, so an exception will be thrown
        $definition = [
            'bar' => [
                'type' => 'text',
                'validation' => (object) ['some silly stuff'],
                'errormessage' => 'bar must not have more than 2 signs'
            ]
        ];

        $this->expectException(Exception::class);

        $validator->validate($attributes, $definition);
    }
}
