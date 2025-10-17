<?php
header('Content-Type: application/json');
include("database.php");

if (isset($_GET['id'])) {
    // Buscar por ID
    $id = intval($_GET['id']);
    $sql = "SELECT * FROM productos WHERE id = $id AND eliminado = 0";
} else {
    // Mostrar todos
    $sql = "SELECT * FROM productos WHERE eliminado = 0";
}

$result = mysqli_query($conexion, $sql);
$productos = [];

if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        // Normalizar campo de descripción
        $row['descripcion'] = $row['detalles'] ?? '';
        $productos[] = $row;
    }
}

// Si se pidió por ID, devolver solo uno
if (isset($_GET['id'])) {
    echo json_encode(isset($productos[0]) ? $productos[0] : ["error" => "Producto no encontrado"]);
} else {
    echo json_encode($productos);
}

mysqli_close($conexion);
?>
