<?php
// Iniciar o reanudar sesión
session_start();

// Restringir acceso si el id_rol no corresponde a cliente (2)
if (!(isset($_SESSION["id_rol"]) && $_SESSION["id_rol"] == 2)) {
    error_log("Intento de acceso sin credenciales adecuadas de cliente!");
    header("location: ../../");
}

// Acceder a la clase Conectar
include "../../cls_conectar/cls_Conectar.php";

// Acceder a la clase Mensaje y su controlador
include "../../modelo/cls_Cliente.php";
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

                <h2 class="card-title text-center mb-5 fw-light">MIS MENSAJES</h2>

                <hr>

                <div class="row justify-content-between g-5 px-4 mb-4">
                    <!-- Filtro de consulta por asunto -->
                    <div class="col-md-4">
                        <label for="cbx-filtro-asunto" class="form-label fs-5">Seleccione el asunto:</label>
                        <select id="cbx-filtro-asunto" class="form-select">
                            <option>Todos</option>
                            <option>Sugerencia</option>
                            <option>Reclamo</option>
                            <option>Ofrezco un producto o servicio</option>
                            <option>Otro asunto</option>
                        </select>
                        <button id="btn-filtro-asunto" class="btn btn-success w-100 mt-3">Consultar</button>
                    </div>
                    <!-- Filtro de consulta por fecha -->
                    <div class="col-md-8">
                        <label class="form-label fs-5">O entre las fechas:</label>
                        <div class="row g-3">
                            <div class="col-6">
                                <input type="date" class="form-control" id="txt-filtro-fecha-inicio">
                            </div>
                            <div class="col-6">
                                <input type="date" class="form-control" id="txt-filtro-fecha-fin">
                            </div>
                        </div>
                        <button id="btn-filtro-fecha" class="btn btn-success w-100 mt-3">Consultar</button>
                    </div>
                </div>

                <hr>

                <!-- Contenedor de Mensajes -->
                <div id="contenedor-mensajes"></div>

                <hr>

                <!-- Botón para volver al Perfil -->
                <div class="col-12 text-center">
                    <a href="../perfil-cliente" class="btn btn-danger btn-lg px-5 py-2">VOLVER</a>
                </div>

                <!--Modal-->
                <div class="modal fade" id="modal-objetivo" data-bs-backdrop="static" data-bs-keyboard="false">
                    <div class="modal-dialog" style="color:black">
                        <div class="modal-content">
                            <!-- Cabecera del modal -->
                            <div class="modal-header" style="background-color: #F7BA00;">
                                <h1 class="modal-title fs-5">Editar mensaje</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <!-- Formulario -->
                            <div class="modal-body">
                                <form class="row g-3" method="post" action="controller-mensajes.php" id="frm-edit" novalidate>
                                    <!-- Input hidden para el código id -->
                                    <input type="hidden" class="form-control" name="id-mensaje" readonly id="txtId"
                                        value="0">
                                    <!-- Input Autor -->
                                    <div class="col-12">
                                        <label for="txtAutor" class="form-label fw-bold">Autor</label>
                                        <input type="text" class="form-control" id="txtAutor" readonly>
                                    </div>
                                    <!-- Input Fecha -->
                                    <div class="col-12">
                                        <label for="txtFecha" class="form-label fw-bold">Fecha de envío</label>
                                        <input type="text" class="form-control" id="txtFecha" readonly>
                                    </div>
                                    <!-- Input Asunto -->
                                    <div class="col-12">
                                        <label for="cbxAsunto" class="form-label fw-bold">Asunto</label>
                                        <select class="form-select" id="cbxAsunto" name="asunto" required>
                                            <option selected disabled value="">Seleccione...</option>
                                            <option>Sugerencia</option>
                                            <option>Reclamo</option>
                                            <option>Ofrezco un producto o servicio</option>
                                            <option>Otro asunto</option>
                                        </select>
                                        <div class="invalid-feedback">
                                            Debe seleccionar una Categoría.
                                        </div>
                                    </div>
                                    <!-- Input Contenido -->
                                    <div class="col-12">
                                        <label for="txaContenido" class="form-label fw-bold">Contenido</label>
                                        <textarea class="form-control" name="contenido" rows="3" id="txaContenido"
                                            required autofocus></textarea>
                                        <div class="invalid-feedback">
                                            El mensaje no puede estar vacío.
                                        </div>
                                    </div>

                                    <!-- Input artificial con el propósito de diferenciarlo de delete -->
                                    <!-- Tipo de operacion CRUD -->
                                    <input type="hidden" id="tipo-crud" name="tipo-crud" value="edit">

                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-success" id="btn-grabar">Grabar</button>
                                        <button type="button" class="btn btn-danger"
                                            data-bs-dismiss="modal">Cerrar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Formulario artificial con el propósito de realizar delete -->
    <form action="controller-mensajes.php" method="post" id="frm-eliminar">
        <!-- Argumento para el delete (codigo) -->
        <input type="hidden" id="txtIdEliminar" name="id-mensaje">
        <!-- Tipo de operacion CRUD -->
        <input type="hidden" name="tipo-crud" value="delete">
        <!-- La acción submit se hace mediante JQuery -->
    </form>

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
                msg("Operación exitosa", "Mensaje eliminado", "success");
                break;
            case 2:
                msg("Operación exitosa", "Mensaje actualizado", "success");
                break;
            case -1:
                msg("Operación fallida", "No se pudo eliminar el mensaje", "error");
                break;
            case -2:
                msg("Operación fallida", "No se pudo editar el mensaje", "error");
                break;
            case -10:
                msg("Operación fallida", "Hubo un error", "error");
                break;
        }
    }

    // Eliminar mensaje de la sesión
    unset($_SESSION["mensaje"]);
    ?>

    <!-- Asignar evento click al botón de filtro por asunto -->
    <script>
        // Función
        const consultarMensajesPorAsunto = function () {
            // Recuperar input
            let asunto = $('#cbx-filtro-asunto').val();
            asunto = (asunto == "Todos") ? "" : asunto;

            // Limpiar los filtros alternativos
            $('#txt-filtro-fecha-inicio').val("");
            $('#txt-filtro-fecha-fin').val("");

            // Vaciar el contenedor
            $("#contenedor-mensajes").html("");

            // Consulta asíncrona
            $.post("json/list_mensaje_by_asunto.php",
                { 
                    nro_cliente: '<?php echo $_SESSION["nro"] ?>',
                    asunto: asunto
                },
                function (response) {
                    $.each(response, function (index, item) {
                        // Llenar contenedor
                        $("#contenedor-mensajes").append(`${item.tarjeta}`);
                    });
                });
        };

        // Llamar a la función al iniciar la página
        consultarMensajesPorAsunto();

        // Evento
        $(document).on("click", "#btn-filtro-asunto", consultarMensajesPorAsunto);        
    </script>

    <!-- Asignar evento click al botón de filtro por fecha -->
    <script>
        // Evento
        $(document).on("click", "#btn-filtro-fecha", function () {
            // Recuperar input
            let fecha1 = $('#txt-filtro-fecha-inicio').val();
            let fecha2 = $('#txt-filtro-fecha-fin').val();

            // Limpiar los filtros alternativos
            $('#cbx-filtro-asunto').val("Todos");

            // Vaciar el contenedor
            $("#contenedor-mensajes").html("");

            // Consulta asíncrona
            $.post("json/list_mensaje_by_fecha.php",
                {
                    nro_cliente: '<?php echo $_SESSION["nro"] ?>',
                    fecha1: fecha1,
                    fecha2: fecha2
                },
                function (response) {
                    $.each(response, function (index, item) {
                        // Llenar contenedor
                        $("#contenedor-mensajes").append(`${item.tarjeta}`);
                    });
                });
        });        
    </script>

    <!-- Asignar evento click a los botones Editar -->
    <script>
        $(document).on("click", ".btn-editar", function () {
            // Cambiar título del modal
            $('.modal-title').html('Editar Mensaje');

            // Recuperar el ID en el componente donde está el botón            
            let id = $(this).parent("div").parent("div").find("td")[0].innerHTML;

            // Consulta asíncrona
            $.post("json/find_mensaje.php",
                { id_mensaje: id },
                function (response) {
                    const codigo = (response.cliente.codigo_usuario == null) ? 'Visitante' : response.cliente.codigo_usuario;
                    const autor = `${response.cliente.nombre} ${response.cliente.apellido} (${codigo})`;
                    // Mostrar en el formulario el valor de las variables
                    $("#txtId").val(response.id_mensaje);
                    $("#txtAutor").val(autor);
                    $("#cbxAsunto").val(response.asunto);
                    $("#txaContenido").val(response.contenido);
                    $("#txtFecha").val(response.fecha_hora);
                });
        });
    </script>

    <!-- Asignar evento click a los botones Eliminar -->
    <script>
        $(document).on("click", ".btn-eliminar", function () {
            // Recuperar el ID en el componente donde está el botón            
            let id = $(this).parent("div").parent("div").find("td")[0].innerHTML;

            // Mostrar en el formulario el valor de las variables
            $("#txtIdEliminar").val(id);

            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: "btn btn-success me-4",
                    cancelButton: "btn btn-danger me-4"
                },
                buttonsStyling: false
            });
            swalWithBootstrapButtons.fire({
                title: "¿Estás seguro de eliminar este mensaje?",
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
                        text: "No se eliminó el mensaje",
                        icon: "error"
                    });
                }
            });
        });
    </script>

    <!-- Validaciones -->
    <script>
        // Desactivar el submit del formulario si presenta inputs inválidos
        let form = document.getElementById("frm-edit");
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