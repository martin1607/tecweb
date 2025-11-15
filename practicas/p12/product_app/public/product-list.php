<?php
// CONEXIÓN DIRECTA - FUNCIONA CON TU BASE DE DATOS
$conexion = mysqli_connect('localhost', 'root', 'Axelchivas1607', 'marketzone_fix');

header('Content-Type: application/json');

if (!$conexion) {
    echo "[]";
    exit;
}

$result = mysqli_query($conexion, "SELECT * FROM productos WHERE eliminado = 0");

$productos = array();
if ($result) {
    while($row = mysqli_fetch_assoc($result)) {
        $productos[] = $row;
    }
}

// DEVOLVER SIEMPRE ARRAY VÁLIDO
if (empty($productos)) {
    echo "[]";
} else {
    echo json_encode($productos);
}

mysqli_close($conexion);
?>