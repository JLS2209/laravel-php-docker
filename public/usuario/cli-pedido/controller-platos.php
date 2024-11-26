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
        error_log("Intento de acceder a pedido/controller-platos sin pasar por las secciones previas de pedido.");
        header("location: ../../");
        return;
    }

    // Recuperar valores de los controles del formulario "post"
    $arr_id_plato = @$_POST['id-plato'];
    $arr_nombre_plato = @$_POST['nombre-plato'];
    $arr_cantidad_plato = @$_POST['cantidad-plato'];
    $arr_precio_plato = @$_POST['precio-un-plato'];

    // Vaciar la lista de platos
    $_SESSION["pedido"]->lista_platos = [];

    if ($arr_id_plato != null) {        
        // Recorrer los arreglos en paralelo
        for ($i = 0; $i < sizeof($arr_id_plato); $i++) {
            // Agregar item a la lista
            $item = array(
                "nombre_plato" => $arr_nombre_plato[$i],
                "cantidad_plato" => $arr_cantidad_plato[$i],
                "precio_un_plato" => $arr_precio_plato[$i],
                "id_plato" => $arr_id_plato[$i]
            );
            $_SESSION["pedido"]->lista_platos[] = $item;
        }
    }

    // Actualizar el total a pagar (debe considerar el costo delivery)
    $_SESSION["pedido"]->set_total();

    // Dirigirse a sección 4 (página de promociones)
    header('location: ../cli-pedido/promociones.php');
} catch (Exception $e) {
    $_SESSION["mensaje"] = -10; // Mensaje: Hubo un error
    header("location: ../cli-pedido/");
}
?>