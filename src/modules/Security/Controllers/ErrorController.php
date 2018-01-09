<?php
/**
 * Created by PhpStorm.
 * User: dk
 * Date: 08.01.18
 * Time: 11:15
 */

namespace Objex\Security\Controllers;


use Objex\Security\Exceptions\AccessDeniedException;
use Objex\Validation\Exceptions\ValidationException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ErrorController
{
    /**
     * @param ValidationException $exception
     * @return JsonResponse
     */
    public function errorAction(AccessDeniedException $exception)
    {
        return new Response('permission denied', 401);
    }

}