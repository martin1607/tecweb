<?php
require __DIR__ . '/vendor/autoload.php';

// Importar clases propias
require __DIR__ . '/myapi/DataBase.php';
require __DIR__ . '/myapi/CREATE/Create.php';
require __DIR__ . '/myapi/READ/Read.php';
require __DIR__ . '/myapi/UPDATE/Update.php';
require __DIR__ . '/myapi/DELETE/Delete.php';

use MYAPI\CREATE\Create;
use MYAPI\READ\Read;
use MYAPI\UPDATE\Update;
use MYAPI\DELETE\Delete;

// Nombre de la BD
$dbname = "marketzone";

// Crear app Slim 3
$app = new \Slim\App();

// ============================================
// ===============   RUTAS API  ===============
// ============================================

// GET /products → Lista completa
$app->get('/products', function($req, $res) use ($dbname) {
    $p = new Read($dbname);
    $p->list();
    return $res->withJson($p->getResponse());
});

// GET /products/{texto} → Búsqueda
$app->get('/products/{txt}', function($req, $res, $args) use ($dbname) {
    $p = new Read($dbname);
    $p->search($args['txt']);
    return $res->withJson($p->getResponse());
});

// GET /product/{id} → Un producto
$app->get('/product/{id}', function($req, $res, $args) use ($dbname) {
    $p = new Read($dbname);
    $p->single($args['id']);
    return $res->withJson($p->getResponse());
});

// POST /product → Crear
$app->post('/product', function($req, $res) use ($dbname) {
    $json = (object) $req->getParsedBody();

    $p = new Create($dbname);
    $p->add($json);

    return $res->withJson($p->getResponse());
});

// PUT /product → Actualizar
$app->put('/product', function($req, $res) use ($dbname) {
    $json = (object) $req->getParsedBody();

    $p = new Update($dbname);
    $p->edit($json);

    return $res->withJson($p->getResponse());
});

// DELETE /product → Eliminar
$app->delete('/product', function($req, $res) use ($dbname) {
    $body = $req->getParsedBody();

    if (!isset($body['id'])) {
        return $res->withJson([
            "status" => "error",
            "message" => "ID requerido"
        ]);
    }

    $p = new Delete($dbname);
    $p->remove($body['id']);

    return $res->withJson($p->getResponse());
});

// Ejecutar
$app->run();
