<?php
if (!class_exists("ControladorUsuario")) {
    class ControladorUsuario
    {
        // CREATE
        public function insert($obj)
        {
        }

        // READ
        public function show($id)
        {

        }

        public function list()
        {
            // Conectar a base de datos
            $cn = (new Conectar()) ->getConectar();
            // Ejecutar select
            $sql = "SELECT * FROM tb_usuario;";
            $rs = mysqli_query($cn, $sql);

            // Arreglo que almacena objetos
            $arr = [];
            while ($row = mysqli_fetch_array($rs)) {
                $user = new Usuario(
                    $row[0],
                    $row[1],
                    $row[2],
                    $row[3]
                );
                $arr[] = $user;
            }

            // Cerrar conexión
            mysqli_free_result($rs);
            mysqli_close($cn);

            // Devolver arreglo
            return $arr;
        }

        public function login($codigo, $clave)
        {
            // Conectar a base de datos
            $cn = (new Conectar()) ->getConectar();

            // Ejecutar select
            $sql = "SELECT * FROM tb_usuario WHERE codigo_usuario = '$codigo';";
            $rs = mysqli_query($cn, $sql);

            // Recoge SOLO UNA fila del resultado del query
            while ($row = mysqli_fetch_row($rs)) {
                $user = new Usuario(
                    $row[0],
                    $row[1],
                    $row[2],
                    $row[3]
                );
            }

            // Verificar la clave
            if(password_verify($clave, $user->clave)) {
                // La clave es correcta
            }  else {
                // La clave es incorrecta
                $user = NULL;
            }

            // Cerrar conexión
            mysqli_free_result($rs);
            mysqli_close($cn);

            // Devolver objeto
            return $user;
        }

        // UPDATE
        public function update($obj)
        {

        }

        // DELETE
        public function delete($id)
        {

        }
    }
}
?>