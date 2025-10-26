<?php
$conexion = @mysqli_connect(
    'localhost',
    'root',
    'Axelchivas1607',
    'marketzone_fix'
);

if(!$conexion) {
    die('¡Base de datos NO conectada!');
}
?>