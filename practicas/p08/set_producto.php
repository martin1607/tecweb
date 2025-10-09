<?php
$nombre = 'nombre_producto';
$descripcion = 'descripcion_producto';
$precio = 1.0;
$unidades = 1;
$imagen   = 'img/imagen.png';

/** SE CREA EL OBJETO DE CONEXIÓN */
@$link = new mysqli('localhost', 'root', 'Axelchivas1607', 'marketzone_fix');	

/** comprobar la conexión */
if ($link->connect_errno) 
{
    die('Falló la conexión: '.$link->connect_error.'<br/>');
}

/** SQL CORREGIDO con la estructura correcta */
$sql = "INSERT INTO productos VALUES (null, '{$nombre}', '{$descripcion}', {$precio}, {$unidades}, '{$imagen}')";

if ( $link->query($sql) ) 
{
    echo 'Producto insertado con ID: '.$link->insert_id;
}
else
{
    echo 'Error al insertar: '.$link->error;
}

$link->close();
?>