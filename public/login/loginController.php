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
    $usuario = $_POST['usuario'];
    $clave = $_POST['clave'];

    // Acceder al controlador
    include "../modelo/cls_Usuario.php";
    include "../controlador/ctrl_Usuario.php";

    // Invocar método de login
    $ctrlUsuario = new ControladorUsuario();
    $user = $ctrlUsuario->login($usuario, $clave);

    // Verificar usuario
    if ($user == NULL) {
        // Mensaje: Credenciales incorrectas
        $_SESSION["mensaje"] = -1;
        // Guardar variables en la sesión para volver a llenar
        $_SESSION["login_usuario"] = $usuario;
        $_SESSION["login_clave"] = $clave;
        // Redirigir a página de login
        header("location: ../login/");
    } else {
        // Guardar el rol en la sesión
        $_SESSION["id_rol"] = $user->id_rol;

        // Convertir el usuario a objeto de clase Cliente o Empleado, según corresponda
        if ($user->id_rol == 2) {
            // Es un Cliente registrado
            include "../modelo/cls_Cliente.php";
            include "../controlador/ctrl_Cliente.php";

            // Invocar método para identificar a partir de usuario
            $ctrlCliente = new ControladorCliente();
            $cliente = $ctrlCliente->show_user($user->codigo_usuario);

            // Guardar el número identificador en la sesión
            $_SESSION["nro"] = $cliente->nro_cliente;

        } else if ($user->id_rol == 3 || $user->id_rol == 4) {
            // Es un Empleado de atención al cliente o administrador
            include "../modelo/cls_Empleado.php";
            include "../controlador/ctrl_Empleado.php";

            // Invocar método para identificar a partir de usuario
            $ctrlEmpleado = new ControladorEmpleado();
            $empleado = $ctrlEmpleado->show_user($user->codigo_usuario);

            // Guardar el número identificador en la sesión
            $_SESSION["nro"] = $empleado->nro_empleado;
        }

        // Redirigir a página principal
        header("location: ../");
    }

} catch (Exception $e) {
    // Mensaje: Credenciales incorrectas
    $_SESSION["mensaje"] = -1;
    // Guardar variables en la sesión para volver a llenar
    $_SESSION["login_usuario"] = $usuario;
    $_SESSION["login_clave"] = $clave;
    // Redirigir a página de login
    header("location: ../login/");
}
?>