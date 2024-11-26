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
    include "../../modelo/cls_Mensaje.php";
    include "../../controlador/ctrl_Mensaje.php";
    $ctrl = new ControladorMensaje();

    // Recuperar valores de los controles del formulario "post"
    $tipo_crud = $_POST['tipo-crud'];  // Para reconocer si la operación es UPDATE o DELETE
    $id_mensaje = $_POST['id-mensaje'];

    // Identificar operación CRUD: Insert / Update / Delete
    if ($tipo_crud == 'delete') {
        // DELETE
        if ($ctrl->delete($id_mensaje)) {
            $_SESSION["mensaje"] = 1; // Mensaje: Eliminación exitosa
        } else {
            $_SESSION["mensaje"] = -1; // Mensaje: Eliminación fallida            
        }
    } else if ($tipo_crud == 'edit') {
        // Recuperar valores de los controles del formulario "post"
        $asunto = @$_POST['asunto'];
        $contenido = @$_POST['contenido'];

        // Crear objeto de clase
        $mensaje = new Mensaje(
            $id_mensaje,
            null,
            $asunto,
            $contenido,
            null
        );

        // UPDATE
        if ($ctrl->update($mensaje)) {
            $_SESSION["mensaje"] = 2; // Mensaje: Actualización exitosa
        } else {
            $_SESSION["mensaje"] = -2; // Mensaje: Actualización fallida            
        }
    }

    // Redirigir a la página de registro
    header("location: ../atn-contacto/");

} catch (Exception $e) {
    $_SESSION["mensaje"] = -10; // Mensaje: Hubo un error
    header("location: ../atn-contacto/");
}
?>