<?php
/**
 * Created by PhpStorm.
 * User: dk
 * Date: 04.01.18
 * Time: 10:11
 */
use Symfony\Component\Routing;

$routes = new Routing\RouteCollection();

$routes->add('api', new Routing\Route('/api/{name}', array(
    'name' => null,
    '_controller' => 'Objex\Controllers\APIController::indexAction',
)));

return $routes;