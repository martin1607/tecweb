<?php
namespace ProductApp\Delete;

use ProductApp\DataBase;

class ProductsDelete extends DataBase {
    private $data;

    public function __construct($db, $user = 'root', $pass = 'Axelchivas1607') {
        $this->data = array();
        parent::__construct($db, $user, $pass);
    }

    public function delete($id) {
        $this->data = array(
            'status'  => 'error',
            'message' => 'La consulta falló'
        );
        
        if(isset($id)) {
            $sql = "UPDATE productos SET eliminado=1 WHERE id = {$id}";
            if ($this->conexion->query($sql)) {
                $this->data['status'] =  "success";
                $this->data['message'] =  "Producto eliminado";
            } else {
                $this->data['message'] = "ERROR: No se ejecuto $sql. " . mysqli_error($this->conexion);
            }
            $this->conexion->close();
        }
    }

    public function getResponse() {
        return $this->data;
    }
}
?>