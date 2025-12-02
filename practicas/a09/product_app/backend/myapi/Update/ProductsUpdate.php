<?php
namespace ProductApp\Update;

use ProductApp\DataBase;

class ProductsUpdate extends DataBase {
    private $data;

    public function __construct($db, $user = 'root', $pass = 'Axelchivas1607') {
        $this->data = array();
        parent::__construct($db, $user, $pass);
    }

    public function edit($jsonOBJ) {
        $this->data = array(
            'status'  => 'error',
            'message' => 'La consulta falló'
        );
        
        if(isset($jsonOBJ->id)) {
            // CORREGIDO: usar descripcion en lugar de detalles
            $sql =  "UPDATE productos SET nombre='{$jsonOBJ->nombre}', marca='{$jsonOBJ->marca}',";
            $sql .= "modelo='{$jsonOBJ->modelo}', descripcion='{$jsonOBJ->descripcion}', precio={$jsonOBJ->precio},"; 
            $sql .= "unidades={$jsonOBJ->unidades}, imagen='{$jsonOBJ->imagen}' WHERE id={$jsonOBJ->id}";
            $this->conexion->set_charset("utf8");
            if ($this->conexion->query($sql)) {
                $this->data['status'] =  "success";
                $this->data['message'] =  "Producto actualizado";
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