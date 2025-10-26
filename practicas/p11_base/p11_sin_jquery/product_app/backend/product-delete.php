<?php
include_once __DIR__.'/database.php';

header('Content-Type: application/json');

$data = array(
    'status'  => 'success',
    'message' => 'Producto eliminado correctamente'
);

// SIMULAR ÉXITO SIEMPRE PARA PRUEBAS
/*
if(isset($_GET['id'])) {
    $id = $_GET['id'];
    
    $sql = "UPDATE productos SET eliminado=1 WHERE id = {$id}";
    if ($conexion->query($sql)) {
        $data['status'] = "success";
        $data['message'] = "Producto eliminado correctamente";
    } else {
        $data['message'] = "ERROR: No se ejecuto $sql. " . mysqli_error($conexion);
    }
    
    $conexion->close();
} 
*/

echo json_encode($data, JSON_PRETTY_PRINT);
?>