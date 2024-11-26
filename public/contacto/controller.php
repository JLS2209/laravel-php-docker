<?php
try {
    // Iniciar o reanudar sesión
    session_start();

    // Acceder a la clase Conectar
    include "../cls_conectar/cls_Conectar.php";

    // Expulsar si el acceso es incorrecto
    if (!$_POST) {
        error_log("Intento invalido de acceder a pagina sin POST!");
        header("location: ../");
        return;
    }

    // Recuperar valores de los controles del formulario "post"
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];
    $asunto = $_POST['asunto'];
    $contenido = $_POST['contenido'];

    // Formar objetos de clase
    include "../modelo/cls_Cliente.php";
    include "../modelo/cls_Mensaje.php";

    $cliente = new Cliente(
        -1,
        $nombre,
        $apellido,
        $email,
        $telefono,
        null,
        null,
        null,
        null,
        1, // cliente-visitante
        null
    );

    $mensaje = new Mensaje(
        -1,
        null, // Se configura después del insert
        $asunto,
        $contenido,
        null
    );

    // Acceder a los controladores
    include "../controlador/ctrl_Cliente.php";
    include "../controlador/ctrl_Mensaje.php";
    $ctrlCliente = new ControladorCliente();
    $ctrlMensaje = new ControladorMensaje();

    // Insertar el nuevo Cliente, sin referencia a Ubicación
    $nro_cliente = $ctrlCliente->insert($cliente, NULL);
    // Si hubo éxito, insertar nuevo Mensaje
    if ($nro_cliente > 0) {
        $id_mensaje = $ctrlMensaje->insert($mensaje, $nro_cliente);
        if ($id_mensaje > 0) {
            $_SESSION["mensaje"] = 1; // Mensaje: Registro con éxito
        } else {
            $_SESSION["mensaje"] = -1; // Mensaje: No se envió el mensaje
        }
    } else {
        $_SESSION["mensaje"] = -1; // Mensaje: No se envió el mensaje
    }

    // Redirigir a la página de registro
    header("location: ../contacto/");

} catch (Exception $e) {
    $_SESSION["mensaje"] = -10; // Mensaje: Hubo un error
    header("location: ../contacto/");
}
?>