<?php
/**
 * Created by PhpStorm.
 * User: dk
 * Date: 09.01.18
 * Time: 08:26
 */

namespace Objex\Security\Exceptions;


class AccessDeniedException extends \Exception
{
    public function __construct()
    {
        parent::__construct('permission denied', 0);
    }
}