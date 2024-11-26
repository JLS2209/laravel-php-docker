<?php
// Cabecera JSON
header("Content-Type: application/json");

// Acceder a la clase Conectar
include "../../../cls_conectar/cls_Conectar.php";

// Acceder a la clase Cliente
include "../../../modelo/cls_Cliente.php";
include "../../../modelo/cls_Ubicacion.php";

// Recuperar los parámetros de la consulta
$distrito = $_REQUEST['distrito'];

// Conectar a base de datos
$cn = (new Conectar())->getConectar();

// Ejecutar select
if ($distrito == 0) {
    $sql = "SELECT * FROM tb_cliente cl
        LEFT JOIN tb_usuario us ON cl.codigo_usuario = us.codigo_usuario
        LEFT JOIN tb_ubicacion ub ON cl.id_ubicacion = ub.id_ubicacion
        LEFT JOIN tb_distrito d ON d.id_distrito = ub.id_distrito;";
} else {
    $sql = "SELECT * FROM tb_cliente cl
        LEFT JOIN tb_usuario us ON cl.codigo_usuario = us.codigo_usuario
        LEFT JOIN tb_ubicacion ub ON cl.id_ubicacion = ub.id_ubicacion
        LEFT JOIN tb_distrito d ON d.id_distrito = ub.id_distrito
        WHERE ub.id_distrito = '$distrito';";
}
$rs = mysqli_query($cn, $sql);

// Arreglo que almacena cada dato
$arr = [];
while ($row = mysqli_fetch_array($rs)) {
    // Formar objeto de clase Ubicacion
    $ubicacion = ($row[7] == null) ? null : new Ubicacion(
        $row[7],
        $row[13],
        new Distrito ($row[14], $row[18], $row[19]),
        $row[15],
        $row[16]
    );

    // Formar objeto de clase Cliente
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

    // Agregar al arreglo
    $arr[] = array(
        "cliente" => $cliente,
        "fila" => $cliente->table_row()
    );
}

// Cerrar conexión
mysqli_free_result($rs);
mysqli_close($cn);

// Convierte a JSON
print_r(json_encode($arr));
?>