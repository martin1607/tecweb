<?php
include_once __DIR__.'/database.php';

header('Content-Type: application/json');

$data = array();

try {
    if(isset($_GET['search']) && !empty($_GET['search'])) {
        $search = $conexion->real_escape_string($_GET['search']);
        
        $sql = "SELECT * FROM productos WHERE 
                (id = '$search' OR 
                 nombre LIKE '%$search%' OR 
                 marca LIKE '%$search%' OR 
                 descripcion LIKE '%$search%' OR  // CAMBIADO: detalles -> descripcion
                 modelo LIKE '%$search%') 
                AND eliminado = 0";
        
        if ($result = $conexion->query($sql)) {
            if($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    foreach($row as $key => $value) {
                        $row[$key] = utf8_encode($value);
                    }
                    $data[] = $row;
                }
            }
            $result->free();
        }
    }
} catch (Exception $e) {
    $data = array('error' => 'Error en la búsqueda: ' . $e->getMessage());
}

$conexion->close();
echo json_encode($data);
?>