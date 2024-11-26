<?php
// Iniciar o reanudar sesión
session_start();

// Restringir acceso si el id_rol no corresponde a empleado (3 o 4)
if (!(isset($_SESSION["id_rol"]) && $_SESSION["id_rol"] >= 3)) {
    error_log("Intento de acceso sin credenciales adecuadas de empleado!");
    header("location: ../../");
    return;
}

// Acceder a la clase Conectar
include "../../cls_conectar/cls_Conectar.php";

// Acceder a la clase Empleado y su controlador
include "../../modelo/cls_Empleado.php";
include "../../controlador/ctrl_Empleado.php";
$ctrlEmpleado = new ControladorEmpleado();

// Recuperar Empleado a partir de la sesión
$empleado = $ctrlEmpleado->show($_SESSION['nro']);
$nombre_rol = ($empleado->id_rol == 3) ? "Empleado de Atención al Cliente" : (($empleado->id_rol == 4) ? "Empleado Administrador" : "");
$nombre_completo = "$empleado->nombre $empleado->apellido"
    ?>

<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Restaurante Sazón & Fuego</title>
    <!--Bootstrap CSS-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <!--Customized CSS-->
    <style>
        /* Color de fondo de la página */
        body {
            background: linear-gradient(to right, #F7BA00, #F0A330);
        }

        /*Estilos de los nav-pills*/
        .nav-pills .nav-link.active {
            color: black;
            background-color: #F7BA00;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <!-- Cuerpo de la página -->
    <div class="container">
        <div class="card flex-row my-5 border-0 shadow rounded-3 overflow-hidden">
            <div class="card-body p-4 p-sm-5">
                <!-- Tarjeta de perfil -->
                <div class="card text-bg-light mb-3 mx-auto" style="max-width: 700px;">
                    <div class="card-header"><?php echo "$nombre_rol" ?></div>
                    <div class="row g-0">
                        <div class="col-md-4 d-flex align-items-center my-3">
                            <span class="fa-stack fa-4x flex-fill">
                                <i class="fa-solid fa-circle fa-stack-2x"></i>
                                <i class="fa-solid fa-user fa-stack-1x fa-inverse"></i>
                            </span>
                        </div>
                        <div class="col-md-8">
                            <div class="card-body h-100 d-flex flex-column">
                                <h5 class="card-title"><?php echo "$nombre_completo" ?></h5>
                                <span class="card-text"><?php echo "$empleado->codigo_usuario" ?></span><br>
                                <div class="mt-auto">
                                    <p class="card-text text-end"><small class="text-muted">
                                            <?php echo "Fecha de registro: $empleado->fecha_registro" ?>
                                        </small></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <!-- Formulario de Datos personales -->
                <h2 class="card-title text-center mb-5 fw-light">Datos personales</h2>
                <form class="row g-3" method="post" action="edtPerfilController.php" id="frm-editar-perfil" novalidate>
                    <!-- Input hidden para el número id -->
                    <input type="hidden" class="form-control" id="txtNro" name="nro-empleado" readonly
                        value='<?php echo "$empleado->nro_empleado" ?>'>
                    <!-- Input Nombre -->
                    <div class="form-floating col-md-6 mb-1">
                        <input type="text" class="form-control" id="txtNombre" name="nombre"
                            value='<?php echo "$empleado->nombre" ?>' pattern="([A-Za-z]|[ÁÉÍÓÚáéíóúÑñüÜ ]){3,30}"
                            required autofocus>
                        <label for="txtNombre">
                            <i class="fas fa-pencil fa-lg me-1 fa-fw"></i>Nombre
                        </label>
                        <div class="invalid-feedback">
                            Introduzca un Nombre válido, entre 3-30 caracteres alfabéticos o espacio.
                        </div>
                    </div>
                    <!-- Input Apellido -->
                    <div class="form-floating col-md-6 mb-1">
                        <input type="text" class="form-control" id="txtApellido" name="apellido"
                            value='<?php echo "$empleado->apellido" ?>' pattern="([A-Za-z]|[ÁÉÍÓÚáéíóúÑñüÜ ]){3,30}"
                            required autofocus>
                        <label for="txtApellido">
                            <i class="fas fa-pencil fa-lg me-1 fa-fw"></i>Apellido
                        </label>
                        <div class="invalid-feedback">
                            Introduzca un Apellido válido, entre 3-30 caracteres alfabéticos o espacio.
                        </div>
                    </div>
                    <!-- Input Email -->
                    <div class="form-floating col-md-6 mb-1">
                        <input type="email" class="form-control" id="txtEmail" name="email"
                            value='<?php echo "$empleado->email" ?>' pattern="[a-z0-9._%+\-]+@[a-z0-9.\-]+\.[a-z]{2,}$"
                            maxlength="80" required autofocus>
                        <label for="txtEmail">
                            <i class="fas fa-at fa-lg me-1 fa-fw"></i>Correo electrónico
                        </label>
                        <div class="invalid-feedback">
                            Introduzca un e-mail válido, de máximo 80 caracteres.
                        </div>
                    </div>

                    <!-- Botón Guardar -->
                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-success btn-lg px-5 py-2" id="btn-editar-perfil">GUARDAR
                            CAMBIOS</button>
                    </div>
                </form>

                <hr class="my-4">

                <!-- Formulario de Contraseña -->
                <h2 class="card-title text-center mb-5 fw-light">Cambio de Contraseña</h2>
                <form class="row g-3" method="post" action="edtClaveController.php" id="frm-cambiar-clave" novalidate>
                    <!-- Input hidden para el número id -->
                    <input type="hidden" class="form-control" id="txtNro" name="nro-empleado" readonly
                        value='<?php echo "$empleado->nro_empleado" ?>'>
                    <!-- Input hidden para el código de usuario -->
                    <input type="hidden" class="form-control" id="txtUsuario" name="codigo-usuario" readonly
                        value='<?php echo "$empleado->codigo_usuario" ?>' autocomplete="username">
                    <!-- Input Contraseña -->
                    <div class="form-floating col-md-5 mb-1">
                        <input type="password" class="form-control" id="txtClaveAntigua" name="clave-antigua"
                            placeholder="" required autofocus>
                        <label for="txtClaveAntigua"> <i class="fas fa-lock fa-lg me-1 fa-fw ms-2"></i>Contraseña actual
                        </label>
                        <div class="invalid-feedback">
                            Falta contraseña.
                        </div>
                    </div>
                    <div class="col-md-1">
                        <i id="toggleClave" class="fas fa-eye-slash fa-2x mt-3" style="cursor: pointer"></i>
                    </div>

                    <!-- Input Cambiar Contraseña -->
                    <div class="form-floating col-md-5 mb-1">
                        <input type="password" class="form-control" id="txtClaveNueva" name="clave-nueva" placeholder=""
                            pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,30}" required autofocus
                            autocomplete="new-password">
                        <label for="txtClaveNueva"> <i class="fas fa-lock fa-lg me-1 fa-fw ms-2"></i>Contraseña nueva
                        </label>
                        <div class="invalid-feedback">
                            Introduzca una Contraseña válida, de entre 8-30 caracteres. Debe contener al
                            menos un dígito, una letra mayúscula y una letra minúscula.
                        </div>
                    </div>
                    <div class="col-md-1">
                        <i id="toggleClave2" class="fas fa-eye-slash fa-2x mt-3" style="cursor: pointer"></i>
                    </div>

                    <!-- Botón Cambiar Contraseña -->
                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-success btn-lg px-5 py-2" id="btn-cambiar-clave">CAMBIAR
                            CONTRASEÑA</button>
                    </div>
                </form>

                <hr class="my-4">

                <!-- Botón para volver al Portal -->
                <div class="col-12 text-center">
                    <a href="../../" class="btn btn-danger btn-lg px-5 py-2">VOLVER</a>
                </div>

            </div>
        </div>
    </div>

    <!--JQuery JS-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <!--Bootstrap JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
        crossorigin="anonymous"></script>
    <!--Fontawesome JS-->
    <script src="https://kit.fontawesome.com/1da5200486.js" crossorigin="anonymous"></script>
    <!--SweetAlert JS-->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.1/dist/sweetalert2.all.min.js"></script>

    <!-- Mostrar mensaje -->
    <?php
    function msg($titulo, $texto, $icono)
    {
        echo "
        <script>
            Swal.fire({
                title: '$titulo',
                text: '$texto',
                icon: '$icono'
            }); 
        </script>";
    }
    if (isset($_SESSION["mensaje"])) {
        switch ($_SESSION["mensaje"]) {
            case 1:
                msg("Operación exitosa", "Se han cambiado los datos personales de su perfil", "success");
                break;
            case 2:
                msg("Operación exitosa", "Se ha cambiado su contraseña exitosamente", "success");
                break;
            case -1:
                msg("Operación fallida", "No se pudo cambiar sus datos personales", "error");
                break;
            case -2:
                msg("Operación fallida", "La contraseña actual no es la correcta", "error");
                break;
            case -3:
                msg("Operación fallida", "No se pudo cambiar la contraseña", "error");
                break;
            case -10:
                msg("Operación fallida", "Hubo un error", "error");
                break;
        }
    }

    // Eliminar mensaje de la sesión
    unset($_SESSION["mensaje"]);
    ?>

    <!-- Visibilidad de las contraseñas -->
    <script>
        // Input Contraseña
        $(document).on("click", "#toggleClave", function () {
            // Cambiar el tipo de input (text <=> password)
            $("#txtClaveAntigua").attr('type') === 'password' ?
                $("#txtClaveAntigua").attr('type', 'text') :
                $("#txtClaveAntigua").attr('type', 'password');
            // Cambiar el ícono (eye <=> eyeslash)
            $("#toggleClave").attr('class') === 'fas fa-eye-slash fa-2x mt-3' ?
                $("#toggleClave").attr('class', 'fas fa-eye fa-2x mt-3') :
                $("#toggleClave").attr('class', 'fas fa-eye-slash fa-2x mt-3');

        });

        // Input Confirmar Contraseña
        $(document).on("click", "#toggleClave2", function () {
            // Cambiar el tipo de input (text <=> password)
            $("#txtClaveNueva").attr('type') === 'password' ?
                $("#txtClaveNueva").attr('type', 'text') :
                $("#txtClaveNueva").attr('type', 'password');
            // Cambiar el ícono (eye <=> eyeslash)
            $("#toggleClave2").attr('class') === 'fas fa-eye-slash fa-2x mt-3' ?
                $("#toggleClave2").attr('class', 'fas fa-eye fa-2x mt-3') :
                $("#toggleClave2").attr('class', 'fas fa-eye-slash fa-2x mt-3');

        });
    </script>

    <!-- Validaciones -->
    <script>
        // Desactivar el submit del formulario si presenta inputs inválidos
        let form1 = document.getElementById("frm-editar-perfil");
        form1.addEventListener("submit", event => {
            if (!form1.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form1.classList.add('was-validated');
        }, false);

        // Desactivar el submit del formulario si presenta inputs inválidos
        let form2 = document.getElementById("frm-cambiar-clave");
        form2.addEventListener("submit", event => {
            if (!form2.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form2.classList.add('was-validated');
        }, false);
    </script>

</body>

</html>