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
    $usuario = $_POST['usuario'];
    $clave = $_POST['clave'];
    $has_ubicacion = (@$_POST['has_ubicacion'] == "si");
    $id_distrito = @$_POST['id_distrito'];
    $direccion = @$_POST['direccion'];
    $coord = @$_POST['coordenadas'];

    // Formar objetos de clase
    include "../modelo/cls_Cliente.php";
    include "../modelo/cls_Ubicacion.php";

    $ubicacion = (!$has_ubicacion) ? null : new Ubicacion(
        -1,
        $direccion,
        new Distrito ($id_distrito, null, null),
        explode(',', substr($coord, 1, -1))[0],
        explode(',', substr($coord, 1, -1))[1]
    );

    $cliente = new Cliente(
        -1,
        $nombre,
        $apellido,
        $email,
        $telefono,
        0,
        $usuario,
        password_hash($clave, PASSWORD_DEFAULT), // Hash
        null,
        2, // cliente-registrado
        $ubicacion
    );

    // Acceder a los controladores
    include "../controlador/ctrl_Cliente.php";
    include "../controlador/ctrl_Ubicacion.php";

    $ctrlUbicacion = new ControladorUbicacion();
    $ctrlCliente = new ControladorCliente();

    // Realizar insert
    if ($has_ubicacion) {
        // Insertar primero la nueva Ubicacion
        $id_ubicacion = $ctrlUbicacion->insert($ubicacion);
        // Si hubo éxito, insertar nuevo Cliente
        if ($id_ubicacion > 0) {
            $nro_cliente = $ctrlCliente->insert($cliente, $id_ubicacion);
            // Si hubo éxito, enviar mensaje
            if ($nro_cliente > 0) {
                $_SESSION["mensaje"] = 1; // Mensaje: Registro con éxito
            } else {
                $_SESSION["mensaje"] = -2; // Mensaje: Hubo un error, debido código de usuario repetido
            }
        } else {
            $_SESSION["mensaje"] = -1; // Mensaje: Hubo un error
        }
    } else {
        // Insertar el nuevo Cliente, sin referencia a Ubicación
        $nro_cliente = $ctrlCliente->insert($cliente, NULL);
        // Si hubo éxito, enviar mensaje
        if ($nro_cliente > 0) {
            $_SESSION["mensaje"] = 1; // Mensaje: Registro con éxito
        } else {
            $_SESSION["mensaje"] = -2; // Mensaje: Hubo un error, debido código de usuario repetido
        }
    }

    // Redirigir a la página de registro
    header("location: ../registro/");

} catch (Exception $e) {
    $_SESSION["mensaje"] = -1; // Mensaje: Hubo un error
    header("location: ../registro/");
}
?>