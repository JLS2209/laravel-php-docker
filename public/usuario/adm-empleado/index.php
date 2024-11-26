<?php
// Iniciar o reanudar sesión
session_start();

// Restringir acceso si el id_rol no corresponde a empleado administrador (4)
if (!(isset($_SESSION["id_rol"]) && $_SESSION["id_rol"] == 4)) {
    error_log("Intento de acceso sin credenciales adecuadas de administrador!");
    header("location: ../../");
}

// Acceder a la clase Conectar
include "../../cls_conectar/cls_Conectar.php";

// Acceder a la clase Empleado y su controlador
include "../../modelo/cls_Empleado.php";
include "../../controlador/ctrl_Empleado.php";
$ctrl = new ControladorEmpleado();
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
    <div class="container-fluid"
        style="background-image: url('../../multimedia/imagenes/fondo.jpg'); background-size: 100%;">
        <div class="container py-4" style="background-color: rgba(0, 0, 0, 0.8); color: wheat;">

            <h1 class="text-center mb-4">REGISTRO DE EMPLEADOS</h1>

            <hr>

            <div class="row justify-content-between g-5 px-4 mb-4">
                <!-- Filtro de consulta por rol -->
                <div class="col-md-4">
                    <label for="cbx-filtro-rol" class="form-label fs-5">Seleccione un rol de empleado:</label>
                    <select class=" form-select" id="cbx-filtro-rol">
                        <option value="0">Todos</option>
                        <option value="3">Atención al cliente</option>
                        <option value="4">Administrador</option>
                    </select>
                    <button id="btn-filtro-rol" class="btn btn-success w-100 mt-3">Consultar</button>
                </div>
                <!-- Filtro de consulta por nombre -->
                <div class="col-md-4">
                    <label for="txt-filtro-nombre" class="form-label fs-5">O ingrese un nombre:</label>
                    <input type="text" class="form-control" id="txt-filtro-nombre"
                        placeholder="Nombre y/o apellido del empleado">
                    <button id="btn-filtro-nombre" class="btn btn-success w-100 mt-3">Consultar</button>
                </div>
                <!-- Botón Nuevo Empleado -->
                <div class="col-md-3 d-flex align-items-center">
                    <button class="btn btn-primary btn-lg btn-nuevo w-100 h-50" type="button" data-bs-toggle="modal"
                        data-bs-target="#modal-objetivo">Nuevo Empleado</button>
                </div>
            </div>

            <hr>

            <!-- Tabla de Listado -->
            <div class="table-responsive" id="tabla-empleados">
                <table class="table table-light table-striped mt-3">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col" class='ps-3'>Número ID</th>
                            <th scope="col">Nombres</th>
                            <th scope="col">E-mail</th>
                            <th scope="col">Rol</th>
                            <th scope="col">Fecha de registro</th>
                            <th scope="col"></th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>

            <!--Modal-->
            <div class="modal fade" id="modal-objetivo" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog" style="color:black">
                    <div class="modal-content">
                        <!-- Cabecera del modal -->
                        <div class="modal-header" style="background-color: steelblue; color:white">
                            <h1 class="modal-title fs-5">Empleado</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <!-- Formulario -->
                        <div class="modal-body">
                            <form class="row g-3" method="post" action="controller.php" id="frm-insert-update">
                                <!-- Input hidden para el número id -->
                                <input type="hidden" class="form-control" name="nro-empleado" readonly id="txtNro"
                                    value="0">
                                <!-- Input Nombre -->
                                <div class="form-floating form-group mb-3">
                                    <input type="text" class="form-control" id="txtNombre" name="nombre">
                                    <label for="txtNombre">
                                        <i class="fas fa-pencil fa-lg me-1 fa-fw"></i>Nombre
                                    </label>
                                </div>
                                <!-- Input Apellido -->
                                <div class="form-floating form-group mb-3">
                                    <input type="text" class="form-control" id="txtApellido" name="apellido">
                                    <label for="txtApellido">
                                        <i class="fas fa-pencil fa-lg me-1 fa-fw"></i>Apellido
                                    </label>
                                </div>
                                <!-- Input Email -->
                                <div class="form-floating form-group mb-3">
                                    <input type="email" class="form-control" id="txtEmail" name="email">
                                    <label for="txtEmail">
                                        <i class="fas fa-at fa-lg me-1 fa-fw"></i>Correo electrónico
                                    </label>
                                </div>
                                <!-- Input Rol -->
                                <div class="form-floating form-group mb-3">
                                    <select class="form-select" id="cbxRol" name="id-rol">
                                        <option selected disabled value="">Seleccione...</option>
                                        <option value="3">Atención al cliente</option>
                                        <option value="4">Administrador</option>
                                    </select>
                                    <label for="cbxRol">
                                        <i class="fas fa-user fa-lg me-1 fa-fw"></i>Tipo de Empleado
                                    </label>
                                </div>
                                <!-- Input Usuario " -->
                                <div class="form-floating form-group">
                                    <input type="text" class="form-control" id="txtUsuario" name="usuario">
                                    <label for="txtUsuario">
                                        <i class="fas fa-user fa-lg me-1 fa-fw"></i>Código de Usuario
                                    </label>
                                </div>
                                <!-- Botón para generar usuario automáticamente -->
                                <div class="d-grid mb-2">
                                    <button class="btn btn-secondary fw-bold text-uppercase" type="button"
                                        id="btn-generar">Generar código de usuario</button>
                                </div>
                                <!-- Input Contraseña -->
                                <div class="form-floating form-group col-10 mb-3">
                                    <input type="password" class="form-control" id="txtClave" name="clave">
                                    <label for="txtClave">
                                        <i class="fas fa-lock fa-lg me-1 fa-fw"></i>Contraseña
                                    </label>
                                </div>
                                <div class="col-2">
                                    <i id="toggleClave" class="fas fa-eye-slash fa-2x mt-3" style="cursor: pointer"></i>
                                </div>

                                <!-- Input Confirmar Contraseña -->
                                <div class="form-floating form-group col-10 mb-3">
                                    <input type="password" class="form-control" id="txtClave2" name="clave2">
                                    <label for="txtClave2">
                                        <i class="fas fa-lock fa-lg me-1 fa-fw"></i>Confirmar Contraseña
                                    </label>
                                </div>
                                <div class="col-2">
                                    <i id="toggleClave2" class="fas fa-eye-slash fa-2x mt-3"
                                        style="cursor: pointer"></i>
                                </div>

                                <!-- Input artificial con el propósito de diferenciarlo de delete -->
                                <!-- Tipo de operacion CRUD -->
                                <input type="hidden" id="tipo-crud" name="tipo-crud" value="insert-update">

                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-success" id="btn-grabar">Grabar</button>
                                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulario artificial con el propósito de realizar delete -->
    <form action="controller.php" method="post" id="frm-eliminar">
        <!-- Argumento para el delete (codigo) -->
        <input type="hidden" id="txtNroEliminar" name="nro-empleado">
        <!-- Tipo de operacion CRUD -->
        <input type="hidden" name="tipo-crud" value="delete">
        <!-- La acción submit se hace mediante JQuery -->
    </form>

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
                msg("Operación exitosa", "Empleado eliminado", "success");
                break;
            case 2:
                msg("Operación exitosa", "Empleado agregado", "success");
                break;
            case 3:
                msg("Operación exitosa", "Rol de empleado actualizado", "success");
                break;
            case 0:
                msg("Operación fallida", "¡No puedes eliminarte a ti mismo(a)!", "error");
                break;
            case -1:
                msg("Operación fallida", "No se pudo eliminar el empleado", "error");
                break;
            case -2:
                msg("Operación fallida", "El Código de usuario ingresado ya existe. Debe intentar con otro código", "error");
                break;
            case -3:
                msg("Operación fallida", "No se pudo actualizar el rol de empleado", "error");
                break;
            case -4:
                msg("Operación fallida", "Hubo un error", "error");
                break;
        }
    }

    // Eliminar mensaje de la sesión
    unset($_SESSION["mensaje"]);
    ?>

    <!-- Asignar evento click al botón de filtro por rol-->
    <script>
        // Función
        const mostrarEmpleadosPorRol = function () {
            // Recuperar selección del combo
            let rol = $('#cbx-filtro-rol').val();

            // Limpiar el filtro alternativo
            $('#txt-filtro-nombre').val("");

            // Vaciar la tabla
            $("#tabla-empleados tbody").html("");

            // Consulta asíncrona
            $.post("json/list_empleado_by_rol.php",
                { rol: rol },
                function (response) {
                    $.each(response, function (index, item) {
                        // Llenar tabla
                        $("#tabla-empleados tbody").append(`${item.fila}`);
                    });
                });
        };

        // Llamar a la función al iniciar la página
        mostrarEmpleadosPorRol();

        // Evento
        $(document).on("click", "#btn-filtro-rol", mostrarEmpleadosPorRol);        
    </script>

    <!-- Asignar evento click al botón de filtro por nombre-->
    <script>
        $(document).on("click", "#btn-filtro-nombre", function () {
            // Recuperar input
            let nombre = $('#txt-filtro-nombre').val();

            // Limpiar el filtro alternativo
            $('#cbx-filtro-rol').val("0");

            // Vaciar la tabla
            $("#tabla-empleados tbody").html("");

            // Consulta asíncrona
            $.post("json/list_empleado_by_nombre.php",
                { nombre: nombre },
                function (response) {
                    $.each(response, function (index, item) {
                        // Llenar tabla
                        $("#tabla-empleados tbody").append(`${item.fila}`);
                    });
                });
        });        
    </script>

    <!-- Asignar evento click al botón Nuevo -->
    <script>
        // Botón nuevo
        $(document).on("click", ".btn-nuevo", function () {
            // Cambiar título del modal
            $('.modal-title').html('Nuevo Empleado');

            // Limpiar los inputs
            $("#txtNro").val("0");
            $("#txtNombre").val("");
            $("#txtApellido").val("");
            $("#txtEmail").val("");
            $("#txtUsuario").val("");
            $("#cbxRol").val("");
            $("#txtClave").val("");
            $("#txtClave2").val("");

            // Reactivar los inputs desactivados
            $("#txtNombre").attr('disabled', false);
            $("#txtApellido").attr('disabled', false);
            $("#txtEmail").attr('disabled', false);
            $("#txtUsuario").attr('disabled', false);
            $("#txtClave").attr('disabled', false);
            $("#txtClave2").attr('disabled', false);

            // Mostrar componentes escondidos
            $("#btn-generar").show();
            $("#txtClave").parent("div").show();
            $("#txtClave2").parent("div").show();
            $("#toggleClave").show();
            $("#toggleClave2").show();
        });
    </script>

    <!-- Asignar evento click a los botones Editar -->
    <script>
        $(document).on("click", ".btn-editar", function () {
            // Cambiar título del modal
            $('.modal-title').html('Cambiar Rol de Empleado');

            // Recuperar el ID en el componente donde está el botón
            let nro = $(this).parents("tr").find("td")[0].innerHTML;

            // Consulta asíncrona
            $.post("json/find_empleado.php",
                { nro_empleado: nro },
                function (response) {
                    // Mostrar en el formulario el valor de las variables
                    $("#txtNro").val(response.nro_empleado);
                    $("#txtNombre").val(response.nombre);
                    $("#txtApellido").val(response.apellido);
                    $("#txtEmail").val(response.email);
                    $("#txtUsuario").val(response.codigo_usuario);
                    $("#cbxRol").val(response.id_rol);

                    // Dejar activo solo el input de Rol
                    $("#txtNombre").attr('disabled', true);
                    $("#txtApellido").attr('disabled', true);
                    $("#txtEmail").attr('disabled', true);
                    $("#txtUsuario").attr('disabled', true);
                    $("#txtClave").attr('disabled', true);
                    $("#txtClave2").attr('disabled', true);

                    // Esconder botón de generar usuario e inputs de contraseña
                    $("#btn-generar").hide();
                    $("#txtClave").parent("div").hide();
                    $("#txtClave2").parent("div").hide();
                    $("#toggleClave").hide();
                    $("#toggleClave2").hide();
                });
        });
    </script>

    <!-- Asignar evento click a los botones Eliminar -->
    <script>
        // Botón eliminar
        $(document).on("click", ".btn-eliminar", function () {
            // Recuperar el ID en el componente donde está el botón
            let nro = $(this).parents("tr").find("td")[0].innerHTML;

            // Mostrar en el formulario el valor de las variables
            $("#txtNroEliminar").val(nro);

            // Crear mensaje de confirmación
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: "btn btn-success me-4",
                    cancelButton: "btn btn-danger me-4"
                },
                buttonsStyling: false
            });
            swalWithBootstrapButtons.fire({
                title: "¿Estás seguro de eliminar este empleado?",
                text: "Esta operación es irreversible",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Sí, eliminar",
                cancelButtonText: "No, cancelar",
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Hacer submit en el formulario #frm-eliminar
                    $("#frm-eliminar").submit();
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    swalWithBootstrapButtons.fire({
                        title: "Operación cancelada",
                        text: "No se eliminó el empleado",
                        icon: "error"
                    });
                }
            });
        });
    </script>

    <!-- Visibilidad de las contraseñas -->
    <script>
        // Input Contraseña
        $(document).on("click", "#toggleClave", function () {
            // Cambiar el tipo de input (text <=> password)
            $("#txtClave").attr('type') === 'password' ?
                $("#txtClave").attr('type', 'text') :
                $("#txtClave").attr('type', 'password');
            // Cambiar el ícono (eye <=> eyeslash)
            $("#toggleClave").attr('class') === 'fas fa-eye-slash fa-2x mt-3' ?
                $("#toggleClave").attr('class', 'fas fa-eye fa-2x mt-3') :
                $("#toggleClave").attr('class', 'fas fa-eye-slash fa-2x mt-3');

        });

        // Input Confirmar Contraseña
        $(document).on("click", "#toggleClave2", function () {
            // Cambiar el tipo de input (text <=> password)
            $("#txtClave2").attr('type') === 'password' ?
                $("#txtClave2").attr('type', 'text') :
                $("#txtClave2").attr('type', 'password');
            // Cambiar el ícono (eye <=> eyeslash)
            $("#toggleClave2").attr('class') === 'fas fa-eye-slash fa-2x mt-3' ?
                $("#toggleClave2").attr('class', 'fas fa-eye fa-2x mt-3') :
                $("#toggleClave2").attr('class', 'fas fa-eye-slash fa-2x mt-3');

        });
    </script>

    <!-- Generación automática de código de usuario -->
    <script>
        $(document).on("click", "#btn-generar", function () {
            let nom = $("#txtNombre").val().toLowerCase().replace(" ", ".");
            let ape = $("#txtApellido").val().toLowerCase().replace(" ", ".");
            let cod = nom + "." + ape + "." + Math.floor(Math.random() * 1000);
            $("#txtUsuario").val(cod);
        });
    </script>

    <!--JScript de Bootstrap Validator-->
    <script
        src="//cdnjs.cloudflare.com/ajax/libs/jquery.bootstrapvalidator/0.5.1/js/bootstrapValidator.min.js"></script>
    <!-- Validaciones -->
    <script>
        $(document).ready(function () {
            $('#frm-insert-update').bootstrapValidator({
                feedbackIcons: {
                    valid: 'glyphicon glyphicon-ok',
                    invalid: 'glyphicon glyphicon-remove',
                    validating: 'glyphicon glyphicon-refresh'
                },
                fields: {
                    nombre: {
                        feedbackIcons: true,
                        validators: {
                            notEmpty: {
                                message: 'Debe introducir un nombre'
                            },
                            regexp: {
                                regexp: /([A-Za-z]|[ÁÉÍÓÚáéíóúÑñüÜ ]){3,30}/,
                                message: 'Introduzca un Nombre válido, entre 3-30 caracteres alfabéticos o espacio.'
                            }
                        }
                    },
                    apellido: {
                        validators: {
                            notEmpty: {
                                message: 'Debe introducir un apellido'
                            },
                            regexp: {
                                regexp: /([A-Za-z]|[ÁÉÍÓÚáéíóúÑñüÜ ]){3,30}/,
                                message: 'Introduzca un Apellido válido, entre 3-30 caracteres alfabéticos o espacio.'
                            }
                        }
                    },
                    email: {
                        validators: {
                            notEmpty: {
                                message: 'Debe introducir un email'
                            },
                            regexp: {
                                regexp: /[a-z0-9._%+\-]+@[a-z0-9.\-]+\.[a-z]{2,}$/,
                                message: 'Introduzca un e-mail válido, de máximo 80 caracteres.'
                            },
                            stringLength: {
                                max: 80,
                                message: 'No puede usar más de 80 caracteres.'
                            }
                        }
                    },
                    'id-rol': {
                        validators: {
                            notEmpty: {
                                message: 'Debe seleccionar un rol de empleado.'
                            }
                        }
                    },
                    usuario: {
                        validators: {
                            notEmpty: {
                                message: 'Debe introducir un código de usuario'
                            },
                            regexp: {
                                regexp: /[a-z0-9._%+\-]{10,80}/,
                                message: 'Introduzca un Código de usuario válido, de entre 10-80 caracteres. No puede usar mayúsculas ni espacios.'
                            }
                        }
                    },
                    clave: {
                        validators: {
                            notEmpty: {
                                message: 'Debe introducir una contraseña'
                            },
                            regexp: {
                                regexp: /(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,30}/,
                                message: 'Introduzca una Contraseña válida, de entre 8-30 caracteres. Debe contener al menos un dígito, una letra mayúscula y una letra minúscula.'
                            }
                        }
                    },
                    clave2: {
                        validators: {
                            notEmpty: {
                                message: 'Las contraseñas no coinciden.'
                            },
                            identical: {
                                field: "clave",
                                message: 'Las contraseñas no coinciden.'
                            }
                        }
                    }
                }
            })
        });

    </script>
</body>

</html>