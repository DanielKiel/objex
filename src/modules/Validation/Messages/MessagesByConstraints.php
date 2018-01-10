<?php
/**
 * Created by PhpStorm.
 * User: dk
 * Date: 10.01.18
 * Time: 10:52
 */

namespace Objex\Validation\Messages;


use Objex\Validation\Contracts\MessagesInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class MessagesByConstraints implements MessagesInterface
{
    private $result;

    private $ruleSet = [];

    /** @var string  */
    private $defaultMessage = 'there was an error on validating this attribute';

    /**
     * MessageResolver constructor.
     * @param ConstraintViolationListInterface $result
     * @param array $ruleset
     */
    public function __construct(ConstraintViolationListInterface $result, array $ruleset = [])
    {
        $this->result = $result;

        $this->ruleSet = $ruleset;
    }

    /**
     * @return bool
     */
    public function hasErrors(): bool
    {
        return $this->result->count() > 0;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        if (array_key_exists('errormessage', $this->ruleSet)) {
            return $this->ruleSet['errormessage'];
        }

        if ($this->result->count() > 0) {
            $message = '';
            foreach ($this->result as $index => $result) {
                $message .= $this->getMessageString($index, $result);
            }

            return $message;
        }

        return $this->defaultMessage;
    }

    /**
     * @param int $index
     * @param $result
     * @return string
     */
    protected function getMessageString(int $index, $result): string
    {
        if ($index === 0) {
            return $result->getMessage();
        }

        return ' | ' .$result->getMessage();
    }
}