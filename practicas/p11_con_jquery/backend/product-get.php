<?php
include_once __DIR__.'/database.php';

header('Content-Type: application/json');

$data = null;

if(isset($_GET['id'])) {
    try {
        $id = $_GET['id'];
        
        $sql = "SELECT * FROM productos WHERE id = {$id} AND eliminado = 0";
        if ($result = $conexion->query($sql)) {
            if($result->num_rows > 0) {
                $data = $result->fetch_assoc();
                // Convertir a UTF-8
                foreach($data as $key => $value) {
                    $data[$key] = utf8_encode($value);
                }
            }
            $result->free();
        }
    } catch (Exception $e) {
        $data = array('error' => $e->getMessage());
    }
}

$conexion->close();
echo json_encode($data);
?>