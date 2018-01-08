<?php
/**
 * Created by PhpStorm.
 * User: dk
 * Date: 08.01.18
 * Time: 11:15
 */

namespace Objex\Validation\Controllers;


use Objex\Validation\Exceptions\ValidationException;
use Symfony\Component\HttpFoundation\JsonResponse;

class ErrorController
{
    /**
     * @param ValidationException $exception
     * @return JsonResponse
     */
    public function errorAction(ValidationException $exception)
    {
        return new JsonResponse([
            'errors' => $exception->getErrors()
        ], 422);
    }

}