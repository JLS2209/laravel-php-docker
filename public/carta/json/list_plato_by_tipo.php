<?php
// Cabecera JSON
header("Content-Type: application/json");

// Acceder a la clase Conectar
include "../../cls_conectar/cls_Conectar.php";
// Acceder a la clase Plato
include "../../modelo/cls_Plato.php";

// Recuperar los parámetros de la consulta
$tipo = $_REQUEST['tipo'];
$is_cliente_regular = $_REQUEST['is_regular'];

// Conectar a base de datos
$cn = (new Conectar())->getConectar();

// Ejecutar select
if ($tipo == 0) {
    $sql = "SELECT * FROM tb_plato p
            INNER JOIN tb_categoria cat ON cat.id_categoria = p.id_categoria;";
} else {
    $sql = "SELECT * FROM tb_plato p
            INNER JOIN tb_categoria cat ON cat.id_categoria = p.id_categoria
            WHERE cat.tipo = '$tipo';";
}
$rs = mysqli_query($cn, $sql);

// Arreglo que almacena cada dato
$arr = [];
while ($row = mysqli_fetch_array($rs)) {
    // Formar objeto
    $plato = new Plato(
        $row[0],
        $row[1],
        $row[8],
        $row[9],
        $row[10],
        $row[3],
        $row[4],
        $row[5],
        $row[6],
        $row[7]
    );

    // Agregar al arreglo
    $arr[] = array(
        "plato" => $plato,
        "carta" => $plato -> card_vertical_menu("../multimedia/imagenes", $is_cliente_regular)
    );
}

// Cerrar conexión
mysqli_free_result($rs);
mysqli_close($cn);

// Convierte a JSON
print_r(json_encode($arr));
?>