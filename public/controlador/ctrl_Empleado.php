<?php
if (!class_exists("ControladorEmpleado")) {
    class ControladorEmpleado
    {
        // CREATE
        public function insert($obj)
        {
            // Conectar a base de datos
            $cn = (new Conectar())->getConectar();

            // Insertar primero en la tabla Usuario
            $sql = "INSERT INTO tb_usuario (codigo_usuario, clave, id_rol)
            VALUES ('$obj->codigo_usuario', '$obj->clave', $obj->id_rol);";
            $estado = mysqli_query($cn, $sql);

            // Verificar éxito de la sentencia
            if ($estado == false) {
                $id = -1; // Hubo un error en el insert
            } else {
                // Insertar en la tabla Empleado
                $sql = "INSERT INTO tb_empleado (nombre, apellido, email, codigo_usuario)
                    VALUES ('$obj->nombre', '$obj->apellido', '$obj->email', '$obj->codigo_usuario');";
                $estado = mysqli_query($cn, $sql);

                // Verificar éxito de la sentencia
                if ($estado == false) {
                    $id = -1; // Hubo un error en el insert
                } else {
                    // Recuperar id AUTO_INCREMENT del objeto recién insertado
                    $id = mysqli_insert_id($cn);
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
            $sql = "SELECT * FROM tb_empleado em
            INNER JOIN tb_usuario us ON em.codigo_usuario = us.codigo_usuario 
            WHERE em.nro_empleado = '$id';";

            $rs = mysqli_query($cn, $sql);

            // Recoge SOLO UNA fila del resultado del query
            while ($row = mysqli_fetch_row($rs)) {
                $empleado = new Empleado(
                    $row[0],
                    $row[1],
                    $row[2],
                    $row[3],
                    $row[4],
                    null, //6
                    $row[7],
                    $row[8]
                );
            }

            // Cerrar conexión
            mysqli_free_result($rs);
            mysqli_close($cn);

            // Devolver objeto
            return $empleado;
        }

        public function list()
        {
            // Conectar a base de datos
            $cn = (new Conectar())->getConectar();

            // Ejecutar select
            $sql = "SELECT * FROM tb_empleado em
            INNER JOIN tb_usuario us ON em.codigo_usuario = us.codigo_usuario ;
            ";

            $rs = mysqli_query($cn, $sql);

            // Arreglo que almacena objetos
            $arr = [];
            while ($row = mysqli_fetch_array($rs)) {
                $empleado = new Empleado(
                    $row[0],
                    $row[1],
                    $row[2],
                    $row[3],
                    $row[4],
                    null,
                    $row[7],
                    $row[8]
                );
                $arr[] = $empleado;
            }


            // Cerrar conexión
            mysqli_free_result($rs);
            mysqli_close($cn);

            // Devolver objeto
            return $arr;
        }

        public function show_user($codigo_usuario)
        {
            // Conectar a base de datos
            $cn = (new Conectar())->getConectar();

            // Ejecutar select
            $sql = "SELECT * FROM tb_empleado em
            INNER JOIN tb_usuario us ON em.codigo_usuario = us.codigo_usuario 
            WHERE em.codigo_usuario = '$codigo_usuario';";

            $rs = mysqli_query($cn, $sql);

            // Recoge SOLO UNA fila del resultado del query
            while ($row = mysqli_fetch_row($rs)) {
                $empleado = new Empleado(
                    $row[0],
                    $row[1],
                    $row[2],
                    $row[3],
                    $row[4],
                    null, // 6
                    $row[7],
                    $row[8]
                );
            }

            // Cerrar conexión
            mysqli_free_result($rs);
            mysqli_close($cn);

            // Devolver objeto
            return $empleado;
        }

        // UPDATE
        public function update($obj)
        {

        }

        public function update_rol($nro, $id_rol)
        {
            // Conectar a base de datos
            $cn = (new Conectar())->getConectar();
            // Ejecutar select
            $sql = "UPDATE tb_empleado em
            INNER JOIN tb_usuario us ON em.codigo_usuario = us.codigo_usuario 
            SET us.id_rol='$id_rol'
            WHERE em.nro_empleado ='$nro'
            ;";

            $estado = mysqli_query($cn, $sql);

            // Cerrar conexión
            mysqli_close($cn);

            // Devolver respuesta
            return $estado;
        }

        public function update_perfil($obj)
        {
            // Conectar a base de datos
            $cn = (new Conectar())->getConectar();
            // Ejecutar select
            $sql = "UPDATE tb_empleado
            SET nombre='$obj->nombre', apellido='$obj->apellido', email='$obj->email'
            WHERE nro_empleado ='$obj->nro_empleado'
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
            // Ejecutar select
            $sql = "UPDATE tb_empleado em
            INNER JOIN tb_usuario us ON em.codigo_usuario = us.codigo_usuario 
            SET us.clave='$clave'
            WHERE em.nro_empleado ='$nro'
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
            $sql = "DELETE em, us FROM tb_empleado em
            INNER JOIN tb_usuario us ON em.codigo_usuario = us.codigo_usuario 
            WHERE em.nro_empleado ='$id';";

            $estado = mysqli_query($cn, $sql);

            // Cerrar conexión
            mysqli_close($cn);

            // Devolver respuesta
            return $estado;
        }
    }
}
?>