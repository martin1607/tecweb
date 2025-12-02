<?php
namespace ProductApp\Read;

use ProductApp\DataBase;

class ProductsList extends DataBase {
    private $data;

    public function __construct($db, $user = 'root', $pass = 'Axelchivas1607') {
        $this->data = array();
        parent::__construct($db, $user, $pass);
    }

    public function list() {
        // SIEMPRE inicializar como array
        $this->data = array();
        
        $sql = "SELECT * FROM productos WHERE eliminado = 0";
        $result = $this->conexion->query($sql);
        
        if ($result && $result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $this->data[] = $row;
            }
            $result->free();
        }
        // Si no hay resultados, $this->data queda como array vacío
        
        $this->conexion->close();
    }

    public function getResponse() {
        return $this->data;
    }
}
?>