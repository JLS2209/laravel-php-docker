<?php
if (!class_exists("ControladorUbicacion")) {
    class ControladorUbicacion
    {
        // CREATE
        public function insert($obj)
        {
            // Conectar a base de datos
            $cn = (new Conectar())->getConectar();

            // Ejecutar select
            $id_distrito = $obj->distrito->id_distrito;
            $sql = "INSERT INTO tb_ubicacion (direccion, id_distrito, coord_lat, coord_lng) 
            VALUES ('$obj->direccion', $id_distrito, $obj->lat, $obj->long);";
            
            $estado = mysqli_query($cn, $sql);
            // Verificar éxito de la sentencia
            if ($estado == false) {
                $id = -1; // Hubo un error en el insert
            } else {
                // Recuperar id AUTO_INCREMENT del objeto recién insertado
                $id = mysqli_insert_id($cn);
            }

            // Cerrar conexión
            mysqli_close($cn);

            // Devolver respuesta
            return $id;
        }

        // READ
        public function show($id)
        {

        }

        public function list()
        {

        }

        // UPDATE
        public function update($obj)
        {
            // Conectar a base de datos
            $cn = (new Conectar())->getConectar();
            // Ejecutar update
            $id_distrito = $obj->distrito->id_distrito;
            $sql = "UPDATE tb_ubicacion
            SET direccion='$obj->direccion', id_distrito='$id_distrito', coord_lat='$obj->lat', coord_lng='$obj->long'
            WHERE id_ubicacion ='$obj->id_ubicacion'
            ;";

            $estado = mysqli_query($cn, $sql);

            // Cerrar conexión
            mysqli_close($cn);

            // Devolver respuesta
            return $estado;
        }

        // DELETE
        public function delete($id)
        {

        }
    }
}
?>