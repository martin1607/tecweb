<?php
header('Content-Type: application/json; charset=utf-8');

// Solo aceptar POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'status'  => 'error',
        'message' => 'Método no permitido'
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

// Parámetros de conexión
$host = 'localhost';
$user = 'root';
$pass = 'Axelchivas1607';
$db   = 'marketzone_fix';

// Conexión
$mysqli = new mysqli($host, $user, $pass, $db);
if ($mysqli->connect_errno) {
    echo json_encode([
        'status'  => 'error',
        'message' => 'Error de conexión: ' . $mysqli->connect_error
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

$nombre        = trim($_POST['nombre']        ?? '');
$autor         = trim($_POST['autor']         ?? '');
$departamento  = trim($_POST['departamento']  ?? '');
$empresa       = trim($_POST['empresa']       ?? '');
$fecha_creacion= trim($_POST['fecha_creacion']?? '');
$descripcion   = trim($_POST['descripcion']   ?? '');

// Validaciones básicas
if ($nombre === '' || $autor === '' || $departamento === '' || $empresa === '' || $fecha_creacion === '') {
    echo json_encode([
        'status'  => 'error',
        'message' => 'Faltan campos obligatorios'
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    $mysqli->close();
    exit;
}

// Validar archivo
if (!isset($_FILES['archivo']) || $_FILES['archivo']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode([
        'status'  => 'error',
        'message' => 'Archivo no recibido o con error'
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    $mysqli->close();
    exit;
}

$archivo      = $_FILES['archivo'];
$nombreOriginal = $archivo['name'];
$extension    = strtolower(pathinfo($nombreOriginal, PATHINFO_EXTENSION));

// Opcional: restringir extensiones permitidas
$permitidas = ['pdf','zip','rar','json','xml','jar','exe','doc','docx','xls','xlsx','ppt','pptx'];
if (!in_array($extension, $permitidas)) {
    echo json_encode([
        'status'  => 'error',
        'message' => 'Tipo de archivo no permitido'
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    $mysqli->close();
    exit;
}

// Carpeta de carga
$uploadsDir = __DIR__ . '/uploads';
if (!is_dir($uploadsDir)) {
    mkdir($uploadsDir, 0777, true);
}

// Nombre único para guardar
$nombreGuardado = uniqid('recurso_') . '.' . $extension;
$rutaDestino    = $uploadsDir . '/' . $nombreGuardado;

if (!move_uploaded_file($archivo['tmp_name'], $rutaDestino)) {
    echo json_encode([
        'status'  => 'error',
        'message' => 'No se pudo guardar el archivo en el servidor'
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    $mysqli->close();
    exit;
}

// Insertar en BD
$sql = "INSERT INTO recursos 
        (nombre, autor, departamento, empresa, fecha_creacion, descripcion, archivo, extension, eliminado)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, 0)";

$stmt = $mysqli->prepare($sql);
if (!$stmt) {
    echo json_encode([
        'status'  => 'error',
        'message' => 'Error al preparar la consulta: ' . $mysqli->error
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    $mysqli->close();
    exit;
}

$stmt->bind_param(
    'ssssssss',
    $nombre,
    $autor,
    $departamento,
    $empresa,
    $fecha_creacion,
    $descripcion,
    $nombreGuardado,
    $extension
);

if ($stmt->execute()) {
    echo json_encode([
        'status'  => 'success',
        'message' => 'Recurso agregado correctamente',
        'id'      => $stmt->insert_id
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode([
        'status'  => 'error',
        'message' => 'Error al insertar el recurso: ' . $stmt->error
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
}

$stmt->close();
$mysqli->close();
