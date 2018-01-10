<?php
/**
 * Created by PhpStorm.
 * User: dk
 * Date: 04.01.18
 * Time: 10:11
 */
use Symfony\Component\Routing;

$routes = new Routing\RouteCollection();

$routes->add('home', new Routing\Route('/', array(
    'name' => null,
    '_controller' => 'Objex\Core\Controllers\HomeController::indexAction',
)));

return $routes;