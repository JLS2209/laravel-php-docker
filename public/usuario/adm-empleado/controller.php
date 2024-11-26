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
    $tipo_crud = $_POST['tipo-crud'];  // Para reconocer si la operación es INSERT/UPDATE o DELETE
    $nro_empleado = $_POST['nro-empleado'];    // Es 0 si la operación es INSERT, sino es (UPDATE/DELETE)

    // Identificar operación CRUD: Insert / Update / Delete
    if ($tipo_crud == 'delete') {
        // Verificar que el empleado a eliminar no sea el mismo usuario
        if ($nro_empleado == $_SESSION['nro']) {
            $_SESSION["mensaje"] = 0; // Mensaje: No se puede eliminar
        } else {
            // DELETE
            if ($ctrlEmpleado->delete($nro_empleado)) {
                $_SESSION["mensaje"] = 1; // Mensaje: Eliminación exitosa
            } else {
                $_SESSION["mensaje"] = -1; // Mensaje: Eliminación fallida            
            }
        }
    } else if ($tipo_crud == 'insert-update') {
        // 1) Recuperar valores de los controles del formulario "post"
        $nombre = @$_POST['nombre'];
        $apellido = @$_POST['apellido'];
        $email = @$_POST['email'];
        $usuario = @$_POST['usuario'];
        $clave = @$_POST['clave'];
        $id_rol = @$_POST['id-rol'];

        // Crear objeto de clase
        $empleado = new Empleado(
            $nro_empleado,
            $nombre,
            $apellido,
            $email,
            $usuario,
            password_hash($clave, PASSWORD_DEFAULT), // Hash
            null,
            $id_rol
        );

        // Identificar operación CRUD: Insert / Update
        if ($nro_empleado == 0) {
            // INSERT
            if ($ctrlEmpleado->insert($empleado) > 0) {
                $_SESSION["mensaje"] = 2; // Mensaje: Inserción exitosa
            } else {
                $_SESSION["mensaje"] = -2; // Mensaje: Inserción fallida            
            }

        } else {
            // UPDATE
            if ($ctrlEmpleado->update_rol($nro_empleado, $id_rol)) {
                $_SESSION["mensaje"] = 3; // Mensaje: Actualización exitosa
            } else {
                $_SESSION["mensaje"] = -3; // Mensaje: Actualización fallida            
            }
        }
    }

    // Redirigir a la página de registro
    header("location: ../adm-empleado/");

} catch (Exception $e) {
    $_SESSION["mensaje"] = -4; // Mensaje: Hubo un error
    header("location: ../adm-empleado/");
}
?>