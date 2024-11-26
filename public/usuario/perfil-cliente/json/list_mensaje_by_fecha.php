<?php
// Cabecera JSON
header("Content-Type: application/json");

// Acceder a la clase Conectar
include "../../../cls_conectar/cls_Conectar.php";

// Acceder a la clase Mensaje
include "../../../modelo/cls_Mensaje.php";
include "../../../modelo/cls_Cliente.php";

// Recuperar los parámetros de la consulta
$nro_cliente = $_REQUEST['nro_cliente'];
$fecha1 = $_REQUEST['fecha1'];
$fecha2 = $_REQUEST['fecha2'];

// Conectar a base de datos
$cn = (new Conectar())->getConectar();

// Ejecutar select
$sql = "SELECT * FROM tb_mensaje m 
        INNER JOIN tb_cliente cl ON m.nro_cliente = cl.nro_cliente
        WHERE cl.nro_cliente = '$nro_cliente' 
        AND fecha_hora > '$fecha1' AND fecha_hora < '$fecha2'
        ORDER BY fecha_hora DESC;";
$rs = mysqli_query($cn, $sql);

// Arreglo que almacena cada dato
$arr = [];
while ($row = mysqli_fetch_array($rs)) {
    // Formar objeto de clase Cliente
    $cliente = new Cliente(
        $row[5],
        $row[6],
        $row[7],
        $row[8],
        $row[9],
        $row[10],
        $row[11],
        null,
        null,
        null,
        null
    );

    // Formar objeto de clase Mensaje
    $mensaje = new Mensaje(
        $row[0],
        $cliente,
        $row[2],
        $row[3],
        $row[4]
    );

    // Agregar al arreglo
    $arr[] = array(
        "mensaje" => $mensaje,
        "tarjeta" => $mensaje->card_edit()
    );
}

// Cerrar conexión
mysqli_free_result($rs);
mysqli_close($cn);

// Convierte a JSON
print_r(json_encode($arr));
?>