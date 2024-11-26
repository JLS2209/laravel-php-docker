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
    $ctrl = new ControladorCliente();

    // Recuperar valores de los controles del formulario "post"
    $tipo_crud = $_POST['tipo-crud'];  // Para reconocer si la operación es INSERT/UPDATE o DELETE
    $nro_cliente = $_POST['nro-cliente'];    // Es 0 si la operación es INSERT, sino es (UPDATE/DELETE)

    // Identificar operación CRUD: Insert / Update / Delete
    if ($tipo_crud == 'delete') {
        // DELETE
        if ($ctrl->delete($nro_cliente)) {
            $_SESSION["mensaje"] = 1; // Mensaje: Eliminación exitosa
        } else {
            $_SESSION["mensaje"] = -1; // Mensaje: Eliminación fallida            
        }
    }

    // Redirigir a la página de registro
    header("location: ../adm-cliente/");

} catch (Exception $e) {
    $_SESSION["mensaje"] = -4; // Mensaje: Hubo un error
    header("location: ../adm-cliente/");
}
?>