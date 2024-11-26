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
    include "../../modelo/cls_Empleado.php";
    include "../../controlador/ctrl_Empleado.php";
    $ctrlEmpleado = new ControladorEmpleado();

    // Recuperar valores de los controles del formulario "post"
    $nro_empleado = $_POST['nro-empleado'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $email = $_POST['email'];

    // Crear objeto de clase
    $empleado = new Empleado(
        $nro_empleado,
        $nombre,
        $apellido,
        $email,
        null,
        null,
        null,
        null
    );

    // UPDATE
    if ($ctrlEmpleado->update_perfil($empleado)) {
        $_SESSION["mensaje"] = 1; // Mensaje: Actualización exitosa
    } else {
        $_SESSION["mensaje"] = -1; // Mensaje: Actualización fallida            
    }

    // Redirigir a la página de perfil
    header("location: ../perfil-empleado/");

} catch (Exception $e) {
    $_SESSION["mensaje"] = -10; // Mensaje: Hubo un error
    header("location: ../perfil-empleado/");
}
?>