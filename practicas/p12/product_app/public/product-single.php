<?php
require_once __DIR__.'/../vendor/autoload.php';

use ProductApp\Read\ProductsSingle;

header('Content-Type: application/json');

try {
    // Verificar que se recibió el ID
    if (!isset($_POST['id']) || empty($_POST['id'])) {
        echo json_encode(['error' => 'No se recibió el ID del producto']);
        exit;
    }

    $id = $_POST['id'];
    
    $productos = new ProductsSingle('marketzone_fix');
    $productos->single($id);
    $response = $productos->getResponse();

    // Si la respuesta es un string (error), convertirlo a array
    if (is_string($response)) {
        echo json_encode(['error' => $response]);
    }
    // Si es un array con error
    else if (isset($response['error'])) {
        echo json_encode(['error' => $response['error']]);
    } 
    // Si está vacío
    else if (empty($response)) {
        echo json_encode(['error' => 'Producto no encontrado']);
    }
    // Si es un array válido con datos
    else {
        echo json_encode($response);
    }
    
} catch (Exception $e) {
    echo json_encode(['error' => 'Error del servidor: ' . $e->getMessage()]);
}
?>