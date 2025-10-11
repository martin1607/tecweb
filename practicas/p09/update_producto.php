<?php
/* MySQL Conexion*/
$link = mysqli_connect("localhost", "root", "Axelchivas1607", "marketzone_fix");

// Chequea coneccion
if($link === false){
    die("ERROR: No pudo conectarse con la DB. ". mysqli_connect_error()); 
}

// Recibir datos del formulario
$id = $_POST['id'] ?? '';
$nombre = $_POST['nombre'] ?? '';
$marca = $_POST['marca'] ?? '';
$modelo = $_POST['modelo'] ?? '';
$descripcion = $_POST['descripcion'] ?? '';
$precio = $_POST['precio'] ?? '';
$unidades = $_POST['unidades'] ?? '';
$imagen = $_POST['imagen'] ?? '';

// Validar que el ID existe
if (empty($id)) {
    die("ERROR: ID del producto no especificado.");
}

// Se actualiza el producto en la BD
$sql = "UPDATE productos SET 
        nombre = '$nombre',
        marca = '$marca', 
        modelo = '$modelo',
        descripcion = '$descripcion',
        precio = $precio,
        unidades = $unidades,
        imagen = '$imagen'
        WHERE id = $id";

if(mysqli_query($link, $sql)){
    echo "✅ Registro actualizado correctamente.";
} else {
    echo "ERROR: No se ejecuto $sql. ". mysqli_error($link); 
}

// Cierra la conexion
mysqli_close($link);

// HIPERVÍNCULOS REQUERIDOS
echo "<br><br>";
echo "<a href='get_productos_xhtml_v2.php'>📋 Ver todos los productos</a> | ";
echo "<a href='get_productos_vigentes_v2.php'>📦 Ver productos vigentes</a>";
?>