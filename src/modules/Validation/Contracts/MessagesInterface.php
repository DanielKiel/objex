<?php
/**
 * Created by PhpStorm.
 * User: dk
 * Date: 10.01.18
 * Time: 10:53
 */

namespace Objex\Validation\Contracts;


interface MessagesInterface
{
    /**
     * @return bool
     */
    public function hasErrors(): bool;

    /**
     * @return string
     */
    public function getMessage(): string;
}