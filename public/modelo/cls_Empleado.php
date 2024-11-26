<?php
if (!class_exists("Empleado")) {
    class Empleado
    {
        public $nro_empleado;
        public $nombre;
        public $apellido;
        public $email;
        public $codigo_usuario;
        public $clave;
        public $fecha_registro;
        public $id_rol;

        public function __construct($nro_empleado, $nombre, $apellido, $email, $codigo_usuario, $clave, $fecha_registro, $id_rol) {
            $this->nro_empleado = $nro_empleado;
            $this->nombre = $nombre;
            $this->apellido = $apellido;
            $this->email = $email;
            $this->codigo_usuario = $codigo_usuario;
            $this->clave = $clave;
            $this->fecha_registro = $fecha_registro;
            $this->id_rol = $id_rol;
        }

        public function nombre_rol() {
            return $this->id_rol == '4' ? 'Administrador' : 'Atención al cliente';
        }
        
        public function table_row() {
            return "
            <tr>
                <td class='ps-3'>$this->nro_empleado</td>
                <td >$this->nombre $this->apellido</td>
                <td>$this->email</td>
                <td>".$this->nombre_rol()."</td>
                <td>$this->fecha_registro</td>
                <td>
                    <button type='button' class='btn btn-info btn-editar' data-bs-toggle='modal'
                        data-bs-target='#modal-objetivo'>
                        Cambiar Rol
                    </button>
                </td>
                <td>
                    <button type='button' class='btn btn-danger btn-eliminar'>
                        Eliminar
                    </button>
                </td>
            </tr>
            ";
        }
    }
}
?>