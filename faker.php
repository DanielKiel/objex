<?php
/**
 * Created by PhpStorm.
 * User: dk
 * Date: 04.01.18
 * Time: 15:52
 */
require_once 'vendor/autoload.php';
$sc = include "src/bootstrap/services.php";

$newProductName = $argv[1];

$product = new \Objex\Models\Object();
$product->setName($newProductName);

$sc->get('orm')->persist($product);
$sc->get('orm')->flush();

echo "Created Product with ID " . $product->getId() . "\n";