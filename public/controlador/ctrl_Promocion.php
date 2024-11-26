<?php
if (!class_exists("ControladorPromocion")) {
    class ControladorPromocion
    {
        // CREATE
        public function insert($obj)
        {
            // Conectar a base de datos
            $cn = (new Conectar())->getConectar();
            // Ejecutar select
            $sql = "INSERT INTO tb_promocion (nombre, descripcion, imagen, cantidad_maxima, descuento_promocion) 
            VALUES ('$obj->nombre', '$obj->descripcion', '$obj->imagen', '$obj->cantidad_max', '$obj->descuento');";
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
            $sql = "SELECT * FROM tb_promocion WHERE id_promocion = '$id';";
            $rs = mysqli_query($cn, $sql);

            // Recoge SOLO UNA fila del resultado del query
            while ($row = mysqli_fetch_row($rs)) {
                $prom = new Promocion(
                    $row[0],
                    $row[1],
                    $row[2],
                    $row[3],
                    $row[4],
                    $row[5]
                );
            }

            // Cerrar conexión
            mysqli_free_result($rs);
            mysqli_close($cn);

            // Devolver arreglo
            return $prom;
        }

        public function list()
        {
            // Conectar a base de datos
            $cn = (new Conectar())->getConectar();
            // Ejecutar select
            $sql = "SELECT * FROM tb_promocion;";
            $rs = mysqli_query($cn, $sql);

            // Arreglo que almacena objetos
            $arr = [];
            while ($row = mysqli_fetch_array($rs)) {
                $prom = new Promocion(
                    $row[0],
                    $row[1],
                    $row[2],
                    $row[3],
                    $row[4],
                    $row[5]
                );
                $arr[] = $prom;
            }

            // Cerrar conexión
            mysqli_free_result($rs);
            mysqli_close($cn);

            // Devolver arreglo
            return $arr;
        }

        public function list_items($id_prom)
        {
            // Conectar a base de datos
            $cn = (new Conectar())->getConectar();
            // Ejecutar select
            $sql = "SELECT id_promocion, nombre, cantidad_plato, precio_regular, pl.id_plato 
                    FROM tb_detalle_promocion det
                    INNER JOIN tb_plato pl ON pl.id_plato = det.id_plato
                    WHERE det.id_promocion = '$id_prom'
                    ;";
            $rs = mysqli_query($cn, $sql);

            // Arreglo que almacena objetos
            $arr = [];
            while ($row = mysqli_fetch_array($rs)) {
                $arr[] = array(
                    "nombre_plato" => $row[1],
                    "cantidad_plato" => $row[2],
                    "precio_plato" => $row[3],
                    "id_plato" => $row[4]
                );
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
            $sql = "UPDATE tb_promocion SET
            nombre='$obj->nombre', descripcion='$obj->descripcion', 
            imagen='$obj->imagen', cantidad_maxima='$obj->cantidad_max', 
            descuento_promocion='$obj->descuento'
            WHERE id_promocion='$obj->id'
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
            $sql = "DELETE FROM tb_promocion WHERE id_promocion='$id';";

            $estado = mysqli_query($cn, $sql);

            // Cerrar conexión
            mysqli_close($cn);

            // Devolver respuesta
            return $estado;
        }
    }
}
?>