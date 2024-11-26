<?php
try {
    // Iniciar o reanudar sesión
    session_start();

    // Acceder a la clase Conectar
    include "../../cls_conectar/cls_Conectar.php";

    // Expulsar si el acceso es incorrecto
    if (!$_POST) {
        error_log("Intento invalido de acceder a pagina sin POST!");
        header("location: ../");
        return;
    }

    // Recuperar valores de los controles del formulario "post"
    $nro_cliente = $_POST['nro-cliente'];
    $asunto = $_POST['asunto'];
    $contenido = $_POST['contenido'];

    // Formar objetos de clase
    include "../../modelo/cls_Mensaje.php";

    $mensaje = new Mensaje(
        -1,
        null, // El ID se pasa directamente a la función
        $asunto,
        $contenido,
        null
    );

    // Acceder a los controladores
    include "../../controlador/ctrl_Mensaje.php";
    $ctrlMensaje = new ControladorMensaje();

    // Insertar el mensaje
    $id_mensaje = $ctrlMensaje->insert($mensaje, $nro_cliente);
    if ($id_mensaje > 0) {
        $_SESSION["mensaje"] = 1; // Mensaje: Registro con éxito
    } else {
        $_SESSION["mensaje"] = -1; // Mensaje: No se envió el mensaje
    }

    // Redirigir a la página de registro
    header("location: ../cli-contacto/");

} catch (Exception $e) {
    $_SESSION["mensaje"] = -10; // Mensaje: Hubo un error
    header("location: ../cli-contacto/");
}
?>