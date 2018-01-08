<?php
/**
 * Created by PhpStorm.
 * User: dk
 * Date: 08.01.18
 * Time: 10:29
 */

namespace Objex\Validation\Exceptions;


class ValidationException extends \Exception
{
    private $errors = [];

    /**
     * ValidationException constructor.
     * @param array $errors
     */
    public function __construct(array $errors = [])
    {
        $this->errors = $errors;

        parent::__construct($message = "validation waa not successful", $code = 0, $previous = null);
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}