<?php
// Cabecera JSON
header("Content-Type: application/json");

// Acceder a la clase Conectar
include "../../../cls_conectar/cls_Conectar.php";

// Acceder a la clase Plato y su controlador
include "../../../modelo/cls_Plato.php";
include "../../../controlador/ctrl_Plato.php";
$ctrl = new ControladorPlato();

// Recuperar los parámetros de la consulta
$id = $_REQUEST['id_plato'];

// Ejecutar consulta
$plato = $ctrl -> show($id);

// Convierte a JSON
print_r(json_encode($plato));
?>