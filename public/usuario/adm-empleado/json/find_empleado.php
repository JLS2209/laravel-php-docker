<?php
// Cabecera JSON
header("Content-Type: application/json");

// Acceder a la clase Conectar
include "../../../cls_conectar/cls_Conectar.php";

// Acceder a la clase Empleado y su controlador
include "../../../modelo/cls_Empleado.php";
include "../../../controlador/ctrl_Empleado.php";
$ctrl = new ControladorEmpleado();

// Recuperar los parámetros de la consulta
$id = $_REQUEST['nro_empleado'];

// Ejecutar consulta
$empleado = $ctrl -> show($id);

// Convierte a JSON
print_r(json_encode($empleado));
?>