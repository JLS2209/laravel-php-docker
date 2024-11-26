<?php
// Acceder al modelo de clase Pedido
include "../../modelo/cls_Pedido.php";

// Iniciar o reanudar sesión
session_start();

// Restringir acceso si el id_rol no corresponde a cliente (2)
if (!(isset($_SESSION["id_rol"]) && $_SESSION["id_rol"] == 2)) {
    error_log("Intento de acceso sin credenciales adecuadas de cliente!");
    header("location: ../../");
}

// Acceder a la clase Conectar
include "../../cls_conectar/cls_Conectar.php";

// Acceder a la clase Cliente y su controlador
include "../../modelo/cls_Ubicacion.php";
include "../../modelo/cls_Cliente.php";
include "../../controlador/ctrl_Cliente.php";

// Invocar búsqueda de cliente para recuperar sus datos
$nro = isset($_SESSION["nro"]) ? $_SESSION["nro"] : -1;
$ctrlCliente = new ControladorCliente();
$cliente = $ctrlCliente->show($nro);

// Cargar data a partir de $_SESSION["pedido"] y $_SESSION["tarjeta"] (si existen)
$opcion = isset($_SESSION["pedido"]) ? $_SESSION["pedido"]->opcion_entrega : "";
$pago = isset($_SESSION["pedido"]) ? $_SESSION["pedido"]->metodo_pago : "";
$nro_tarjeta = isset($_SESSION["tarjeta"]) ? $_SESSION["tarjeta"]["numero-tarjeta"] : "";
$cvv = isset($_SESSION["tarjeta"]) ? $_SESSION["tarjeta"]["cvv"] : "";
$fecha_venc = isset($_SESSION["tarjeta"]) ? $_SESSION["tarjeta"]["fecha-vencimiento"] : "";
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

        /* Bootstrap validator */
        .help-block {
            color: red;
        }

        .form-group.has-error .form-control-label {
            color: red;
        }

        .form-group.has-error .form-control {
            border: 1px solid red;
            box-shadow: 0 0 0 0.2rem rgba(250, 16, 0, 0.18);
        }

        .form-group.has-error .form-select {
            border: 1px solid red;
            box-shadow: 0 0 0 0.2rem rgba(250, 16, 0, 0.18);
        }
    </style>
</head>

<body>
    <!-- Incluir Barra de menús de navegación -->
    <?php include "../../templates/nav.php"; ?>

    <!-- Cuerpo de la página -->
    <div class="container">
        <div class="card flex-row my-5 border-0 shadow rounded-3 overflow-hidden">
            <div class="card-body p-4 p-sm-5">

                <!-- Formulario -->
                <h2 class="card-title text-center mb-5 fw-light">Empieza tu pedido</h2>
                <form class="row g-3" method="post" action="controller.php" id="frm-pedido" novalidate>
                    <!-- Nombres -->
                    <div class="form-floating col-md-6 mb-1">
                        <input type="text" class="form-control" id="txtNombre"
                            value='<?php echo "$cliente->nombre $cliente->apellido" ?>' disabled>
                        <label class="ps-4" for="txtNombre">Cliente</label>
                    </div>
                    <!-- Teléfono -->
                    <div class="form-floating col-md-6 mb-1">
                        <input type="tel" class="form-control" id="txtTelefono"
                            value='<?php echo "$cliente->telefono" ?>' disabled>
                        <label class="ps-4" for="txtTelefono">Teléfono celular</label>
                    </div>
                    <!-- Input Entrega -->
                    <div class="form-floating col-md-6 mb-1">
                        <select class="form-select" id="cbxEntrega" name="opcion-entrega" required>
                            <option selected value="" disabled>Seleccione...</option>
                            <option value="1">Delivery</option>
                            <option value="2">Pickup en tienda</option>
                        </select>
                        <label class="ps-4" for="cbxEntrega"> Opción de entrega </label>
                        <div class="invalid-feedback">
                            Debe seleccionar una Opción de entrega.
                        </div>
                    </div>
                    <!-- Input Pago -->
                    <div class="form-floating col-md-6 mb-1">
                        <select class="form-select" id="cbxPago" name="metodo-pago" required>
                            <option selected value="" disabled>Seleccione...</option>
                            <option value="1">Tarjeta</option>
                            <option value="2">Efectivo</option>
                        </select>
                        <label class="ps-4" for="cbxPago"> Método de Pago </label>
                        <div class="invalid-feedback">
                            Debe seleccionar un Método de Pago.
                        </div>
                    </div>

                    <hr>

                    <!-- Datos de Tarjeta de crédito -->
                    <div class="row g-3" id="caja-tarjeta">
                        <h4 class="card-title fw-light">Datos de tu tarjeta (solo si decides pagar con tarjeta)</h4>
                        <!-- Input Tarjeta -->
                        <div class="form-floating col-md-6 mb-1">
                            <input type="text" class="form-control" id="txtTarjeta" name="numero-tarjeta"
                                value='<?php echo "$nro_tarjeta" ?>'>
                            <label class="ps-4" for="txtTarjeta"> Número de Tarjeta </label>
                        </div>
                        <!-- Input CVV -->
                        <div class="form-floating col-md-2 mb-1">
                            <input type="text" class="form-control" id="txtCVV" name="cvv" value='<?php echo "$cvv" ?>'>
                            <label class="ps-4" for="txtCVV"> CVV </label>
                        </div>
                        <!-- Input Fecha de vencimiento -->
                        <div class="form-floating col-md-4 mb-1">
                            <input type="text" class="form-control" id="txtFecha" name="fecha-vencimiento"
                                value='<?php echo "$fecha_venc" ?>'>
                            <label class="ps-4" for="txtFecha"> Fecha de vencimiento </label>
                        </div>
                        <hr>
                    </div>

                    <!-- Botón Guardar -->
                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-success btn-lg px-5 py-2">GUARDAR Y SEGUIR</button>
                    </div>
                </form>
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
        error_log($_SESSION["mensaje"]);
        switch ($_SESSION["mensaje"]) {
            case 1:
                msg("Hemos recibido su pedido!", "Para revisar el estado de su pedido, revise la sección \"Mis Pedidos\" en \"Mi Perfil\"", "success");
                break;
            case 2:
                msg("Hemos descartado su pedido.", "Vuelva pronto!", "success");
                break;
            case -1:
                msg("Algo salió mal...", "No hemos podido registrar su pedido. Por favor, vuelva a intentarlo.", "error");
                break;
            case -10:
                msg("Operación fallida", "Hubo un error", "error");
                break;
        }
    }

    // Eliminar mensaje de la sesión
    unset($_SESSION["mensaje"]);
    ?>

    <!-- Cargar datos en los combobox de entrega y pago -->
    <script>
        $('#cbxEntrega').val('<?php echo $opcion; ?>');
        $('#cbxPago').val('<?php echo $pago; ?>');
    </script>

    <!--JScript de Bootstrap Validator-->
    <script
        src="//cdnjs.cloudflare.com/ajax/libs/jquery.bootstrapvalidator/0.5.1/js/bootstrapValidator.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.30.1/moment-with-locales.min.js"
        integrity="sha512-4F1cxYdMiAW98oomSLaygEwmCnIP38pb4Kx70yQYqRwLVCs3DbRumfBq82T08g/4LJ/smbFGFpmeFlQgoDccgg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!-- Validaciones -->
    <script>
        $(document).ready(function () {
            $('#frm-pedido').bootstrapValidator({
                feedbackIcons: {
                    valid: 'glyphicon glyphicon-ok',
                    invalid: 'glyphicon glyphicon-remove',
                    validating: 'glyphicon glyphicon-refresh'
                },
                fields: {
                    'opcion-entrega': {
                        validators: {
                            notEmpty: {
                                message: ' '
                            }
                        }
                    },
                    'metodo-pago': {
                        validators: {
                            notEmpty: {
                                message: ' '
                            }
                        }
                    },
                    'numero-tarjeta': {
                        validators: {
                            notEmpty: {
                                message: 'Debe escribir un número de tarjeta.'
                            },
                            creditCard: {
                                message: 'Debe escribir un número de tarjeta válido.'
                            }
                        }
                    },
                    'cvv': {
                        validators: {
                            notEmpty: {
                                message: 'Debe escribir un CVV.'
                            },
                            cvv: {
                                creditCardField: 'numero-tarjeta',
                                message: 'Debe escribir un CVV válido.'
                            }
                        }
                    },
                    'fecha-vencimiento': {
                        validators: {
                            notEmpty: {
                                message: ' '
                            },
                            callback: {
                                message: 'Debe escribir la fecha como aparece en su tarjeta, p.e. 01/24.',
                                callback: function (value, validator) {
                                    var m = new moment(value, 'MM/YY', true);
                                    return m.isValid();
                                }
                            }
                        }
                    }
                }
            })

            // Desactivar el submit del formulario si presenta inputs inválidos
            let form = document.getElementById("frm-pedido");
            form.addEventListener("submit", event => {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    </script>

    <!-- Asignar evento change y load al cbx de método de pago -->
    <script>
        // Función que activa o desactiva la caja de tarjeta según el combo de pago
        const activarInputsTarjeta = function () {
            const isTarjeta = $('#cbxPago').val() == 1;
            if (isTarjeta) {
                $("#caja-tarjeta").show();
            } else {
                $("#caja-tarjeta").hide(); // Bootstrap validator no valida campos escondidos
            }
        }
        // Llamar al cargar la página
        activarInputsTarjeta();
        // Eventos
        $(document).on("change", '#cbxPago', activarInputsTarjeta);
        $(document).on("click", activarInputsTarjeta);
    </script>
</body>

</html>