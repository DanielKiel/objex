<?php
/**
 * Created by PhpStorm.
 * User: dk
 * Date: 10.01.18
 * Time: 10:51
 */

namespace Objex\Validation\Messages;


use Objex\Validation\Contracts\MessagesInterface;

class MessagesByExpression implements MessagesInterface
{
    private $result;

    private $ruleSet = [];

    /** @var string  */
    private $defaultMessage = 'there was an error on validating this attribute';

    /**
     * MessageResolver constructor.
     * @param bool $result
     * @param array $ruleset
     */
    public function __construct(bool $result, array $ruleset = [])
    {
        $this->result = $result;

        $this->ruleSet = $ruleset;
    }

    /**
     * @return bool
     */
    public function hasErrors(): bool
    {
        return ! $this->result;
    }

    /**
     * @return string
     */
    public function getMessage():string
    {
        if (array_key_exists('errormessage', $this->ruleSet)) {
            return $this->ruleSet['errormessage'];
        }

        return $this->defaultMessage;
    }
}