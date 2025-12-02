<?php
namespace ProductApp\Read;

use ProductApp\DataBase;

class ProductsSearch extends DataBase {
    private $data;

    public function __construct($db, $user = 'root', $pass = 'Axelchivas1607') {
        $this->data = array();
        parent::__construct($db, $user, $pass);
    }

    public function search($search) {
        $this->data = array();
        
        if(isset($search)) {
            // CORREGIDO: usar descripcion en lugar de detalles
            $sql = "SELECT * FROM productos WHERE (id = '{$search}' OR nombre LIKE '%{$search}%' OR marca LIKE '%{$search}%' OR descripcion LIKE '%{$search}%') AND eliminado = 0";
            if ($result = $this->conexion->query($sql)) {
                $rows = $result->fetch_all(MYSQLI_ASSOC);

                if(!is_null($rows)) {
                    foreach($rows as $num => $row) {
                        foreach($row as $key => $value) {
                            $this->data[$num][$key] = $value;
                        }
                    }
                }
                $result->free();
            } else {
                $this->data = array('error' => 'Query Error: '.mysqli_error($this->conexion));
            }
            $this->conexion->close();
        }
    }

    public function getResponse() {
        return $this->data;
    }
}
?>