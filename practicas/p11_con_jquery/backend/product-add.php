<?php
include_once __DIR__.'/database.php';

header('Content-Type: application/json');

$producto = file_get_contents('php://input');
$data = array(
    'status'  => 'error',
    'message' => 'Error al agregar producto'
);

if(!empty($producto)) {
    try {
        $jsonOBJ = json_decode($producto);
        
        if (!$jsonOBJ) {
            throw new Exception('JSON inválido');
        }
        
        // Verificar si ya existe un producto con ese nombre
        $sql = "SELECT * FROM productos WHERE nombre = '{$jsonOBJ->nombre}' AND eliminado = 0";
        $result = $conexion->query($sql);
        
        if ($result->num_rows == 0) {
            $conexion->set_charset("utf8");
            
            // USAR NOMBRES CORRECTOS DE COLUMNAS - descripcion en lugar de detalles
            $sql = "INSERT INTO productos VALUES (null, '{$jsonOBJ->nombre}', '{$jsonOBJ->marca}', '{$jsonOBJ->modelo}', {$jsonOBJ->precio}, '{$jsonOBJ->descripcion}', {$jsonOBJ->unidades}, '{$jsonOBJ->imagen}', 0)";
            
            if($conexion->query($sql)){
                $data['status'] = "success";
                $data['message'] = "Producto agregado correctamente";
            } else {
                throw new Exception("ERROR: No se ejecuto $sql. " . mysqli_error($conexion));
            }
        } else {
            $data['message'] = "Ya existe un producto con ese nombre";
        }

        $result->free();
    } catch (Exception $e) {
        $data['message'] = $e->getMessage();
    }
}

$conexion->close();
echo json_encode($data, JSON_PRETTY_PRINT);
?>