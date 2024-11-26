<?php
// Cabecera JSON
header("Content-Type: application/json");

// Acceder a la clase Conectar
include "../../../cls_conectar/cls_Conectar.php";

// Acceder a la clase Cliente y su controlador
include "../../../modelo/cls_Cliente.php";
include "../../../modelo/cls_Ubicacion.php";
include "../../../controlador/ctrl_Cliente.php";
$ctrl = new ControladorCliente();

// Recuperar los parámetros de la consulta
$id = $_REQUEST['nro_cliente'];

// Ejecutar consulta
$cliente = $ctrl -> show($id);

// Convierte a JSON
print_r(json_encode($cliente));
?>