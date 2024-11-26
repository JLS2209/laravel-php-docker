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
    include "../../modelo/cls_Cliente.php";
    include "../../controlador/ctrl_Cliente.php";
    $ctrlCliente = new ControladorCliente();

    // Recuperar valores de los controles del formulario "post"
    $nro_cliente = $_POST['nro-cliente'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];

    // Crear objeto de clase
    $cliente = new Cliente(
        $nro_cliente,
        $nombre,
        $apellido,
        $email,
        $telefono,
        null,
        null,
        null,
        null,
        null,
        null
    );

    // UPDATE
    if ($ctrlCliente->update_perfil($cliente)) {
        $_SESSION["mensaje"] = 1; // Mensaje: Actualización exitosa
    } else {
        $_SESSION["mensaje"] = -1; // Mensaje: Actualización fallida            
    }

    // Redirigir a la página de perfil
    header("location: ../perfil-cliente/");

} catch (Exception $e) {
    $_SESSION["mensaje"] = -10; // Mensaje: Hubo un error
    header("location: ../perfil-cliente/");
}
?>