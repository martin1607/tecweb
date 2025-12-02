<?php
// product-edit.php
header('Content-Type: application/json; charset=utf-8');

// Solo POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'status'  => 'error',
        'message' => 'Método no permitido'
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
if ($id <= 0) {
    echo json_encode([
        'status'  => 'error',
        'message' => 'ID inválido'
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

$nombre        = trim($_POST['nombre']        ?? '');
$autor         = trim($_POST['autor']         ?? '');
$departamento  = trim($_POST['departamento']  ?? '');
$empresa       = trim($_POST['empresa']       ?? '');
$fecha_creacion= trim($_POST['fecha_creacion']?? '');
$descripcion   = trim($_POST['descripcion']   ?? '');

if ($nombre === '' || $autor === '' || $departamento === '' || $empresa === '' || $fecha_creacion === '') {
    echo json_encode([
        'status'  => 'error',
        'message' => 'Faltan campos obligatorios'
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

$host = 'localhost';
$user = 'root';
$pass = 'Axelchivas1607';
$db   = 'marketzone_fix';

$mysqli = new mysqli($host, $user, $pass, $db);
if ($mysqli->connect_errno) {
    echo json_encode([
        'status'  => 'error',
        'message' => 'Error de conexión: ' . $mysqli->connect_error
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

// ¿Hay nuevo archivo?
$tieneArchivoNuevo = isset($_FILES['archivo']) && $_FILES['archivo']['error'] === UPLOAD_ERR_OK;

if ($tieneArchivoNuevo) {
    $archivo      = $_FILES['archivo'];
    $nombreOriginal = $archivo['name'];
    $extension    = strtolower(pathinfo($nombreOriginal, PATHINFO_EXTENSION));

    $permitidas = ['pdf','zip','rar','json','xml','jar','exe','doc','docx','xls','xlsx','ppt','pptx'];
    if (!in_array($extension, $permitidas)) {
        echo json_encode([
            'status'  => 'error',
            'message' => 'Tipo de archivo no permitido'
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        $mysqli->close();
        exit;
    }

    $uploadsDir = __DIR__ . '/uploads';
    if (!is_dir($uploadsDir)) {
        mkdir($uploadsDir, 0777, true);
    }

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

    // Actualizar también archivo y extensión
    $sql = "UPDATE recursos
            SET nombre = ?, autor = ?, departamento = ?, empresa = ?, 
                fecha_creacion = ?, descripcion = ?, archivo = ?, extension = ?
            WHERE id = ?";
    $stmt = $mysqli->prepare($sql);
    if (!$stmt) {
        echo json_encode([
            'status'  => 'error',
            'message' => 'Error al preparar consulta: ' . $mysqli->error
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        $mysqli->close();
        exit;
    }

    $stmt->bind_param(
        'ssssssssi',
        $nombre,
        $autor,
        $departamento,
        $empresa,
        $fecha_creacion,
        $descripcion,
        $nombreGuardado,
        $extension,
        $id
    );
} else {
    // Sin cambio de archivo
    $sql = "UPDATE recursos
            SET nombre = ?, autor = ?, departamento = ?, empresa = ?, 
                fecha_creacion = ?, descripcion = ?
            WHERE id = ?";
    $stmt = $mysqli->prepare($sql);
    if (!$stmt) {
        echo json_encode([
            'status'  => 'error',
            'message' => 'Error al preparar consulta: ' . $mysqli->error
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        $mysqli->close();
        exit;
    }

    $stmt->bind_param(
        'ssssssi',
        $nombre,
        $autor,
        $departamento,
        $empresa,
        $fecha_creacion,
        $descripcion,
        $id
    );
}

if ($stmt->execute() && $stmt->affected_rows >= 0) {
    echo json_encode([
        'status'  => 'success',
        'message' => 'Recurso actualizado correctamente'
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode([
        'status'  => 'error',
        'message' => 'No se pudo actualizar el recurso: ' . $stmt->error
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
}

$stmt->close();
$mysqli->close();
