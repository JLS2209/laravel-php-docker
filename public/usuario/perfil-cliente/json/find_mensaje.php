<?php
// Cabecera JSON
header("Content-Type: application/json");

// Acceder a la clase Conectar
include "../../../cls_conectar/cls_Conectar.php";

// Acceder a la clase Mensaje y su controlador
include "../../../modelo/cls_Mensaje.php";
include "../../../modelo/cls_Cliente.php";
include "../../../controlador/ctrl_Mensaje.php";
$ctrl = new ControladorMensaje();

// Recuperar los parámetros de la consulta
$id = $_REQUEST['id_mensaje'];

// Ejecutar consulta
$mensaje = $ctrl -> show($id);

// Convierte a JSON
print_r(json_encode($mensaje));
?>