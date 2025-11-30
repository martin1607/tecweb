<?php
namespace ProductApp\Read;

use ProductApp\DataBase;

class ProductsSingle extends DataBase {
    private $data;

    public function __construct($db, $user = 'root', $pass = 'Axelchivas1607') {
        $this->data = array();
        parent::__construct($db, $user, $pass);
    }

    public function single($id) {
        $this->data = array(); // Reiniciar datos
        
        if(isset($id) && is_numeric($id)) {
            $sql = "SELECT * FROM productos WHERE id = " . intval($id);
            $result = $this->conexion->query($sql);
            
            if ($result && $result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $this->data = $row; // Asignar directamente el array
                $result->free();
            } else {
                $this->data = ['error' => 'Producto no encontrado'];
            }
            $this->conexion->close();
        } else {
            $this->data = ['error' => 'ID no válido'];
        }
    }

    public function getResponse() {
        return $this->data;
    }
}
?>