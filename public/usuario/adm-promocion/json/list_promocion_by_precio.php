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
$precio = $_REQUEST['precio'];

// Conectar a base de datos
$cn = (new Conectar())->getConectar();

// Ejecutar select
if ($precio == 0) {
    $sql = "SELECT * FROM tb_promocion;";
} else {
    $sql = "SELECT prom.id_promocion, prom.nombre, prom.descripcion, prom.imagen, cantidad_maxima, descuento_promocion
            FROM tb_promocion prom
            INNER JOIN tb_detalle_promocion det ON det.id_promocion = prom.id_promocion
            INNER JOIN tb_plato pl ON pl.id_plato = det.id_plato
            GROUP BY prom.id_promocion
            HAVING (SUM(pl.precio_regular * det.cantidad_plato * (100 - descuento_promocion)/100)) > '$precio'
            ;";
}
$rs = mysqli_query($cn, $sql);

// Arreglo que almacena cada dato
$arr = [];
while ($row = mysqli_fetch_array($rs)) {
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
    $promocion->items = $ctrl->list_items($row[0]);
    $promocion->set_precio_regular();
    $promocion->set_precio_final();

    // Agregar al arreglo
    $arr[] = array(
        "promocion" => $promocion,
        "carta" => $promocion->card_vertical_edit("../../multimedia/imagenes")
    );
}

// Cerrar conexión
mysqli_free_result($rs);
mysqli_close($cn);

// Convierte a JSON
print_r(json_encode($arr));
?>