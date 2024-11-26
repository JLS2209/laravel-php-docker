<?php
// Iniciar o reanudar sesión
session_start();

// Restringir acceso si el id_rol no corresponde a cliente (2)
if (!(isset($_SESSION["id_rol"]) && $_SESSION["id_rol"] == 2)) {
    error_log("Intento de acceso sin credenciales adecuadas de cliente!");
    header("location: ../../");
}

// Se recuperan las siguientes variables para llenar el formulario de Mensaje
// -- Recupera el número de cliente/empleado de la sesión (por defecto -> -1)
$nro = isset($_SESSION["nro"]) ? $_SESSION["nro"] : -1;

// Acceder a la clase Conectar
include "../../cls_conectar/cls_Conectar.php";

// Acceder a la clase Cliente y su controlador
include "../../modelo/cls_Ubicacion.php";
include "../../modelo/cls_Cliente.php";
include "../../controlador/ctrl_Cliente.php";
$ctrlCliente = new ControladorCliente();
$cliente = $ctrlCliente->show($nro);

// Acceder a la clase Mensaje y su controlador
include "../../modelo/cls_Mensaje.php";
include "../../controlador/ctrl_Mensaje.php";
$ctrl = new ControladorMensaje();
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
        /*Estilos de los nav-pills en la barra de navegación*/
        .nav-pills .nav-link {
            color: #F7BA00;
        }

        .nav-pills .nav-link.active {
            color: black;
            background-color: #F7BA00;
            font-weight: bold;
        }

        /* Invierte el color de los controles del audio (negro en Chrome)*/
        audio {
            filter: invert(90%);
        }
    </style>
</head>

<body>
    <!-- Incluir Barra de menús de navegación -->
    <?php include "../../templates/nav.php"; ?>

    <!-- Cuerpo de la página -->
    <div class="container-fluid"
        style="background-image: url('../../multimedia/imagenes/fondo.jpg'); background-size: 100%;">
        <div class="container py-4" style="background-color: rgba(0, 0, 0, 0.8); color: wheat;">
            <!-- Sección superior -->
            <div class="row g-2 mx-4">
                <div class="col-md-6">
                    <h1 class="text-center ">Nuestras redes sociales</h1>
                    <div class="row g-5 text-center mt-auto">
                        <!-- Facebook -->
                        <div class="col-md-6" style="color: black;">
                            <div class="card p-2 h-100" style="background-color: blanchedalmond;">
                                <div class="card-body">
                                    <i class="fa-brands fa-facebook fa-4x mb-4" style="color: darkblue;"></i>
                                    <p>Denos un like y únase a la comunidad.</p>
                                </div>
                                <div class="card-footer border-0" style="background-color: blanchedalmond;">
                                    <a href="https://www.facebook.com/" target="_blank"
                                        class="btn btn-warning fw-bold">ME
                                        GUSTA</a>
                                </div>
                            </div>
                        </div>
                        <!-- Twitter -->
                        <div class="col-md-6" style="color: black;">
                            <div class="card p-2 h-100" style="background-color: blanchedalmond;">
                                <div class="card-body">
                                    <i class="fa-brands fa-x-twitter fa-4x mb-4" style="color: black;"></i>
                                    <p>Síganos para más platos y consejos culinarios.</p>
                                </div>
                                <div class="card-footer border-0" style="background-color: blanchedalmond;">
                                    <a href="https://x.com/" target="_blank" class="btn btn-warning fw-bold">SEGUIR</a>
                                </div>
                            </div>
                        </div>
                        <!-- Instagram -->
                        <div class="col-md-6" style="color: black;">
                            <div class="card p-2 h-100" style="background-color: blanchedalmond;">
                                <div class="card-body">
                                    <i class="fa-brands fa-instagram fa-4x mb-4" style="color: orange;"></i>
                                    <p> Síganos para ver fotos de nuestros platos y deliciosas recetas.</p>
                                </div>
                                <div class="card-footer border-0" style="background-color: blanchedalmond;">
                                    <a href="https://www.instagram.com/" target="_blank"
                                        class="btn btn-warning fw-bold">SEGUIR</a>
                                </div>
                            </div>
                        </div>
                        <!-- Youtube -->
                        <div class="col-md-6" style="color: black;">
                            <div class="card p-2 h-100" style="background-color: blanchedalmond;">
                                <div class="card-body">
                                    <i class="fa-brands fa-youtube fa-4x mb-4" style="color: brown;"></i>
                                    <p> Vea nuestros anuncios, stories y promociones en video.</p>
                                </div>
                                <div class="card-footer border-0" style="background-color: blanchedalmond;">
                                    <a href="https://www.youtube.com/" target="_blank"
                                        class="btn btn-warning fw-bold">SUSCRÍBETE</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 text-center">
                    <h1 class="text-center mb-4">Ubícanos en</h1>
                    <!-- Google Maps -->
                    <iframe class="media rounded-4 mt-4"
                        src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d7806.623916028263!2d-77.070192!3d-11.95289!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x9105d1d877f532d7%3A0x8db19fe8e1f40feb!2sUniversidad%20Tecnol%C3%B3gica%20del%20Per%C3%BA!5e0!3m2!1ses-419!2spe!4v1726113492352!5m2!1ses-419!2spe"
                        style="border:0; width:80%; height: 400px" allowfullscreen="" loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                    <!-- Dirección -->
                    <div class="alert mx-auto mt-2" style="background-color:wheat; color:black; width: 45%">
                        <span>Panamericana Norte, <br> Av. Alfredo Mendiola 6377, <br> Los Olivos 15306</span>
                    </div>
                    <!-- Horario -->
                    <div class="alert mx-auto" style="background-color:peru; color:black; width: 60%">
                        <span><b>HORARIO DE ATENCIÓN</b> <br> Lunes a sábado <br> 1:00 p.m. a 10:00 p.m.</span>
                    </div>
                </div>
            </div>

            <hr style="border: wheat solid 2px; opacity: 1.0; margin: 30px auto; width: 75%">

            <h1 class="text-center mb-4">Queremos saber tu opinión</h1><br>
            <div class="row g-2 mx-4">
                <!-- Formulario -->
                <div class="col-md-8 row justify-content-center">
                    <div class="card text-bg-light" style="color: black; width: 90%">
                        <div class="card-body">
                            <h3 class="card-title mb-4 text-center">Datos de contacto</h3>
                            <form class="row g-3" id="form-validacion" method="post" action="controller.php" novalidate>
                                <!-- Input hidden para el Número ID de Cliente -->
                                <input type="hidden" id="txtNroCliente" name="nro-cliente" value="<?php echo $nro ?>">
                                <!-- Input Nombre -->
                                <div class="form-floating col-md-6 mb-3">
                                    <input type="text" class="form-control" id="txtNombre" readonly
                                        value="<?php echo $cliente->nombre ?>">
                                    <label for="txtNombre" class="ms-2">
                                        Nombre
                                    </label>
                                    <div class="invalid-feedback">
                                        Introduzca un Nombre válido, entre 3-30 caracteres alfabéticos o espacio.
                                    </div>
                                </div>
                                <!-- Input Apellido -->
                                <div class="form-floating col-md-6 mb-3">
                                    <input type="text" class="form-control" id="txtApellido" readonly
                                        value="<?php echo $cliente->apellido ?>">
                                    <label for="txtApellido" class="ms-2">
                                        Apellido
                                    </label>
                                    <div class="invalid-feedback">
                                        Introduzca un Apellido válido, entre 3-30 caracteres alfabéticos o espacio.
                                    </div>
                                </div>
                                <!-- Input Email -->
                                <div class="form-floating col-md-6 mb-3">
                                    <input type="email" class="form-control" id="txtEmail" readonly
                                        value="<?php echo $cliente->email ?>">
                                    <label for="txtEmail" class="ms-2">
                                        Correo electrónico
                                    </label>
                                    <div class="invalid-feedback">
                                        Introduzca un e-mail válido, de máximo 80 caracteres.
                                    </div>
                                </div>
                                <!-- Input Teléfono -->
                                <div class="form-floating col-md-6 mb-3">
                                    <input type="tel" class="form-control" id="txtTelefono" readonly
                                        value="<?php echo $cliente->telefono ?>">
                                    <label for="txtTelefono" class="ms-2">
                                        Teléfono
                                    </label>
                                    <div class="invalid-feedback">
                                        Introduzca un número de teléfono válido, sin espacios entre los dígitos.
                                    </div>
                                </div>
                                <!-- Input Asunto -->
                                <div class="form-floating col-md-12 mb-3">
                                    <select class="form-select" id="cbxAsunto" name="asunto" required>
                                        <option value="">Seleccione...</option>
                                        <option>Sugerencia</option>
                                        <option>Reclamo</option>
                                        <option>Ofrezco un producto o servicio</option>
                                        <option>Otro asunto</option>
                                    </select>
                                    <label for="cbxAsunto" class="ms-2">
                                        Asunto
                                    </label>
                                    <div class="invalid-feedback">
                                        Seleccione un asunto
                                    </div>
                                </div>
                                <!-- Input Contenido -->
                                <div class="form-floating col-md-12 mb-3">
                                    <textarea class="form-control" name="contenido" id="txaContenido" placeholder=''
                                        style="height:200px;" required></textarea>
                                    <label for="txaContenido" class="ms-2">
                                        Contenido
                                    </label>
                                    <div class="invalid-feedback">
                                        El contenido no puede estar vacío.
                                    </div>
                                </div>

                                <!-- Checkbox -->
                                <div class="form-floating mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="chkPolitica" required>
                                        <label class="form-check-label" for="chkPolitica">
                                        He leído y acepto los <a href="../terms.php" target="_blank">términos y condiciones.</a>
                                        </label>
                                        <div class="invalid-feedback">
                                            Debe aceptar los términos de la política de privacidad.
                                        </div>
                                    </div>
                                </div>

                                <!-- Botones -->
                                <div class="col-md-6 text-center">
                                    <button class="btn btn-lg btn-success fw-bold px-4" type="submit">ENVIAR</button>
                                </div>
                                <div class="col-md-6 text-center">
                                    <button class="btn btn-lg btn-warning fw-bold px-4" type="button"
                                        id="btn-limpiar">LIMPIAR</button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>

                <!-- Sección derecha -->
                <div class="col-md-4 text-center align-self-center">
                    <img src="../../multimedia/imagenes/contacto.png" class="rounded-4 w-100">
                    <div class="row align-items-center justify-content-center mt-4">
                        <span class="fa-stack fa-1x col-auto" style="color:brown">
                            <i class="fa-solid fa-circle fa-stack-2x"></i>
                            <i class="fa-solid fa-envelope fa-stack-1x fa-inverse"></i>
                        </span>
                        <h6 class="col-auto">correo@gmail.com</h6>
                    </div>

                    <div class="row align-items-center justify-content-center">
                        <span class="fa-stack fa-1x col-auto my-4" style="color:brown">
                            <i class="fa-solid fa-circle fa-stack-2x"></i>
                            <i class="fa-solid fa-phone fa-stack-1x fa-inverse"></i>
                        </span>
                        <h6 class="col-auto">998 776 554</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Incluir Pie de página -->
    <?php include "../../templates/footer.php"; ?>

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
                msg("Operación exitosa", "Mensaje enviado", "success");
                break;
            case -1:
                msg("Operación fallida", "No se pudo enviar el mensaje", "error");
                break;
            case -10:
                msg("Operación fallida", "Hubo un error", "error");
                break;
        }
    }

    // Eliminar mensaje de la sesión
    unset($_SESSION["mensaje"]);
    ?>

    <!-- Limpiar cajas -->
    <script>
        document.getElementById("btn-limpiar").addEventListener("click", function () {
            document.getElementById("txaContenido").value = '';
            document.getElementById("cbxAsunto").value = '';
            document.getElementById("chkPolitica").checked = false;
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