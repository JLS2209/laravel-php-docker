<?php
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

    // Acceder al modelo y controlador
    include "../../modelo/cls_Ubicacion.php";
    include "../../controlador/ctrl_Ubicacion.php";
    include "../../controlador/ctrl_Cliente.php";
    $ctrlCliente = new ControladorCliente();
    $ctrlUbicacion = new ControladorUbicacion();

    // Recuperar valores de los controles del formulario "post"
    $nro_cliente = $_POST['nro-cliente'];
    $id_ubicacion = $_POST['id-ubicacion'];
    $id_distrito = $_POST['id_distrito'];
    $direccion = $_POST['direccion'];
    $coord = $_POST['coordenadas'];

    // Formar objeto de clase Ubicacion
    $ubicacion = new Ubicacion(
        $id_ubicacion,
        $direccion,
        new Distrito($id_distrito, null, null),
        explode(',', substr($coord, 1, -1))[0],
        explode(',', substr($coord, 1, -1))[1]
    );

    // Si el cliente está brindando su ubicación por primera vez
    if ($id_ubicacion == '0') {

        // INSERT en tb_ubicacion y recuperar ID recién insertado
        $id_ubicacion = $ctrlUbicacion->insert($ubicacion);

        // Verificar si ocurrrió algún error
        if ($id_ubicacion <= 0) {
            $_SESSION["mensaje"] = -4; // Mensaje: Actualización fallida
        }
    }
    // Si el cliente ya tenía una ubicación y la está cambiando
    else {
        // UPDATE en tb_ubicacion
        $estado = $ctrlUbicacion->update($ubicacion);

        // Verificar si ocurrrió algún error
        if ($estado == false) {
            $_SESSION["mensaje"] = -4; // Mensaje: Actualización fallida
            $id_ubicacion = -1;
        }
    }

    // UPDATE en tb_cliente
    if ($id_ubicacion > 0) {
        if ($ctrlCliente->update_ubicacion($nro_cliente, $id_ubicacion)) {
            $_SESSION["mensaje"] = 3; // Mensaje: Actualización exitosa
        } else {
            $_SESSION["mensaje"] = -4; // Mensaje: Actualización fallida            
        }
    }

    // Redirigir a la página de perfil
    header("location: ../perfil-cliente/");

} catch (Exception $e) {
    $_SESSION["mensaje"] = -10; // Mensaje: Hubo un error
    header("location: ../perfil-cliente/");
}
?>