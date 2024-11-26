<?php
if (!class_exists("ControladorPlato")) {
    class ControladorPlato
    {
        // CREATE
        public function insert($obj)
        {
            // Conectar a base de datos
            $cn = (new Conectar())->getConectar();
            // Ejecutar select
            $sql = "INSERT INTO tb_plato (nombre, id_categoria, descripcion, imagen, precio_regular, descuento_general, descuento_fidelidad) 
            VALUES ('$obj->nombre', '$obj->id_categoria', '$obj->descripcion', '$obj->imagen', '$obj->precio_regular', '$obj->descuento_general', '$obj->descuento_fidelidad');";
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

        public function insert_plato_dia($id)
        {
            // Conectar a base de datos
            $cn = (new Conectar())->getConectar();
            // Ejecutar select
            $sql = "INSERT INTO tb_plato_del_dia (id_plato) VALUES ('$id');";
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
            $sql = "SELECT * FROM tb_plato p
                INNER JOIN tb_categoria cat ON cat.id_categoria = p.id_categoria
                WHERE id_plato = '$id';
            ";
            $rs = mysqli_query($cn, $sql);

            // Recoge SOLO UNA fila del resultado del query
            while ($row = mysqli_fetch_row($rs)) {
                $plato = new Plato(
                    $row[0],
                    $row[1],
                    $row[8],
                    $row[9],
                    $row[10],
                    $row[3],
                    $row[4],
                    $row[5],
                    $row[6],
                    $row[7]
                );
            }

            // Cerrar conexión
            mysqli_free_result($rs);
            mysqli_close($cn);

            // Devolver objeto
            return $plato;
        }

        public function show_plato_dia()
        {
            // Conectar a base de datos
            $cn = (new Conectar())->getConectar();

            // Ejecutar select
            $sql = "SELECT * FROM tb_plato p
                INNER JOIN tb_plato_del_dia pd ON pd.id_plato = p.id_plato
                INNER JOIN tb_categoria cat ON cat.id_categoria = p.id_categoria
                ORDER BY pd.fecha_eleccion DESC
                LIMIT 1;
            ";
            $rs = mysqli_query($cn, $sql);

            // Recoge SOLO UNA fila del resultado del query
            while ($row = mysqli_fetch_row($rs)) {
                $plato = new Plato(
                    $row[0],
                    $row[1],
                    $row[11],
                    $row[12],
                    $row[13],
                    $row[3],
                    $row[4],
                    $row[5],
                    $row[6],
                    $row[7]
                );
            }

            // Cerrar conexión
            mysqli_free_result($rs);
            mysqli_close($cn);

            // Devolver objeto
            return $plato;
        }

        public function list()
        {
            // Conectar a base de datos
            $cn = (new Conectar())->getConectar();
            // Ejecutar select
            $sql = "SELECT * FROM tb_plato p
            INNER JOIN tb_categoria cat ON cat.id_categoria = p.id_categoria;";
            $rs = mysqli_query($cn, $sql);

            // Arreglo que almacena objetos
            $arr = [];
            while ($row = mysqli_fetch_array($rs)) {
                $plato = new Plato(
                    $row[0],
                    $row[1],
                    $row[8],
                    $row[9],
                    $row[10],
                    $row[3],
                    $row[4],
                    $row[5],
                    $row[6],
                    $row[7]
                );
                $arr[] = $plato;
            }

            // Cerrar conexión
            mysqli_free_result($rs);
            mysqli_close($cn);

            // Devolver arreglo
            return $arr;
        }

        // UPDATE
        public function update($obj)
        {
            // Conectar a base de datos
            $cn = (new Conectar())->getConectar();
            // Ejecutar select
            $sql = "UPDATE tb_plato SET
            nombre='$obj->nombre', id_categoria='$obj->id_categoria', descripcion='$obj->descripcion', 
            imagen='$obj->imagen', precio_regular='$obj->precio_regular', 
            descuento_general='$obj->descuento_general', descuento_fidelidad='$obj->descuento_fidelidad'
            WHERE id_plato='$obj->id'
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
            $sql = "DELETE FROM tb_plato WHERE id_plato='$id';";

            $estado = mysqli_query($cn, $sql);

            // Cerrar conexión
            mysqli_close($cn);

            // Devolver respuesta
            return $estado;
        }
    }
}
?>