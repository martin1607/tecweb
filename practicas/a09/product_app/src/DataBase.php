<?php
namespace ProductApp;

abstract class DataBase {
    protected $conexion;

    public function __construct($db, $user = 'root', $pass = 'Axelchivas1607') {
        $this->conexion = @mysqli_connect(
            'localhost',
            $user,
            $pass,
            $db
        );
    
        if(!$this->conexion) {
            // SOLUCIÓN MÁS SIMPLE: Usar die() en lugar de Exception
            die('¡Base de datos NO conectada! Error: ' . mysqli_connect_error());
        }
    }
}
?>