<?php
if (!class_exists("ControladorMensaje")) {
    class ControladorMensaje
    {
        // CREATE
        public function insert($obj, $nro_cliente)
        {
            // Conectar a base de datos
            $cn = (new Conectar())->getConectar();

            // Insertar en la tabla Mensaje
            $sql = "INSERT INTO tb_mensaje (nro_cliente, asunto, contenido)
            VALUES ('$nro_cliente', '$obj->asunto', '$obj->contenido');";
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
            // Conectar a base de datos
            $cn = (new Conectar())->getConectar();

            // Ejecutar select
            $sql = "SELECT * FROM tb_mensaje me
            LEFT JOIN tb_cliente cl ON me.nro_cliente = cl.nro_cliente
            LEFT JOIN tb_usuario us ON cl.codigo_usuario = us.codigo_usuario 
            WHERE me.id_mensaje = '$id';";

            $rs = mysqli_query($cn, $sql);

            // Recoge SOLO UNA fila del resultado del query
            $mensaje = null;
            while ($row = mysqli_fetch_row($rs)) {
                $mensaje = new Mensaje(
                    $row[0],
                    new Cliente(
                        $row[5],
                        $row[6],
                        $row[7],
                        $row[8],
                        $row[9],
                        null,
                        $row[11],
                        null,
                        null,
                        $row[16],
                        null
                    ),
                    $row[2],
                    $row[3],
                    $row[4]
                );
            }

            // Cerrar conexión
            mysqli_free_result($rs);
            mysqli_close($cn);

            // Devolver objeto
            return $mensaje;
        }

        public function list()
        {
            // Conectar a base de datos
            $cn = (new Conectar())->getConectar();

            // Ejecutar select
            $sql = "SELECT * FROM tb_mensaje me
            INNER JOIN tb_cliente cl ON me.nro_cliente = cl.nro_cliente
            LEFT JOIN tb_usuario us ON cl.codigo_usuario = us.codigo_usuario 
            ;";

            $rs = mysqli_query($cn, $sql);

            // Arreglo que almacena objetos
            $arr = [];
            while ($row = mysqli_fetch_array($rs)) {
                $mensaje = new Mensaje(
                    $row[0],
                    new Cliente(
                        $row[5],
                        $row[6],
                        $row[7],
                        $row[8],
                        $row[9],
                        null,
                        $row[11],
                        null,
                        null,
                        $row[16],
                        null
                    ),
                    $row[2],
                    $row[3],
                    $row[4]
                );
                $arr[] = $mensaje;
            }


            // Cerrar conexión
            mysqli_free_result($rs);
            mysqli_close($cn);

            // Devolver objeto
            return $arr;
        }

        // UPDATE
        public function update($obj)
        {
            // Conectar a base de datos
            $cn = (new Conectar())->getConectar();
            // Ejecutar select
            $sql = "UPDATE tb_mensaje 
            SET asunto='$obj->asunto', contenido='$obj->contenido'
            WHERE id_mensaje='$obj->id_mensaje'
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
            // Conectar a base de datos
            $cn = (new Conectar())->getConectar();
            // Ejecutar select
            $sql = "DELETE FROM tb_mensaje
            WHERE id_mensaje='$id';";

            $estado = mysqli_query($cn, $sql);

            // Cerrar conexión
            mysqli_close($cn);

            // Devolver respuesta
            return $estado;
        }
    }
}
?>