<?php
// test-query.php
header('Content-Type: application/json');

include_once __DIR__.'/database.php';

$data = array();

// Probar consulta básica
$sql = "SELECT * FROM productos WHERE eliminado = 0 LIMIT 5";

echo "SQL: " . $sql . "\n\n";

if ($result = $conexion->query($sql)) {
    echo "✓ Query ejecutada correctamente\n";
    echo "Número de resultados: " . $result->num_rows . "\n\n";
    
    if($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        echo "Datos encontrados:\n";
        print_r($data);
    } else {
        echo "✓ No hay productos en la base de datos\n";
    }
    $result->free();
} else {
    echo "✗ ERROR en query: " . mysqli_error($conexion) . "\n";
}

$conexion->close();

//echo json_encode($data);
?>