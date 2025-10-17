<?php
header('Content-Type: application/json');
include("database.php");

// Recibir el JSON del cliente
$data = json_decode(file_get_contents("php://input"), true);

// Validar campos mínimos
if (!$data || empty($data['nombre']) || empty($data['marca']) || empty($data['modelo'])) {
    echo json_encode(["success" => false, "message" => "Datos incompletos o inválidos"]);
    exit;
}

$nombre = mysqli_real_escape_string($conexion, $data['nombre']);
$marca = mysqli_real_escape_string($conexion, $data['marca']);
$modelo = mysqli_real_escape_string($conexion, $data['modelo']);
$descripcion = mysqli_real_escape_string($conexion, $data['descripcion'] ?? '');

// Validar duplicado por (nombre + marca) o (marca + modelo)
$sqlCheck = "SELECT * FROM productos 
             WHERE eliminado = 0 AND 
             ((nombre = '$nombre' AND marca = '$marca') 
              OR (marca = '$marca' AND modelo = '$modelo'))";

$result = mysqli_query($conexion, $sqlCheck);

if ($result && mysqli_num_rows($result) > 0) {
    echo json_encode(["success" => false, "message" => "El producto ya existe en la base de datos"]);
    exit;
}

// Insertar nuevo producto
$sqlInsert = "INSERT INTO productos (nombre, marca, modelo, descripcion, eliminado)
              VALUES ('$nombre', '$marca', '$modelo', '$descripcion', 0)";

if (mysqli_query($conexion, $sqlInsert)) {
    echo json_encode(["success" => true, "message" => "Producto agregado correctamente"]);
} else {
    echo json_encode(["success" => false, "message" => "Error al insertar: " . mysqli_error($conexion)]);
}

mysqli_close($conexion);
?>
