<?php
// Cabecera JSON
header("Content-Type: application/json");

// Acceder a la clase Conectar
include "../../../cls_conectar/cls_Conectar.php";
// Acceder a la clase Empleado
include "../../../modelo/cls_Empleado.php";

// Recuperar los parámetros de la consulta
$rol = $_REQUEST['rol'];

// Conectar a base de datos
$cn = (new Conectar())->getConectar();

// Ejecutar select
if ($rol == 0) {
    $sql = "SELECT * FROM tb_empleado em
            INNER JOIN tb_usuario us ON em.codigo_usuario = us.codigo_usuario ;";
} else {
    $sql = "SELECT * FROM tb_empleado em
            INNER JOIN tb_usuario us ON em.codigo_usuario = us.codigo_usuario
            WHERE us.id_rol = '$rol';";
}
$rs = mysqli_query($cn, $sql);

// Arreglo que almacena cada dato
$arr = [];
while ($row = mysqli_fetch_array($rs)) {
    // Formar objeto
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

    // Agregar al arreglo
    $arr[] = array(
        "empleado" => $empleado,
        "fila" => $empleado -> table_row()
    );
}

// Cerrar conexión
mysqli_free_result($rs);
mysqli_close($cn);

// Convierte a JSON
print_r(json_encode($arr));
?>