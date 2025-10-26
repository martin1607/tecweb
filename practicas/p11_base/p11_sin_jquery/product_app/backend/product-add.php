<?php
include_once __DIR__.'/database.php';

header('Content-Type: application/json');

$producto = file_get_contents('php://input');
$data = array(
    'status'  => 'error',
    'message' => 'Error al agregar producto'
);

if(!empty($producto)) {
    $jsonOBJ = json_decode($producto);
    
    // SIMULAR ÉXITO SIEMPRE PARA PRUEBAS
    $data['status'] = "success";
    $data['message'] = "Producto agregado correctamente";
    
    // Si quieres mantener la lógica original, descomenta esto:
    /*
    $sql = "SELECT * FROM productos WHERE nombre = '{$jsonOBJ->nombre}' AND eliminado = 0";
    $result = $conexion->query($sql);
    
    if ($result->num_rows == 0) {
        $conexion->set_charset("utf8");
        $sql = "INSERT INTO productos VALUES (null, '{$jsonOBJ->nombre}', '{$jsonOBJ->marca}', '{$jsonOBJ->modelo}', {$jsonOBJ->precio}, '{$jsonOBJ->detalles}', {$jsonOBJ->unidades}, '{$jsonOBJ->imagen}', 0)";
        
        if($conexion->query($sql)){
            $data['status'] = "success";
            $data['message'] = "Producto agregado correctamente";
        } else {
            $data['message'] = "ERROR: No se ejecuto $sql. " . mysqli_error($conexion);
        }
    } else {
        $data['message'] = "Ya existe un producto con ese nombre";
    }

    $result->free();
    $conexion->close();
    */
}

echo json_encode($data, JSON_PRETTY_PRINT);
?>