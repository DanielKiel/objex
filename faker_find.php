<?php
/**
 * Created by PhpStorm.
 * User: dk
 * Date: 04.01.18
 * Time: 15:52
 */
require_once 'vendor/autoload.php';
$sc = include "src/bootstrap/services.php";

$productRepository = $sc->get('orm')->getRepository('Objex\Models\BaseObject');
$products = $productRepository->findAll();

foreach ($products as $product) {
    echo sprintf("-%s\n", $product->getToken());
    dump( $product->getData());
}