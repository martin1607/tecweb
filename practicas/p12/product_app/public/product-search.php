<?php
// CONEXIÓN DIRECTA - CORREGIDO
$conexion = mysqli_connect('localhost', 'root', 'Axelchivas1607', 'marketzone_fix');

header('Content-Type: application/json');

if (!$conexion) {
    echo json_encode([]);
    exit;
}

if (!isset($_GET['search']) || empty($_GET['search'])) {
    echo json_encode([]);
    exit;
}

$search = mysqli_real_escape_string($conexion, $_GET['search']);

// CORREGIDO: usar descripcion en lugar de detalles
$sql = "SELECT * FROM productos WHERE 
       (id = '$search' OR 
        nombre LIKE '%$search%' OR 
        marca LIKE '%$search%' OR 
        descripcion LIKE '%$search%') 
       AND eliminado = 0";

$result = mysqli_query($conexion, $sql);

$productos = [];
if ($result && $result->num_rows > 0) {
    while($row = mysqli_fetch_assoc($result)) {
        $productos[] = $row;
    }
}

echo json_encode($productos, JSON_PRETTY_PRINT);
mysqli_close($conexion);
?>