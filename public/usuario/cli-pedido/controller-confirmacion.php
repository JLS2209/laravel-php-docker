<?php
// Acceder al modelo
include "../../modelo/cls_Pedido.php";
include "../../modelo/cls_Ubicacion.php";
// Acceder a controladores
// include "../../controlador/ctrl_Pedido.php";
include "../../controlador/ctrl_Ubicacion.php";

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

    // Si no existe $_SESSION["pedido"], expulsar de la página
    if (!isset($_SESSION["pedido"])) {
        error_log("Intento de acceder a pedido/controller-confirmacion sin pasar por las secciones previas de pedido.");
        header("location: ../../");
        return;
    }

    // Evaluar acciones
    if ($_POST["confirmar-descartar"] == 0) {
        $_SESSION["mensaje"] = 2; // Pedido descartado
        // Descartar el pedido
        unset($_SESSION["tarjeta"]);
        unset($_SESSION["pedido"]);        
    } else if ($_POST["confirmar-descartar"] == 1) {
        
        $ok = true; // Centinela
        // Recuperar objeto de clase Pedido
        /**
         * @var Pedido
         */
        $obj = $_SESSION["pedido"];

        // 0) UPDATE la fidelidad del cliente
        $nro_cliente = $obj -> nro_cliente;

        // 1) INSERT en tb_ubicacion, si es necesario
        $ubi = $obj->ubicacion; // Recuperar objeto Ubicacion
        $id_ubi = -1;
        if ($ubi != null) {
            // Ejecutar INSERT en la base de datos y recuperar PK
            // $id_ubi = ...;
            // Actualizar $ok
        }

        // 2) INSERT en tb_pedido
        if ($ok) {
            if ($id_ubi == -1) {
                // Preparar INSERT sin ubicacion
            } else {
                // Preparar INSERT con ubicacion
            }

            // Ejecutar INSERT en la base de datos y recuperar PK
            $id_pedido = -1; // $id_pedido = ...;
            // Actualizar $ok

            // 3) INSERT en las tablas de detalle
            if ($ok && $id_pedido > 0) {

                $lista_platos = $obj->lista_platos;
                if (!empty($lista_platos)) {
                    // Preparar INSERT en tb_detalle_pedido_plato
                    $sql = "INSERT INTO tb_detalle_pedido_plato (nro_pedido, id_plato, cantidad_plato, precio) VALUES ";

                    // Recorrer la lista
                    for ($i = 0; $i < sizeof($lista_platos); $i++) {
                        $item = $lista_platos[$i];
                        $id_plato = $item["id_plato"];
                        $cantidad_plato = $item["cantidad_plato"];
                        $precio_plato = $item["precio_un_plato"] * $cantidad_plato;
                        // Agregar a la sentencia
                        $sql .= "('$id_pedido', '$id_plato', '$cantidad_plato', '$precio_plato') ";
                        if ($i != sizeof($lista_platos) - 1) {
                            $sql .= ", ";
                        }
                    }

                    // Ejecutar INSERT
                    // Actualizar $ok
                }

                $lista_promociones = $obj->lista_promociones;
                if (!empty($lista_promociones)) {
                    // Preparar INSERT en tb_detalle_pedido_promocion
                    $sql = "INSERT INTO tb_detalle_pedido_promocion (nro_pedido, id_promocion, cantidad_promocion, precio) VALUES ";

                    // Recorrer la lista
                    for ($i = 0; $i < sizeof($lista_promociones); $i++) {
                        $item = $lista_promociones[$i];
                        $id_promocion = $item["id_promocion"];
                        $cantidad_promocion = $item["cantidad_promocion"];
                        $precio_promocion = $item["precio_un_promocion"] * $cantidad_promocion;
                        // Agregar a la sentencia
                        $sql .= "('$id_pedido', '$id_promocion', '$cantidad_promocion', '$precio_promocion') ";
                        if ($i != sizeof($lista_promociones) - 1) {
                            $sql .= ", ";
                        }
                    }

                    // Ejecutar INSERT
                    // Actualizar $ok
                }

            }
        }

        // 4) Verificación
        if ($ok) {
            $_SESSION["mensaje"] = 1; // Pedido enviado con éxito
            // Resetear el pedido
            unset($_SESSION["tarjeta"]);
            unset($_SESSION["pedido"]);
        } else {
            $_SESSION["mensaje"] = -1; // Algo salió mal. Vuelva a intentarlo
        }
    } else {
        // No debería existir otra opción
        $_SESSION["mensaje"] = -10; // Hubo un error
    }

    // Dirigirse a sección principal de pedidos
    header('location: ../cli-pedido/');

} catch (Exception $e) {
    $_SESSION["mensaje"] = -10; // Mensaje: Hubo un error
    header("location: ../cli-pedido/");
}

?>