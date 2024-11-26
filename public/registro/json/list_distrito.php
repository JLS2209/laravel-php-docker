<?php
// Cabecera JSON
header("Content-Type: application/json");

// Acceder a la clase Conectar
include "../../cls_conectar/cls_Conectar.php";

// Conectar a base de datos
$cn = (new Conectar())->getConectar();

// Recuperar los parámetros de la consulta
$provincia = $_REQUEST['provincia'];

// Ejecutar select
$sql = "SELECT * FROM tb_distrito WHERE provincia = '$provincia' ORDER BY distrito;";
$rs = mysqli_query($cn, $sql);

// Arreglo que almacena objetos
$arr = [];
while ($row = mysqli_fetch_array($rs)) {
    $arr[] = array(
        "id" => $row[0],
        "nombre" => $row[1]
    );
}

// Cerrar conexión
mysqli_free_result($rs);
mysqli_close($cn);

// Convierte a JSON
print_r(json_encode($arr));
?>