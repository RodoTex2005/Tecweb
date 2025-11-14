<?php
namespace TECWEB\MYAPI;

use Exception;

require_once __DIR__ . '/DataBase.php';

class Products extends DataBase {
    private $data = [];

    public function __construct($db, $user = 'root', $pass = 'Rudytexcuc@no') 
    {
    $this->data = [];
    parent::__construct($user, $pass, $db);
    
    // Verificar que la conexión se estableció
    if (!$this->conexion) {
        throw new Exception('No se pudo conectar a la base de datos');
    }
    }
        public function list() {
        $this->data = [];
        if ($result = $this->conexion->query("SELECT * FROM productos WHERE eliminado = 0")) {
            while ($row = $result->fetch_assoc()) {
                $this->data[] = $row;
            }
            $result->free();
        } else {
            throw new Exception('Query Error: ' . mysqli_error($this->conexion));
        }
    }

    public function getById($id) {
        $this->data = [];
        $stmt = $this->conexion->prepare("SELECT * FROM productos WHERE id = ? AND eliminado = 0");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $this->data = $row;
        }
        $stmt->close();
    }

    public function singleByName($name) {
        $this->data = [];
        $stmt = $this->conexion->prepare("SELECT * FROM productos WHERE nombre = ? AND eliminado = 0");
        $stmt->bind_param('s', $name);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $this->data = $row;
        }
        $stmt->close();
    }

    public function search($search) {
        $this->data = [];
        
        // Buscar en todos los campos incluyendo ID
        $sql = "SELECT * FROM productos WHERE 
                (id = ? OR 
                 nombre LIKE ? OR 
                 marca LIKE ? OR 
                 descripcion LIKE ? OR 
                 modelo LIKE ?) 
                AND eliminado = 0";
        
        $stmt = $this->conexion->prepare($sql);
        
        // Si es numérico, buscar por ID exacto, sino como texto
        $id_search = is_numeric($search) ? $search : 0;
        $like_term = "%$search%";
        
        $stmt->bind_param('issss', $id_search, $like_term, $like_term, $like_term, $like_term);
        $stmt->execute();
        $result = $stmt->get_result();
        
        while ($row = $result->fetch_assoc()) {
            $this->data[] = $row;
        }
        $stmt->close();
    }

    public function add($nombre, $marca, $modelo, $precio, $descripcion, $unidades, $imagen) {
        $stmt = $this->conexion->prepare("INSERT INTO productos (nombre, marca, modelo, precio, descripcion, unidades, imagen) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('sssdsss', $nombre, $marca, $modelo, $precio, $descripcion, $unidades, $imagen);
        $stmt->execute();
        $this->data = ['success' => $stmt->affected_rows > 0];
        $stmt->close();
    }

    public function edit($id, $nombre, $marca, $modelo, $precio, $descripcion, $unidades, $imagen) {
        $stmt = $this->conexion->prepare("UPDATE productos SET nombre=?, marca=?, modelo=?, precio=?, descripcion=?, unidades=?, imagen=? WHERE id=?");
        $stmt->bind_param('sssdsssi', $nombre, $marca, $modelo, $precio, $descripcion, $unidades, $imagen, $id);
        $stmt->execute();
        $this->data = ['success' => $stmt->affected_rows > 0];
        $stmt->close();
    }

    public function delete($id) {
        $stmt = $this->conexion->prepare("UPDATE productos SET eliminado=1 WHERE id=?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $this->data = ['success' => $stmt->affected_rows > 0];
        $stmt->close();
    }

    public function getData() {
        return json_encode($this->data, JSON_PRETTY_PRINT);
    }
}
?>