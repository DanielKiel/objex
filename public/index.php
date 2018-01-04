<?php
/**
 * Created by PhpStorm.
 * User: dk
 * Date: 04.01.18
 * Time: 09:38
 */

require_once __DIR__.'/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;

$routes = $routes = include __DIR__.'/../src/routes.php';

$sc = include __DIR__.'/../src/bootstrap.php';

$request = Request::createFromGlobals();

$response = $sc->get('app')->handle($request);

$response->send();