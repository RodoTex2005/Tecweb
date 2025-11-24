<?php
namespace TECWEB\MYAPI\Create;

use TECWEB\MYAPI\DataBase;
require_once __DIR__ . '/../DataBase.php';

class ProductCreator extends DataBase {
    private $data;

    public function __construct($db, $user = 'root', $pass = 'Rudytexcuc@no') {
        $this->data = array();
        parent::__construct($db, $user, $pass);
    }

    public function add($jsonOBJ) {
        $this->data = array(
            'status'  => 'error',
            'message' => 'Ya existe un producto con ese nombre'
        );
        
        if(isset($jsonOBJ->nombre)) {
            $sql = "SELECT * FROM productos WHERE nombre = '{$jsonOBJ->nombre}' AND eliminado = 0";
            $result = $this->conexion->query($sql);
            
            if ($result->num_rows == 0) {
                $this->conexion->set_charset("utf8");
                // CAMBIO IMPORTANTE: usar 'detalles' en lugar de 'descripcion'
                $sql = "INSERT INTO productos VALUES (null, '{$jsonOBJ->nombre}', '{$jsonOBJ->marca}', '{$jsonOBJ->modelo}', {$jsonOBJ->precio}, '{$jsonOBJ->descripcion}', {$jsonOBJ->unidades}, '{$jsonOBJ->imagen}', 0)";
                
                if($this->conexion->query($sql)){
                    $this->data['status'] = "success";
                    $this->data['message'] = "Producto agregado";
                } else {
                    $this->data['message'] = "ERROR: No se ejecuto $sql. " . mysqli_error($this->conexion);
                }
            }
            $result->free();
            $this->conexion->close();
        }
        return json_encode($this->data, JSON_PRETTY_PRINT);
    }
}
?>