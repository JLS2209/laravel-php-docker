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

// Acceder a la clase Cliente y su controlador
include "../../modelo/cls_Cliente.php";
include "../../modelo/cls_Ubicacion.php";
include "../../controlador/ctrl_Cliente.php";
include "../../controlador/ctrl_Ubicacion.php";
$ctrlCliente = new ControladorCliente();
$ctrlUbicacion = new ControladorUbicacion();
?>

<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Restaurante Sazón & Fuego</title>
    <!--Bootstrap CSS-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <!-- Leaflet CSS (Mapa) -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
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

            <h1 class="text-center mb-4">REGISTRO DE CLIENTES</h1>

            <hr>

            <div class="row justify-content-between g-5 px-4 mb-4">
                <!-- Filtro de consulta por nombre -->
                <div class="col-md-4">
                    <label for="txt-filtro-nombre" class="form-label fs-5">Ingrese un nombre:</label>
                    <input type="text" class="form-control" id="txt-filtro-nombre"
                        placeholder="Nombre y/o apellidos del cliente">
                    <button id="btn-filtro-nombre" class="btn btn-success w-100 mt-3">Consultar</button>
                </div>
                <!-- Filtro de consulta por telefono -->
                <div class="col-md-4">
                    <label for="txt-filtro-telefono" class="form-label fs-5">O ingrese un número de teléfono:</label>
                    <input type="text" class="form-control" id="txt-filtro-telefono" placeholder="Teléfono del cliente">
                    <button id="btn-filtro-telefono" class="btn btn-success w-100 mt-3">Consultar</button>
                </div>
                <!-- Filtro de consulta por distrito -->
                <div class="col-md-4">
                    <label for="cbx-filtro-distrito" class="form-label fs-5">O seleccione un distrito:</label>
                    <select class=" form-select" id="cbx-filtro-distrito">
                        <option value="0">Todos</option>
                    </select>
                    <button id="btn-filtro-distrito" class="btn btn-success w-100 mt-3">Consultar</button>
                </div>
            </div>

            <hr>

            <!-- Tabla de Listado -->
            <div id="paginador" class="d-flex flex-row mx-4"></div>
            <div class="table-responsive" id="tabla-clientes">
                <table class="table table-light table-striped mt-3">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col" class='ps-3'>Número ID</th>
                            <th scope="col">Nombres</th>
                            <th scope="col">E-mail</th>
                            <th scope="col">Teléfono</th>
                            <th scope="col">Distrito</th>
                            <th scope="col"></th> <!-- btn-ver -->
                            <th scope="col"></th> <!-- btn-eliminar -->
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>


            <hr>

            <!--Modal-->
            <div class="modal fade" id="modal-objetivo" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog" style="color:black">
                    <div class="modal-content">
                        <!-- Cabecera del modal -->
                        <div class="modal-header" style="background-color: steelblue; color:white">
                            <h1 class="modal-title fs-5">Cliente</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <!-- Cuerpo -->
                        <div class="modal-body">
                            <div class="row g-3">
                                <!-- Input Nombre -->
                                <div class="form-floating mb-3 col-6">
                                    <input type="text" class="form-control" id="txtNombre" readonly>
                                    <label for="txtNombre">Nombre</label>
                                </div>
                                <!-- Input Apellido -->
                                <div class="form-floating mb-3 col-6">
                                    <input type="text" class="form-control" id="txtApellido" readonly>
                                    <label for="txtApellido">Apellido</label>
                                </div>
                                <!-- Input Email -->
                                <div class="form-floating mb-3 col-6">
                                    <input type="text" class="form-control" id="txtEmail" readonly>
                                    <label for="txtEmail">Correo electrónico</label>
                                </div>
                                <!-- Input Telefono -->
                                <div class="form-floating mb-3 col-6">
                                    <input type="text" class="form-control" id="txtTelefono" readonly>
                                    <label for="txtTelefono">Teléfono</label>
                                </div>
                                <!-- Input Usuario -->
                                <div class="form-floating mb-3 col-6">
                                    <input type="text" class="form-control" id="txtUsuario" readonly>
                                    <label for="txtUsuario">Código de usuario</label>
                                </div>
                                <!-- Input Fidelidad -->
                                <div class="form-floating mb-3 col-6">
                                    <input type="text" class="form-control" id="txtFidelidad" readonly>
                                    <label for="txtFidelidad">Fidelidad</label>
                                </div>

                                <div id="seccion-ubicacion">
                                    <hr>
                                    <!-- Input Provincia -->
                                    <div class="form-floating mb-3 col-12">
                                        <input type="text" class="form-control" id="txtProvincia" readonly>
                                        <label for="txtProvincia">Provincia</label>
                                    </div>
                                    <!-- Input Distrito -->
                                    <div class="form-floating mb-3 col-12">
                                        <input type="text" class="form-control" id="txtDistrito" readonly>
                                        <label for="txtDistrito">Distrito</label>
                                    </div>
                                    <!-- Input Dirección -->
                                    <div class="form-floating mb-3 col-12">
                                        <input type="text" class="form-control" id="txtDireccion" readonly>
                                        <label for="txtDireccion">Dirección</label>
                                    </div>
                                    <!-- Input Coordenadas -->
                                    <div class="form-floating mb-3 col-12">
                                        <input type="text" class="form-control" id="txtCoordenadas" readonly>
                                        <label for="txtCoordenadas">Coordenadas</label>
                                        <!-- Mapa -->
                                        <div id="map" class="rounded mt-3" style="height:300px"></div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulario artificial con el propósito de realizar delete -->
    <form action="controller.php" method="post" id="frm-eliminar">
        <!-- Argumento para el delete (codigo) -->
        <input type="hidden" id="txtNroEliminar" name="nro-cliente">
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
    <!-- Bootpag JS -->
    <script src="../../paginator/paginator.js"></script>

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
                msg("Operación exitosa", "Cliente eliminado", "success");
                break;
            case -1:
                msg("Operación fallida", "No se pudo eliminar el cliente", "error");
                break;
            case -4:
                msg("Operación fallida", "Hubo un error", "error");
                break;
        }
    }

    // Eliminar mensaje de la sesión
    unset($_SESSION["mensaje"]);
    ?>

    <!-- Llenar dinamicamente el combo de Distrito -->
    <script>
        $.get("json/list_distrito.php",
            function (response) {
                response.forEach(distrito => {
                    $('#cbx-filtro-distrito').append(`<option value='${distrito.id}'>${distrito.nombre}</option>`);
                });
            });
    </script>

    <!-- Agregar paginación -->
    <script>
        const paginador = function () {
            paginator({
                table: document.getElementById("tabla-clientes").getElementsByTagName("table")[0],
                box: document.getElementById("paginador"),
                box_mode: "list",
                tail_call: function () {
                    // Dar estilos al paginador
                    $(".pagination").addClass("d-flex justify-content-center");
                    $(".pagination li").addClass("page-item");
                    $(".pagination a").addClass("page-link");
                    // Dar estilos al mensajes
                    $("#paginador span").addClass("mx-4 d-flex align-items-center");
                    // Esconder el selector de páginas
                    $("#paginador select").hide();

                }
            });
        }
    </script>

    <!-- Asignar evento click al botón de filtro por distrito -->
    <script>
        // Función
        const mostrarClientesPorDistrito = function () {
            // Recuperar selección del combo
            const distrito = $('#cbx-filtro-distrito').val();

            // Limpiar los filtros alternativos
            $('#txt-filtro-nombre').val("");
            $('#txt-filtro-telefono').val("");

            // Vaciar la tabla
            $("#tabla-clientes tbody").html("");

            // Consulta asíncrona
            $.post("json/list_cliente_by_distrito.php",
                { distrito: distrito },
                function (response) {
                    $.each(response, function (index, item) {
                        // Llenar tabla
                        $("#tabla-clientes tbody").append(`${item.fila}`);
                    });
                });

            // Llamar al paginador
            paginador();
        };

        // Llamar a la función al iniciar la página
        mostrarClientesPorDistrito();

        // Evento
        $(document).on("click", "#btn-filtro-distrito", mostrarClientesPorDistrito);
    </script>

    <!-- Asignar evento click al botón de filtro por nombre -->
    <script>
        $(document).on("click", "#btn-filtro-nombre", function () {
            // Recuperar input
            let nombre = $('#txt-filtro-nombre').val();

            // Limpiar los filtros alternativos
            $('#txt-filtro-telefono').val("");
            $('#cbx-filtro-rol').val("0");

            // Vaciar la tabla
            $("#tabla-clientes tbody").html("");

            // Consulta asíncrona
            $.post("json/list_cliente_by_nombre.php",
                { nombre: nombre },
                function (response) {
                    $.each(response, function (index, item) {
                        // Llenar tabla
                        $("#tabla-clientes tbody").append(`${item.fila}`);
                    });
                });
                // Llamar al paginador
            paginador();
        });        
    </script>

    <!-- Asignar evento click al botón de filtro por telefono -->
    <script>
        $(document).on("click", "#btn-filtro-telefono", function () {
            // Recuperar input
            let telefono = $('#txt-filtro-telefono').val();

            // Limpiar los filtros alternativos
            $('#txt-filtro-nombre').val("");
            $('#cbx-filtro-rol').val("0");

            // Vaciar la tabla
            $("#tabla-clientes tbody").html("");

            // Consulta asíncrona
            $.post("json/list_cliente_by_telefono.php",
                { telefono: telefono },
                function (response) {
                    $.each(response, function (index, item) {
                        // Llenar tabla
                        $("#tabla-clientes tbody").append(`${item.fila}`);
                    });
                });
                // Llamar al paginador
            paginador();
        });        
    </script>

    <!-- Leaflet JS (Mapa) -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <!-- Asignar evento click a los botones Ver -->
    <script>
        // Inicializar mapa
        const map = L.map('map');

        $(document).on("click", ".btn-ver", function () {
            // Recuperar el ID en el componente donde está el botón
            let nro = $(this).parents("tr").find("td")[0].innerHTML;

            // Consulta asíncrona
            $.post("json/find_cliente.php",
                { nro_cliente: nro },
                function (response) {
                    // Mostrar en el formulario el valor de las variables
                    $("#txtNombre").val(response.nombre);
                    $("#txtApellido").val(response.apellido);
                    $("#txtEmail").val(response.email);
                    $("#txtTelefono").val(response.telefono);

                    // Esconder usuario y fidelidad si el cliente no está registrado                    
                    if (response.codigo_usuario == null) {
                        $("#txtUsuario").parent("div").hide();
                        $("#txtFidelidad").parent("div").hide();
                    } else {
                        $("#txtUsuario").parent("div").show();
                        $("#txtFidelidad").parent("div").show();
                        $("#txtUsuario").val(response.codigo_usuario);
                        $("#txtFidelidad").val(response.fidelidad);
                    }

                    // Esconder sección de ubicación si el cliente no tiene
                    if (response.ubicacion == null) {
                        $("#seccion-ubicacion").hide();
                    } else {
                        $("#seccion-ubicacion").show();
                        $("#txtProvincia").val(response.ubicacion.distrito.nombre_provincia);
                        $("#txtDistrito").val(response.ubicacion.distrito.nombre_distrito);
                        $("#txtDireccion").val(response.ubicacion.direccion);
                        $("#txtCoordenadas").val(`(${response.ubicacion.lat}, ${response.ubicacion.long})`);

                        // Localizar punto en el mapa
                        map.setView([response.ubicacion.lat, response.ubicacion.long], 17);
                        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            maxZoom: 19,
                            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                        }).addTo(map);
                        // Agregar marcador en las coordenadas dadas
                        L.marker([response.ubicacion.lat, response.ubicacion.long]).addTo(map);
                    }

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
                title: "¿Estás seguro de eliminar este cliente?",
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
                        text: "No se eliminó el cliente",
                        icon: "error"
                    });
                }
            });
        });
    </script>

</body>

</html>