<?php
// Iniciar o reanudar sesión
session_start();

// Restringir acceso si el id_rol está en sesión (no debería porque esta página solo se muestra a visitantes)
if (isset($_SESSION["id_rol"])) {
    error_log("Intento de registro sin ser visitante!");
    header("location: ../");
}

// Acceder a la clase Conectar
include "../cls_conectar/cls_Conectar.php";
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

        /* Imagen de fondo en la seccion izquierda */
        .card-img-left {
            width: 40%;
            background: scroll center url('../multimedia/imagenes/fondo-signup.jpg');
            background-size: cover;
        }

        /* Botones */
        .btn-login {
            font-size: 0.9rem;
            letter-spacing: 0.05rem;
            padding: 0.75rem 1rem;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-lg-10 col-xl-9 mx-auto">
                <div class="card flex-row my-5 border-0 shadow rounded-3 overflow-hidden">
                    <!-- Sección izquierda -->
                    <div class="card-img-left d-none d-md-flex">
                        <div class="container">
                            <img class="rounded m-5 p-3" src='../multimedia/imagenes/logo.png'
                                style="background-color: #211F1D; width:75%">
                        </div>
                    </div>
                    <!-- Sección derecha: Formulario -->
                    <div class="card-body p-4 p-sm-5" style="width: 60%;">
                        <h2 class="card-title text-center mb-5 fw-light">Inicio de Sesión</h2>
                        <form id="form-validacion" method="post" action="loginController.php" novalidate>
                            
                            <!-- Campo Usuario -->
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="txtUsuario" name="usuario" placeholder=""
                                    required autofocus autocomplete="username" 
                                    <?php 
                                    // Si la página ha sido redirigida (credenciales erróneas)
                                    if (isset($_SESSION["login_usuario"])) {
                                        // Completar con el usuario colocado anteriormente
                                        echo "value = '".$_SESSION['login_usuario']."'";
                                        // Eliminar variable de la sesión
                                        unset($_SESSION['login_usuario']);
                                    }
                                    ?>
                                >
                                <label for="txtUsuario">
                                    <i class="fas fa-user fa-lg me-1 fa-fw"></i>Código de Usuario
                                </label>
                                <div class="invalid-feedback">
                                    Falta Código de Usuario.
                                </div>
                            </div>
                            
                            <!-- Campo Contraseña -->
                            <div class="row">
                                <div class="form-floating col-10 mb-3">
                                    <input type="password" class="form-control" id="txtClave" name="clave" placeholder=""
                                        required autofocus autocomplete="current-password"
                                        <?php 
                                        // Si la página ha sido redirigida (credenciales erróneas)
                                        if (isset($_SESSION["login_clave"])) {
                                            // Completar con la clave colocada anteriormente
                                            echo "value = '".$_SESSION['login_clave']."'";
                                            // Eliminar variable de la sesión
                                            unset($_SESSION['login_clave']);
                                        }
                                        ?>
                                    >
                                    <label for="txtClave">
                                        <i class="fas fa-lock fa-lg me-1 fa-fw ms-2"></i>Contraseña
                                    </label>
                                    <div class="invalid-feedback">
                                        Falta Contraseña.
                                    </div>
                                </div>
                                <div class="col-2">
                                    <i id="toggleClave" class="fas fa-eye-slash fa-2x mt-3" style="cursor: pointer"></i>
                                </div>
                            </div>

                            <hr>

                            <!-- Botón de login -->
                            <div class="d-grid mb-2">
                                <button class="btn btn-lg btn-warning btn-login fw-bold text-uppercase"
                                    type="submit">Ingresar</button>
                            </div>

                            <!-- Link a página de registro -->
                            <a class="d-block text-center mt-2 small" href="../registro/">¿Aún no tienes una cuenta? Regístrate</a>

                            <!-- Link al portal principal -->
                            <div class="d-grid mb-2 mt-4">
                                <a class="btn btn-lg btn-danger btn-login fw-bold text-uppercase" href="../">Salir</a>
                            </div>

                        </form>
                    </div>
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
    if (isset($_SESSION["mensaje"]) && $_SESSION["mensaje"] == -1) {
        echo '
            <script>
            Swal.fire({
                title: "Credenciales incorrectas",
                text: "Código de usuario o Contraseña incorrectos.",
                icon: "error"
            });
            </script>
            ';
    }

    // Eliminar variable mensaje de la sesión
    unset($_SESSION["mensaje"]);
    ?>

    <!-- Visibilidad de las contraseñas -->
    <script>
        // Input Contraseña
        let clv = document.getElementById("txtClave");
        let toggle = document.getElementById("toggleClave");
        toggle.addEventListener('click', function (e) {
            // Cambiar el tipo de input (text <=> password)
            clv.getAttribute('type') === 'password' ?
                clv.setAttribute('type', 'text') :
                clv.setAttribute('type', 'password');
            // Cambiar el ícono (eye <=> eyeslash)
            toggle.getAttribute('class') === 'fas fa-eye-slash fa-2x mt-3' ?
                toggle.setAttribute('class', 'fas fa-eye fa-2x mt-3') :
                toggle.setAttribute('class', 'fas fa-eye-slash fa-2x mt-3');

        });
    </script>

    <!-- Validaciones -->
    <script>
        // Desactivar el submit del formulario si presenta inputs inválidos
        let form = document.getElementById("form-validacion");
        form.addEventListener("submit", event => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    </script>
</body>

</html>