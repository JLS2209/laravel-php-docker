<?php
// Acceder al modelo
include "../../modelo/cls_Pedido.php";

try {
    // Iniciar o reanudar sesión
    session_start();

    // Acceder a la clase Conectar
    include "../../cls_conectar/cls_Conectar.php";

    // Si no existe $_SESSION["pedido"], expulsar de la página
    if (!isset($_SESSION["pedido"])) {
        error_log("Intento de acceder a pedido/controller-promociones sin pasar por las secciones previas de pedido.");
        header("location: ../../");
        return;
    }

    // Recuperar valores de los controles del formulario "post"
    $arr_id_promocion = @$_POST['id-promocion'];
    $arr_nombre_promocion = @$_POST['nombre-promocion'];
    $arr_cantidad_promocion = @$_POST['cantidad-promocion'];
    $arr_precio_promocion = @$_POST['precio-un-promocion'];

    // Vaciar la lista de promociones
    $_SESSION["pedido"]->lista_promociones = [];

    if ($arr_id_promocion != null) {        
        // Recorrer los arreglos en paralelo
        for ($i = 0; $i < sizeof($arr_id_promocion); $i++) {
            // Agregar item a la lista
            $item = array(
                "nombre_promocion" => $arr_nombre_promocion[$i],
                "cantidad_promocion" => $arr_cantidad_promocion[$i],
                "precio_un_promocion" => $arr_precio_promocion[$i],
                "id_promocion" => $arr_id_promocion[$i]
            );
            $_SESSION["pedido"]->lista_promociones[] = $item;
        }
    }

    // Actualizar el total a pagar (debe considerar el costo delivery)
    $_SESSION["pedido"]->set_total();

    // Dirigirse a sección 5 (página de confirmacion)
    header('location: ../cli-pedido/confirmacion.php');
} catch (Exception $e) {
    $_SESSION["mensaje"] = -10; // Mensaje: Hubo un error
    header("location: ../cli-pedido/");
}
?>