<?php
include_once __DIR__.'/database.php';

header('Content-Type: application/json');

$data = array();

try {
    $search = isset($_GET['search']) ? $conexion->real_escape_string($_GET['search']) : '';

    if (!empty($search)) {
        $sql = "SELECT * FROM productos WHERE 
                (id = '$search' OR 
                 nombre LIKE '%$search%' OR 
                 marca LIKE '%$search%' OR 
                 descripcion LIKE '%$search%' OR  // CAMBIADO: detalles -> descripcion
                 modelo LIKE '%$search%') 
                AND eliminado = 0";
    } else {
        $sql = "SELECT * FROM productos WHERE eliminado = 0";
    }

    if ($result = $conexion->query($sql)) {
        if($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                // Convertir a UTF-8 si es necesario
                foreach($row as $key => $value) {
                    $row[$key] = utf8_encode($value);
                }
                $data[] = $row;
            }
        }
        $result->free();
    }
} catch (Exception $e) {
    $data = array('error' => 'Error en la consulta: ' . $e->getMessage());
}

$conexion->close();
echo json_encode($data);
?>