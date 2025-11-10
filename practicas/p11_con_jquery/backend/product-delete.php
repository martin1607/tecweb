<?php
include_once __DIR__.'/database.php';

header('Content-Type: application/json');

$data = array(
    'status'  => 'error',
    'message' => 'La consulta falló'
);

if(isset($_GET['id'])) {
    try {
        $id = $_GET['id'];
        
        $sql = "UPDATE productos SET eliminado=1 WHERE id = {$id}";
        if ($conexion->query($sql)) {
            $data['status'] = "success";
            $data['message'] = "Producto eliminado correctamente";
        } else {
            throw new Exception("ERROR: No se ejecuto $sql. " . mysqli_error($conexion));
        }
    } catch (Exception $e) {
        $data['message'] = $e->getMessage();
    }
    
    $conexion->close();
} 

echo json_encode($data, JSON_PRETTY_PRINT);
?>