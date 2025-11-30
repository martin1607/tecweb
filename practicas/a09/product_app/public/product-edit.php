<?php
require_once __DIR__.'/../vendor/autoload.php';

use ProductApp\Update\ProductsUpdate;

header('Content-Type: application/json');

$productos = new ProductsUpdate('marketzone_fix');
$productos->edit(json_decode(json_encode($_POST)));
echo json_encode($productos->getResponse(), JSON_PRETTY_PRINT);
?>