<?php
if (!class_exists("Cliente")) {
    class Cliente
    {
        public $nro_cliente;
        public $nombre;
        public $apellido;
        public $email;
        public $telefono;
        public $fidelidad;
        public $codigo_usuario;
        public $clave;
        public $fecha_registro;
        public $id_rol;
        public $ubicacion;

        public function __construct($nro_cliente, $nombre, $apellido, $email, $telefono, $fidelidad, $codigo_usuario, $clave, $fecha_registro, $id_rol, $ubicacion)
        {
            $this->nro_cliente = $nro_cliente;
            $this->nombre = $nombre;
            $this->apellido = $apellido;
            $this->email = $email;
            $this->telefono = $telefono;
            $this->fidelidad = $fidelidad;
            $this->codigo_usuario = $codigo_usuario;
            $this->clave = $clave;
            $this->fecha_registro = $fecha_registro;
            $this->id_rol = $id_rol;
            $this->ubicacion = $ubicacion;
        }

        public function is_regular()
        {
            return $this->fidelidad < 5;
        }

        public function table_row()
        {
            $distrito = ($this->ubicacion == null) ? "-" : $this->ubicacion->distrito->nombre_distrito;

            return "
                <tr>
                    <td class='ps-3'>$this->nro_cliente</td>
                    <td >$this->nombre $this->apellido</td>
                    <td>$this->email</td>
                    <td>$this->telefono</td>
                    <td>$distrito</td>
                    <td>
                        <button type='button' class='btn btn-info btn-ver' data-bs-toggle='modal'
                            data-bs-target='#modal-objetivo'>
                            Ver
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