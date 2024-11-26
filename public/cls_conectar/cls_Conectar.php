<?php
if (!class_exists("Conectar")) {
    class Conectar
    {
        function getConectar()
        {
            $server = "db4free.net:3306";
            $login = "jls2209";
            $pass = "2G9fk4BZ8G8a!@Z";
            $bdatos = "proyecto_colegio";
            $cn = "";
            $cn = mysqli_connect($server, $login, $pass, $bdatos);
            if (mysqli_connect_error()) {
                echo 'error nro: ' . mysqli_connect_errno();
            }
            return $cn;
        }
    }
}

?>