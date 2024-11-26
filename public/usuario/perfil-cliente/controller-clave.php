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
    include "../../modelo/cls_Usuario.php";
    include "../../controlador/ctrl_Cliente.php";
    include "../../controlador/ctrl_Usuario.php";
    $ctrlCliente = new ControladorCliente();
    $ctrlUsuario = new ControladorUsuario();

    // Recuperar valores de los controles del formulario "post"
    $nro_cliente = $_POST['nro-cliente'];
    $codigo_usuario = $_POST['codigo-usuario'];
    $clave_antigua = $_POST['clave-antigua'];
    $clave_nueva = password_hash($_POST['clave-nueva'], PASSWORD_DEFAULT); // Hash

    // Invocar método de login (para confirmar que la contraseña antigua sea correcta)    
    $user = $ctrlUsuario->login($codigo_usuario, $clave_antigua);

    // Verificar usuario
    if ($user == NULL) {
        // Mensaje: Contraseña antigua errónea
        $_SESSION["mensaje"] = -2;
    } else {
        // UPDATE
        if ($ctrlCliente->update_passw($nro_cliente, $clave_nueva)) {
            $_SESSION["mensaje"] = 2; // Mensaje: Actualización exitosa
        } else {
            $_SESSION["mensaje"] = -3; // Mensaje: Actualización fallida            
        }
    }

    // Redirigir a la página de perfil
    header("location: ../perfil-cliente/");

} catch (Exception $e) {
    $_SESSION["mensaje"] = -10; // Mensaje: Hubo un error
    header("location: ../perfil-cliente/");
}
?>