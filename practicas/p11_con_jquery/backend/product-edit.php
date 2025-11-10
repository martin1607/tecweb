<?php
include_once __DIR__.'/database.php';

header('Content-Type: application/json');

$producto = file_get_contents('php://input');
$data = array(
    'status'  => 'error',
    'message' => 'Error al actualizar producto'
);

if(!empty($producto)) {
    try {
        $jsonOBJ = json_decode($producto);
        
        if (!$jsonOBJ) {
            throw new Exception('JSON inválido');
        }
        
        // Verificar que el producto existe
        $sql_check = "SELECT * FROM productos WHERE id = {$jsonOBJ->id} AND eliminado = 0";
        $result_check = $conexion->query($sql_check);
        
        if ($result_check->num_rows > 0) {
            $conexion->set_charset("utf8");
            
            // Actualizar producto
            $sql = "UPDATE productos SET 
                    nombre = '{$jsonOBJ->nombre}',
                    marca = '{$jsonOBJ->marca}',
                    modelo = '{$jsonOBJ->modelo}',
                    precio = {$jsonOBJ->precio},
                    descripcion = '{$jsonOBJ->descripcion}',
                    unidades = {$jsonOBJ->unidades},
                    imagen = '{$jsonOBJ->imagen}'
                    WHERE id = {$jsonOBJ->id}";
            
            if($conexion->query($sql)){
                $data['status'] = "success";
                $data['message'] = "Producto actualizado correctamente";
            } else {
                throw new Exception("ERROR: No se ejecuto $sql. " . mysqli_error($conexion));
            }
        } else {
            $data['message'] = "El producto no existe o ha sido eliminado";
        }

        $result_check->free();
    } catch (Exception $e) {
        $data['message'] = $e->getMessage();
    }
}

$conexion->close();
echo json_encode($data, JSON_PRETTY_PRINT);
?>