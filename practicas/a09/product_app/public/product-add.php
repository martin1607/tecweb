<?php
require_once __DIR__.'/../vendor/autoload.php';

use ProductApp\Create\ProductsCreate;

header('Content-Type: application/json');

$productos = new ProductsCreate('marketzone_fix');
$productos->add(json_decode(json_encode($_POST)));
echo json_encode($productos->getResponse(), JSON_PRETTY_PRINT);
?>