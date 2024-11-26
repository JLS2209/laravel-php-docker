<?php
// Iniciar o reanudar sesión
session_start();

// Restringir acceso si el id_rol no corresponde a empleado de atención (3)
if (!(isset($_SESSION["id_rol"]) && $_SESSION["id_rol"] == 3)) {
    error_log("Intento de acceso sin credenciales adecuadas de empleado de atención!");
    header("location: ../../");
}

// Acceder a la clase Conectar
include "../../cls_conectar/cls_Conectar.php";

// Acceder a la clase Mensaje y su controlador
include "../../modelo/cls_Cliente.php";
include "../../modelo/cls_Mensaje.php";
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

            <h1 class="text-center mb-4">LISTA DE MENSAJES</h1>

            <hr>

            <div class="row justify-content-between g-5 px-4 mb-4">
                <!-- Filtro de consulta por nombre -->
                <div class="col-md-4">
                    <label for="txt-filtro-nombre" class="form-label fs-5">Ingrese un nombre:</label>
                    <input type="text" class="form-control" id="txt-filtro-nombre"
                        placeholder="Nombre y/o apellidos del cliente">
                    <button id="btn-filtro-nombre" class="btn btn-success w-100 mt-3">Consultar</button>
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

        </div>
    </div>

    <!-- Formulario artificial con el propósito de realizar delete -->
    <form action="controller.php" method="post" id="frm-eliminar">
        <!-- Argumento para el delete (codigo) -->
        <input type="hidden" id="txtIdEliminar" name="id-mensaje">
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

    <!-- Asignar evento click al botón de filtro por nombre -->
    <script>
        // Función
        const consultarMensajesPorNombre = function () {
            // Recuperar input
            let nombre = $('#txt-filtro-nombre').val();

            // Limpiar los filtros alternativos
            $('#txt-filtro-fecha-inicio').val("");
            $('#txt-filtro-fecha-fin').val("");

            // Vaciar el contenedor
            $("#contenedor-mensajes").html("");

            // Consulta asíncrona
            $.post("json/list_mensaje_by_nombre.php",
                { nombre: nombre },
                function (response) {
                    $.each(response, function (index, item) {
                        // Llenar contenedor
                        $("#contenedor-mensajes").append(`${item.tarjeta}`);
                    });
                });
        };

        // Llamar a la función al iniciar la página
        consultarMensajesPorNombre();

        // Evento
        $(document).on("click", "#btn-filtro-nombre", consultarMensajesPorNombre);        
    </script>

    <!-- Asignar evento click al botón de filtro por fecha -->
    <script>
        // Evento
        $(document).on("click", "#btn-filtro-fecha", function () {
            // Recuperar input
            let fecha1 = $('#txt-filtro-fecha-inicio').val();
            let fecha2 = $('#txt-filtro-fecha-fin').val();

            // Limpiar los filtros alternativos
            $('#txt-filtro-nombre').val("");

            // Vaciar el contenedor
            $("#contenedor-mensajes").html("");

            // Consulta asíncrona
            $.post("json/list_mensaje_by_fecha.php",
                { 
                    fecha1: fecha1 ,
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

</body>

</html>