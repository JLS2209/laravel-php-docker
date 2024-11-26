<?php
if (!class_exists("ControladorCliente")) {
    class ControladorCliente
    {
        // CREATE
        public function insert($obj, $id_ubicacion)
        {
            // Conectar a base de datos
            $cn = (new Conectar())->getConectar();

            // Los clientes visitantes (id_rol = 1), no tiene código de usuario
            if ($obj->id_rol == 1) {
                // Insertar en la tabla Cliente
                $sql = "INSERT INTO tb_cliente (nombre, apellido, email, telefono, codigo_usuario, id_ubicacion)
                VALUES ('$obj->nombre', '$obj->apellido', '$obj->email', '$obj->telefono', NULL, NULL);";
                $estado = mysqli_query($cn, $sql);

                // Verificar éxito de la sentencia
                if ($estado == false) {
                    $id = -1; // Hubo un error en el insert
                } else {
                    // Recuperar id AUTO_INCREMENT del objeto recién insertado
                    $id = mysqli_insert_id($cn);
                }
            }
            // Los clientes registrados necesitan código de usuario
            else {
                // Insertar primero en la tabla Usuario
                $sql = "INSERT INTO tb_usuario (codigo_usuario, clave, id_rol)
                VALUES ('$obj->codigo_usuario', '$obj->clave', $obj->id_rol);";
                $estado = mysqli_query($cn, $sql);

                // Verificar éxito de la sentencia
                if ($estado == false) {
                    $id = -1; // Hubo un error en el insert
                } else {
                    // Insertar en la tabla Cliente
                    $sql = "INSERT INTO tb_cliente (nombre, apellido, email, telefono, codigo_usuario, id_ubicacion)
                    VALUES ('$obj->nombre', '$obj->apellido', '$obj->email', '$obj->telefono', '$obj->codigo_usuario', 
                    " . ($id_ubicacion == NULL ? 'NULL' : "'$id_ubicacion'") . ");";
                    $estado = mysqli_query($cn, $sql);

                    // Verificar éxito de la sentencia
                    if ($estado == false) {
                        $id = -1; // Hubo un error en el insert
                    } else {
                        // Recuperar id AUTO_INCREMENT del objeto recién insertado
                        $id = mysqli_insert_id($cn);
                    }
                }
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
            $sql = "SELECT * FROM tb_cliente cl
            LEFT JOIN tb_usuario us ON cl.codigo_usuario = us.codigo_usuario
            LEFT JOIN tb_ubicacion ub ON cl.id_ubicacion = ub.id_ubicacion
            LEFT JOIN tb_distrito d ON d.id_distrito = ub.id_distrito
            WHERE cl.nro_cliente = '$id';";

            $rs = mysqli_query($cn, $sql);

            // Recoge SOLO UNA fila del resultado del query
            while ($row = mysqli_fetch_row($rs)) {
                // Crear objeto de clase Ubicacion
                $ubicacion = ($row[7] == null) ? null : new Ubicacion(
                    $row[7],
                    $row[13],
                    new Distrito($row[14], $row[18], $row[19]),
                    $row[15],
                    $row[16]
                );

                $cliente = new Cliente(
                    $row[0],
                    $row[1],
                    $row[2],
                    $row[3],
                    $row[4],
                    $row[5],
                    $row[6],
                    null, // 9
                    $row[10],
                    $row[11],
                    $ubicacion
                );
            }

            // Cerrar conexión
            mysqli_free_result($rs);
            mysqli_close($cn);

            // Devolver objeto
            return $cliente;
        }

        public function list()
        {

        }

        public function show_user($codigo_usuario)
        {
            // Conectar a base de datos
            $cn = (new Conectar())->getConectar();

            // Ejecutar select
            $sql = "SELECT * FROM tb_cliente cl
            INNER JOIN tb_usuario us ON cl.codigo_usuario = us.codigo_usuario 
            WHERE cl.codigo_usuario = '$codigo_usuario';";

            $rs = mysqli_query($cn, $sql);

            // Recoge SOLO UNA fila del resultado del query
            while ($row = mysqli_fetch_row($rs)) {
                $cliente = new Cliente(
                    $row[0],
                    $row[1],
                    $row[2],
                    $row[3],
                    $row[4],
                    $row[5],
                    $row[6],
                    null, // 9
                    $row[10],
                    $row[11],
                    null // 7
                );
            }

            // Cerrar conexión
            mysqli_free_result($rs);
            mysqli_close($cn);

            // Devolver objeto
            return $cliente;
        }

        public function list_user()
        {
            // Conectar a base de datos
            $cn = (new Conectar())->getConectar();

            // Ejecutar select
            $sql = "SELECT * FROM tb_cliente cl
            INNER JOIN tb_usuario us ON cl.codigo_usuario = us.codigo_usuario
            LEFT JOIN tb_ubicacion ub ON cl.id_ubicacion = ub.id_ubicacion
            WHERE us.id_rol = '2' ORDER BY cl.nro_cliente;
            ";

            $rs = mysqli_query($cn, $sql);

            // Arreglo que almacena objetos
            $arr = [];
            while ($row = mysqli_fetch_array($rs)) {
                // Crear objeto de clase Ubicacion
                $ubicacion = ($row[7] == null) ? null : new Ubicacion(
                    $row[7],
                    $row[13],
                    new Distrito($row[14], null, null),
                    $row[15],
                    $row[16]
                );

                $cliente = new Cliente(
                    $row[0],
                    $row[1],
                    $row[2],
                    $row[3],
                    $row[4],
                    $row[5],
                    $row[6],
                    null, // 9
                    $row[10],
                    $row[11],
                    $ubicacion
                );
                $arr[] = $cliente;
            }

            // Cerrar conexión
            mysqli_free_result($rs);
            mysqli_close($cn);

            // Devolver objeto
            return $arr;
        }

        public function list_visitor()
        {
            // Conectar a base de datos
            $cn = (new Conectar())->getConectar();

            // Ejecutar select
            $sql = "SELECT * FROM tb_cliente 
            WHERE codigo_usuario IS NULL ORDER BY nro_cliente;
            ";

            $rs = mysqli_query($cn, $sql);

            // Arreglo que almacena objetos
            $arr = [];
            while ($row = mysqli_fetch_array($rs)) {
                $cliente = new Cliente(
                    $row[0],
                    $row[1],
                    $row[2],
                    $row[3],
                    $row[4],
                    null,
                    null,
                    null,
                    null,
                    1,
                    null
                );
                $arr[] = $cliente;
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

        }

        public function update_perfil($obj)
        {
            // Conectar a base de datos
            $cn = (new Conectar())->getConectar();
            // Ejecutar update
            $sql = "UPDATE tb_cliente
            SET nombre='$obj->nombre', apellido='$obj->apellido', email='$obj->email', telefono='$obj->telefono'
            WHERE nro_cliente ='$obj->nro_cliente'
            ;";

            $estado = mysqli_query($cn, $sql);

            // Cerrar conexión
            mysqli_close($cn);

            // Devolver respuesta
            return $estado;
        }

        public function update_passw($nro, $clave)
        {
            // Conectar a base de datos
            $cn = (new Conectar())->getConectar();
            // Ejecutar update
            $sql = "UPDATE tb_cliente cl
            INNER JOIN tb_usuario us ON cl.codigo_usuario = us.codigo_usuario 
            SET us.clave='$clave'
            WHERE cl.nro_cliente ='$nro'
            ;";

            $estado = mysqli_query($cn, $sql);

            // Cerrar conexión
            mysqli_close($cn);

            // Devolver respuesta
            return $estado;
        }

        public function update_ubicacion($nro, $id_ubicacion)
        {
            // Conectar a base de datos
            $cn = (new Conectar())->getConectar();
            // Ejecutar update
            $sql = "UPDATE tb_cliente
            SET id_ubicacion ='$id_ubicacion'
            WHERE nro_cliente ='$nro'
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
            $sql = "DELETE cl, us FROM tb_cliente cl
                    INNER JOIN tb_usuario us ON cl.codigo_usuario = us.codigo_usuario 
                    WHERE cl.nro_cliente ='$id';";

            $estado = mysqli_query($cn, $sql);

            // Cerrar conexión
            mysqli_close($cn);

            // Devolver respuesta
            return $estado;
        }

        public function delete_user($id)
        {
            // Conectar a base de datos
            $cn = (new Conectar())->getConectar();
            // Ejecutar select
            $sql = "DELETE cl, us FROM tb_cliente cl
            INNER JOIN tb_usuario us ON cl.codigo_usuario = us.codigo_usuario 
            WHERE cl.nro_cliente ='$id';";

            $estado = mysqli_query($cn, $sql);

            // Cerrar conexión
            mysqli_close($cn);

            // Devolver respuesta
            return $estado;
        }

        public function delete_visitor($id)
        {
            // Conectar a base de datos
            $cn = (new Conectar())->getConectar();
            // Ejecutar select
            $sql = "DELETE FROM tb_cliente WHERE cl.nro_cliente ='$id';";

            $estado = mysqli_query($cn, $sql);

            // Cerrar conexión
            mysqli_close($cn);

            // Devolver respuesta
            return $estado;
        }
    }
}
?>