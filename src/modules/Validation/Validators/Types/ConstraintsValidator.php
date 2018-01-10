<?php
/**
 * Created by PhpStorm.
 * User: dk
 * Date: 10.01.18
 * Time: 08:21
 */

namespace Objex\Validation\Validators\Types;


use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ConstraintsValidator
{
    /**
     * @var ValidatorInterface
     */
    private $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function validate(string $attribute, $definition): ConstraintViolationListInterface
    {
        return $this->validator->validate($attribute, $definition);
    }
}