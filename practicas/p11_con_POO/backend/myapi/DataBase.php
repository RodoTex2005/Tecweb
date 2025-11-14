<?php
namespace TECWEB\MYAPI;

use Exception;

abstract class DataBase {
    protected $conexion;

    public function __construct($user, $pass, $db) {
        $this->conexion = mysqli_connect(
            'localhost',
            $user,
            $pass,
            $db
        );

        if (!$this->conexion) {
            throw new Exception('Error de conexión: ' . mysqli_connect_error());
        }
        
        // Establecer charset
        mysqli_set_charset($this->conexion, 'utf8');
    }
}
?>