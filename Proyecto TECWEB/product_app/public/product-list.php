<?php
// product-list.php
header('Content-Type: application/json; charset=utf-8');

$host = 'localhost';
$user = 'root';
$pass = 'Axelchivas1607';
$db   = 'marketzone_fix';

$mysqli = new mysqli($host, $user, $pass, $db);
if ($mysqli->connect_errno) {
    echo json_encode([
        'error' => 'Error de conexión: ' . $mysqli->connect_error
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

// Sólo recursos NO eliminados
$sql = "SELECT 
            id,
            nombre,
            autor,
            departamento,
            empresa,
            DATE_FORMAT(fecha_creacion, '%Y-%m-%d') AS fecha_creacion,
            descripcion,
            archivo,
            extension
        FROM recursos
        WHERE eliminado = 0
        ORDER BY id DESC";

$result = $mysqli->query($sql);

$recursos = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $recursos[] = $row;
    }
    $result->free();
}

$mysqli->close();

// Si no hay registros, devolvemos arreglo vacío
echo json_encode($recursos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
