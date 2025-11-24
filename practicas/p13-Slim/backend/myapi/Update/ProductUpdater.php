<?php
namespace TECWEB\MYAPI\Update;

use Exception;
use TECWEB\MYAPI\DataBase;
require_once __DIR__ . '/../DataBase.php';

class ProductUpdater extends DataBase {
    private $data;

    public function __construct($db, $user = 'root', $pass = 'Rudytexcuc@no') {
        $this->data = array();
        parent::__construct($db, $user, $pass);
    }

    public function edit($jsonOBJ) {
        error_log("=== DEBUG ProductUpdater ===");
        error_log("Datos recibidos: " . print_r($jsonOBJ, true));
        
        $this->data = array(
            'status'  => 'error',
            'message' => 'La consulta falló'
        );
        
        if(isset($jsonOBJ->id)) {
            try {
                // Verificar que todos los campos necesarios existan
                $required_fields = ['nombre', 'marca', 'modelo', 'precio', 'descripcion', 'unidades'];
                $missing_fields = [];
                
                foreach ($required_fields as $field) {
                    if (!isset($jsonOBJ->$field)) {
                        $missing_fields[] = $field;
                    }
                }
                
                if (!empty($missing_fields)) {
                    $this->data['message'] = "Campos faltantes: " . implode(', ', $missing_fields);
                    error_log("Campos faltantes: " . implode(', ', $missing_fields));
                    return json_encode($this->data, JSON_PRETTY_PRINT);
                }
                
                // Preparar datos para la consulta
                $id = intval($jsonOBJ->id);
                $nombre = $this->conexion->real_escape_string($jsonOBJ->nombre);
                $marca = $this->conexion->real_escape_string($jsonOBJ->marca);
                $modelo = $this->conexion->real_escape_string($jsonOBJ->modelo);
                $precio = floatval($jsonOBJ->precio);
                $descripcion = $this->conexion->real_escape_string($jsonOBJ->descripcion);
                $unidades = intval($jsonOBJ->unidades);
                $imagen = isset($jsonOBJ->imagen) ? $this->conexion->real_escape_string($jsonOBJ->imagen) : 'img/default.png';
                
                // Crear la consulta SQL (CORREGIDA: usando 'descripcion' como en tu BD)
                $sql = "UPDATE productos SET 
                        nombre = '$nombre',
                        marca = '$marca',
                        modelo = '$modelo', 
                        precio = $precio, 
                        descripcion = '$descripcion',
                        unidades = $unidades, 
                        imagen = '$imagen' 
                        WHERE id = $id";
                
                error_log("SQL ejecutado: " . $sql);
                
                $this->conexion->set_charset("utf8");
                if ($this->conexion->query($sql)) {
                    $this->data['status'] = "success";
                    $this->data['message'] = "Producto actualizado correctamente";
                    error_log("✅ Consulta exitosa");
                } else {
                    $error = mysqli_error($this->conexion);
                    $this->data['message'] = "Error en la consulta: " . $error;
                    error_log("❌ Error SQL: " . $error);
                }
                
                $this->conexion->close();
                
            } catch (Exception $e) {
                $this->data['message'] = "Excepción: " . $e->getMessage();
                error_log("❌ Excepción: " . $e->getMessage());
            }
        } else {
            $this->data['message'] = "ID no proporcionado";
            error_log("❌ ID no proporcionado");
        }
        
        error_log("Resultado final: " . json_encode($this->data));
        return json_encode($this->data, JSON_PRETTY_PRINT);
    }
}
?>