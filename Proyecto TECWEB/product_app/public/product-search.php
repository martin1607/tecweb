<?php
// product-search.php
header('Content-Type: application/json; charset=utf-8');

$search = isset($_GET['search']) ? trim($_GET['search']) : '';

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

if ($search === '') {
    echo json_encode([], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    $mysqli->close();
    exit;
}

$like = '%' . $search . '%';

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
          AND (
                nombre LIKE ?
             OR autor LIKE ?
             OR departamento LIKE ?
             OR empresa LIKE ?
             OR extension LIKE ?
          )
        ORDER BY id DESC";

$stmt = $mysqli->prepare($sql);
if (!$stmt) {
    echo json_encode([
        'error' => 'Error al preparar consulta: ' . $mysqli->error
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    $mysqli->close();
    exit;
}

$stmt->bind_param('sssss', $like, $like, $like, $like, $like);
$stmt->execute();
$result = $stmt->get_result();

$recursos = [];
while ($row = $result->fetch_assoc()) {
    $recursos[] = $row;
}

$result->free();
$stmt->close();
$mysqli->close();

echo json_encode($recursos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
