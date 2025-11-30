<?php
// product_app/public/index.php

// ------------- CONFIGURACIÓN Y CLASES -------------
require __DIR__ . '/../src/DataBase.php';
require __DIR__ . '/../src/create/ProductsCreate.php';
require __DIR__ . '/../src/Update/ProductsUpdate.php';
require __DIR__ . '/../src/Delete/ProductsDelete.php';
require __DIR__ . '/../src/Read/ProductsSingle.php';
require __DIR__ . '/../src/Read/ProductsList.php';
require __DIR__ . '/../src/Read/ProductsSearch.php';

use ProductApp\Create\ProductsCreate;
use ProductApp\Update\ProductsUpdate;
use ProductApp\Delete\ProductsDelete;
use ProductApp\Read\ProductsSingle;
use ProductApp\Read\ProductsList;
use ProductApp\Read\ProductsSearch;


$dbname = 'marketzone_fix';

// Siempre respondemos JSON
header('Content-Type: application/json; charset=utf-8');

// ------------- OBTENER MÉTODO Y RUTA -------------
$method = $_SERVER['REQUEST_METHOD'];            // GET, POST, PUT, DELETE
$uri    = $_SERVER['REQUEST_URI'];               // /.../index.php/products/5?x=1
$script = $_SERVER['SCRIPT_NAME'];               // /.../index.php

// Quitar query string
$uri = explode('?', $uri)[0];

// Obtener la parte después de index.php
$baseLen = strlen($script);
$path = substr($uri, $baseLen);                  // ej: /products, /product/5, etc.
$path = rtrim($path, '/');                       // quitar / final
if ($path === false) {
    $path = '';
}

// Helper para leer datos de PUT/DELETE
function getBodyParams() {
    $data = [];
    $raw = file_get_contents('php://input');
    if ($raw) {
        parse_str($raw, $data);  // soporta "id=1&nombre=x"
    }
    return $data;
}

// ------------- RUTEO MANUAL (sin Slim) -------------

try {

    // ----------------- GET -----------------
    if ($method === 'GET') {

        // GET index.php/products
        if ($path === '' || $path === '/products') {
            $list = new ProductsList($GLOBALS['dbname']);
            $list->list();
            $products = $list->getResponse();
            echo json_encode($products);
            exit;
        }

        // GET index.php/product/{id}
        if (preg_match('#^/product/(\d+)$#', $path, $matches)) {
            $id = $matches[1];

            $single = new ProductsSingle($GLOBALS['dbname']);
            $single->single($id);
            $product = $single->getResponse();
            echo json_encode($product);
            exit;
        }

        // GET index.php/products/{search}
        if (preg_match('#^/products/(.+)$#', $path, $matches)) {
            $text = urldecode($matches[1]);

            $search = new ProductsSearch($GLOBALS['dbname']);
            $search->search($text);
            $products = $search->getResponse();
            echo json_encode($products);
            exit;
        }

        // Ruta desconocida
        http_response_code(404);
        echo json_encode([
            'status'  => 'error',
            'message' => 'Ruta GET no encontrada'
        ]);
        exit;
    }

    // ----------------- POST -----------------
    if ($method === 'POST') {

        // POST index.php/product  -> crear
        if ($path === '/product' || $path === '') {
            $body = $_POST;                 // viene de $.ajax data
            $jsonOBJ = (object)$body;

            $creator = new ProductsCreate($GLOBALS['dbname']);
            $creator->add($jsonOBJ);
            $result = $creator->getResponse();

            echo json_encode($result);
            exit;
        }

        http_response_code(404);
        echo json_encode([
            'status'  => 'error',
            'message' => 'Ruta POST no encontrada'
        ]);
        exit;
    }

    // ----------------- PUT -----------------
    if ($method === 'PUT') {

        // PUT index.php/product  -> editar
        if ($path === '/product' || $path === '') {
            $body = getBodyParams();        // leer cuerpo de la petición
            $jsonOBJ = (object)$body;

            $updater = new ProductsUpdate($GLOBALS['dbname']);
            $updater->edit($jsonOBJ);
            $result = $updater->getResponse();

            echo json_encode($result);
            exit;
        }

        http_response_code(404);
        echo json_encode([
            'status'  => 'error',
            'message' => 'Ruta PUT no encontrada'
        ]);
        exit;
    }

    // ----------------- DELETE -----------------
    if ($method === 'DELETE') {

        // DELETE index.php/product  -> eliminar
        if ($path === '/product' || $path === '') {
            $body = getBodyParams();
            $id = isset($body['id']) ? $body['id'] : null;

            if ($id === null) {
                echo json_encode([
                    'status'  => 'error',
                    'message' => 'Falta id de producto'
                ]);
                exit;
            }

            $deleter = new ProductsDelete($GLOBALS['dbname']);
            $deleter->delete($id);
            $result = $deleter->getResponse();

            echo json_encode($result);
            exit;
        }

        http_response_code(404);
        echo json_encode([
            'status'  => 'error',
            'message' => 'Ruta DELETE no encontrada'
        ]);
        exit;
    }

    // -------- Otros métodos no soportados --------
    http_response_code(405);
    echo json_encode([
        'status'  => 'error',
        'message' => 'Método no permitido'
    ]);
    exit;

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'status'  => 'error',
        'message' => 'Error interno: ' . $e->getMessage()
    ]);
    exit;
}
