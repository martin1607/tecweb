<?php
require_once __DIR__.'/../vendor/autoload.php';

use ProductApp\Delete\ProductsDelete;

header('Content-Type: application/json');

$productos = new ProductsDelete('marketzone_fix');
$productos->delete($_POST['id']);
echo json_encode($productos->getResponse(), JSON_PRETTY_PRINT);
?>