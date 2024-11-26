<?php
// Acceder al modelo
include "../../modelo/cls_Pedido.php";

try {
    // Iniciar o reanudar sesión
    session_start();

    // Acceder a la clase Conectar
    include "../../cls_conectar/cls_Conectar.php";

    // Expulsar si el acceso es incorrecto
    if (!$_POST) {
        error_log("Intento invalido de acceder a pagina sin POST!");
        header("location: ../../");
        return;
    }    

    // Recuperar numero ID de cliente a partir de la sesión
    $nro = isset($_SESSION["nro"]) ? $_SESSION["nro"] : -1;

    // Crear sesión var pedido, si no existe; si no solo actualizar los valores POST
    if (isset($_SESSION["pedido"])) {
        $_SESSION["pedido"]->nro_cliente = $nro;
        $_SESSION["pedido"]->opcion_entrega = $_POST['opcion-entrega'];
        $_SESSION["pedido"]->metodo_pago = $_POST['metodo-pago'];
    } else {
        $_SESSION["pedido"] = new Pedido(
            -1,         // Default. Se crea automáticamente en el INSERT a la BD
            $nro,
            $_POST['opcion-entrega'],
            null,        // Se llena en la siguiente sección, solo si opción_entrega == 1
            $_POST['metodo-pago'],
            0.0,    // Se llena después
            0.0, 		// Se recalcula de la lista de items con el método set_total() de la clase Pedido
            null, 		// Default. Se crea automáticamente en el INSERT a la BD
            1 			    // 1: En espera, 2: En preparación, 3: En transporte, 4: Finalizado, 5: Cancelado
        );
    }

    // Crear sesión var tarjeta
    $_SESSION["tarjeta"] = array(
        "numero-tarjeta" => isset($_POST['numero-tarjeta']) ? $_POST['numero-tarjeta'] : "",
        "cvv" => isset($_POST['cvv']) ? $_POST['cvv'] : "",
        "fecha-vencimiento" => isset($_POST['fecha-vencimiento']) ? $_POST['fecha-vencimiento'] : ""
    );

    // Evaluar si dirigirse a sección 2 (página de ubicación) o 3 (página de platos)
    if ($_SESSION["pedido"]->opcion_entrega == 1) {
        $_SESSION["pedido"]->costo_delivery = 5.0;		// Establecer delivery en 5 soles por defecto
        header('location: ../cli-pedido/ubicacion.php');
    } else {
        $_SESSION["pedido"]->ubicacion = null; 		// Eliminar ubicación, por si existía antes
        $_SESSION["pedido"]->costo_delivery = 0.0; 		// Resetear costo de delivery
        header('location: ../cli-pedido/platos.php');
    }
} catch (Exception $e) {
    $_SESSION["mensaje"] = -10; // Mensaje: Hubo un error
    header("location: ../cli-pedido/");
}
?>