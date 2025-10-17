<?php
    $conexion = @mysqli_connect(
        'localhost',
        'root',
        'Axelchivas1607',  // tu contraseña real
        'marketzone_fix'   // tu base de datos real
    );

    if(!$conexion) {
        die('¡Error: Base de datos NO conectada!');
    }
?>
