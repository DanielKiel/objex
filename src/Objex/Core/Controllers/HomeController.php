<?php
/**
 * Created by PhpStorm.
 * User: dk
 * Date: 09.01.18
 * Time: 14:28
 */

namespace Objex\Core\Controllers;


use Symfony\Component\HttpFoundation\JsonResponse;

class HomeController
{
    public function indexAction()
    {
        return new JsonResponse([
            'hello' => 'objex',
            'framework' => 'event driven, api orientated, json orientated',
            'licence' => 'MIT',
            'follow' => 'https://github.com/DanielKiel/objex'
        ]);
    }
}