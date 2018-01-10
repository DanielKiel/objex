<?php
/**
 * Created by PhpStorm.
 * User: dk
 * Date: 10.01.18
 * Time: 09:44
 */

namespace Objex\Validation\Messages;


use Objex\Validation\Contracts\MessagesInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class MessageResolver
{
    private $result;

    private $ruleSet = [];

    /** @var MessagesInterface */
    private $messager;

    /** @var string  */
    private $defaultMessage = 'there was an error on validating this attribute';

    /**
     * MessageResolver constructor.
     * @param bool|ConstraintViolationListInterface $result
     * @param array $ruleset
     */
    public function __construct($result, array $ruleset = [])
    {
        if (is_bool($result)) {
            $this->messager = new MessagesByExpression($result, $ruleset);

            return;
        }

        $this->messager = new MessagesByConstraints($result, $ruleset);
    }

    /**
     * @return bool
     */
    public function hasErrors(): bool
    {
        return $this->messager->hasErrors();
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->messager->getMessage();
    }
}