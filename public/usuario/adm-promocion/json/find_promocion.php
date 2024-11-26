<?php
// Cabecera JSON
header("Content-Type: application/json");

// Acceder a la clase Conectar
include "../../../cls_conectar/cls_Conectar.php";

// Acceder a la clase Promocion y su controlador
include "../../../modelo/cls_Promocion.php";
include "../../../controlador/ctrl_Promocion.php";
$ctrl = new ControladorPromocion();

// Recuperar los parámetros de la consulta
$id = $_REQUEST['id_promocion'];

// Conectar a base de datos
$cn = (new Conectar())->getConectar();

// Ejecutar select
$sql = "SELECT * FROM tb_promocion WHERE id_promocion = '$id';";
$rs = mysqli_query($cn, $sql);

// Recoge SOLO UNA fila del resultado del query
$promocion = null;
while ($row = mysqli_fetch_row($rs)) {
    // Formar objeto
    $promocion = new Promocion(
        $row[0],
        $row[1],
        $row[2],
        $row[3],
        $row[4],
        $row[5]
    );

    // Recoger los items de la promocion
    $promocion->items = $ctrl->list_items($id);
    $promocion->set_precio_regular();
    $promocion->set_precio_final();

}

// Cerrar conexión
mysqli_free_result($rs);
mysqli_close($cn);

// Convierte a JSON
print_r(json_encode($promocion));
?>