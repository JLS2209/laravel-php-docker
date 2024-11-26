<?php
if (!class_exists("Usuario")) {
    class Usuario
    {
        public $codigo_usuario;
        public $clave;
        public $fecha_registro;
        public $id_rol;

        public function __construct($codigo_usuario, $clave, $fecha_registro, $id_rol) {
            $this->codigo_usuario = $codigo_usuario;
            $this->clave = $clave;
            $this->fecha_registro = $fecha_registro;
            $this->id_rol = $id_rol;
        }        
    }
}
?>