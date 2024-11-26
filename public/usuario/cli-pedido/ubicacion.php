<?php
// Acceder al modelo de clase Pedido
include "../../modelo/cls_Pedido.php";

// Acceder a la clase Cliente y su controlador para recuperar la ubicación del cliente
include "../../modelo/cls_Cliente.php";
include "../../modelo/cls_Ubicacion.php";
include "../../controlador/ctrl_Cliente.php";

// Iniciar o reanudar sesión
session_start();

// Restringir acceso si el id_rol no corresponde a cliente (2)
if (!(isset($_SESSION["id_rol"]) && $_SESSION["id_rol"] == 2)) {
    error_log("Intento de acceso sin credenciales adecuadas de cliente!");
    header("location: ../../");
}

// Si no existe $_SESSION["pedido"], expulsar de la página
if (!isset($_SESSION["pedido"])) {
    error_log("Intento de acceder a pedido/ubicacion sin pasar por las secciones previas de pedido.");
    header("location: ../../");
    return;
}

// Acceder a la clase Conectar
include "../../cls_conectar/cls_Conectar.php";

// Cargar datos de ubicación a partir del Pedido, si ya existe ubicacion previa (not null)
if ($_SESSION["pedido"]->ubicacion != null) {
    $provincia = $_SESSION["pedido"]->ubicacion->distrito->nombre_provincia;
    $id_distrito = $_SESSION["pedido"]->ubicacion->distrito->id_distrito;
    $direccion = $_SESSION["pedido"]->ubicacion->direccion;
    $lat = $_SESSION["pedido"]->ubicacion->lat;
    $long = $_SESSION["pedido"]->ubicacion->long;
    $coord = "($lat, $long)";
} else {
    // Recuperar Cliente a partir de la sesión
    $ctrlCliente = new ControladorCliente();
    $cliente = $ctrlCliente->show($_SESSION['nro']);
    // Usar la ubicación del cliente por defecto (solo si no existe ya en el pedido)
    $provincia = ($cliente->ubicacion == null) ? "" : $cliente->ubicacion->distrito->nombre_provincia;
    $id_distrito = ($cliente->ubicacion == null) ? "" : $cliente->ubicacion->distrito->id_distrito;
    $direccion = ($cliente->ubicacion == null) ? "" : $cliente->ubicacion->direccion;
    $lat = ($cliente->ubicacion == null) ? "-11.9529" : $cliente->ubicacion->lat;
    $long = ($cliente->ubicacion == null) ? "-77.0702" : $cliente->ubicacion->long;
    $coord = ($cliente->ubicacion == null) ? "" : "($lat, $long)";
}
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
    </style>
</head>

<body>
    <!-- Incluir Barra de menús de navegación -->
    <?php include "../../templates/nav.php"; ?>

    <!-- Cuerpo de la página -->
    <div class="container">
        <div class="card flex-row my-5 border-0 shadow rounded-3 overflow-hidden">
            <div class="card-body p-4 p-sm-5">
                <!-- Formulario de Ubicación -->
                <h2 class="card-title text-center mb-5 fw-light">Dirección de entrega</h2>
                <form class="row g-3" method="post" action="controller-ubicacion.php" id="frm-ubicacion" novalidate>
                    <!-- Input Provincia -->
                    <div class="form-floating col-md-6 mb-1">
                        <select class="form-select" id="cbxProvincia" required>
                            <option selected value="" disabled>Seleccione...</option>
                            <option>Lima Metropolitana</option>
                            <option>Callao</option>
                        </select>
                        <label for="cbxProvincia">
                            <i class="fas fa-location-dot fa-lg me-1 fa-fw"></i>Provincia
                        </label>
                        <div class="invalid-feedback">
                            Debe seleccionar una Provincia.
                        </div>
                    </div>

                    <!-- Input Distrito - Llenado mediante JS -->
                    <div class="form-floating col-md-6 mb-1">
                        <select class="form-select" id="cbxDistrito" name="id_distrito" required>
                            <option selected value="" disabled>Seleccione...</option>
                        </select>
                        <label for="cbxDistrito">
                            <i class="fas fa-location-dot fa-lg me-1 fa-fw"></i>Distrito
                        </label>
                        <div class="invalid-feedback">
                            Debe seleccionar un Distrito.
                        </div>
                    </div>

                    <!-- Input Dirección -->
                    <div class="form-floating col-12 mb-1">
                        <input type="text" class="form-control" id="txtDireccion" name="direccion"
                            value='<?php echo "$direccion" ?>' required>
                        <label for="txtDireccion">
                            <i class="fas fa-location-dot fa-lg me-1 fa-fw"></i>Dirección
                        </label>
                        <div class="invalid-feedback">
                            Debe escribir una dirección válida.
                        </div>
                    </div>

                    <!-- Input Coordenadas y Mapa -->
                    <div class="form-floating col-12 mb-1">
                        <input type="text" class="form-control" id="txtCoordenadas" name="coordenadas"
                            value='<?php echo "$coord" ?>' pattern="\(-?\d+\.\d+, -?\d+\.\d+\)" required>
                        <label for="txtCoordenadas">
                            <i class="fas fa-location-dot fa-lg me-1 fa-fw"></i>Seleccione su ubicación
                            aproximada en el mapa
                        </label>
                        <div class="invalid-feedback">
                            No ha seleccionado una ubicación en el mapa.
                        </div>
                        <div id="map" class="rounded mt-3" style="height:300px;"></div>
                    </div>

                    <!-- Botones -->
                    <div class="col-6 text-center">
                        <a href="./" class="btn btn-danger btn-lg px-5 py-2">VOLVER SIN GUARDAR CAMBIOS</a>
                    </div>
                    <div class="col-6 text-center">
                        <button type="submit" class="btn btn-success btn-lg px-5 py-2">GUARDAR Y SEGUIR</button>
                    </div>
                </form>

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

    <!-- Leaflet JS (Mapa) -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        // Crear mapa y centrarlo
        const map = L.map('map');
        map.setView([<?php echo "$lat, $long"; ?>], 17);
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);

        // Agregar marcador en la ubicación del restaurante
        const marker = L.marker([-11.9529, -77.0702]).addTo(map);
        marker.bindPopup("¡Hola! Aquí se encuentra <b>Sabor & Fuego</b>.");

        // Evento al hacer click en el mapa
        let marker2 = L.marker([<?php echo "$lat, $long"; ?>]).addTo(map);
        map.on('click', e => {
            // Remover marcador anterior
            marker2.remove();
            // Agregar marcador al mapa
            marker2 = L.marker(e.latlng);
            marker2.addTo(map);
            // Insertar coordenadas en input
            $("#txtCoordenadas").val(e.latlng.toString().slice(6));
        });        
    </script>

    <!-- Llenar dinamicamente el combo de Distrito -->
    <script>
        // Función para llenar el combo de Distrito según selección del combo de Provincia
        const llenarDistritos = function () {
            // Limpiar el combo de Distrito
            $('#cbxDistrito').html('<option selected value="" disabled>Seleccione...</option>');

            // Consulta asíncrona
            const provincia = $("#cbxProvincia").val();
            $.post("json/list_distrito.php",
                { provincia: provincia },
                function (response) {
                    // Agregar opciones al combo de Distrito
                    response.forEach(distrito => {
                        $('#cbxDistrito').append(`<option value='${distrito.id}'>${distrito.nombre}</option>`);
                    });

                    // Seleccionar distrito actual en el combo
                    $('#cbxDistrito').val('<?php echo "$id_distrito"; ?>');

                    // Corregir en caso el distrito no esté en el combo
                    if ($('#cbxDistrito').val() == null) { $('#cbxDistrito').val(''); }
                });
        }

        // Selecionar provincia actual en el combo
        $('#cbxProvincia').val('<?php echo "$provincia"; ?>');
        llenarDistritos();

        // Evento
        $(document).on("change", "#cbxProvincia", llenarDistritos);
    </script>

    <!-- Validaciones -->
    <script>
        // Desactivar el submit del formulario si presenta inputs inválidos
        let form = document.getElementById("frm-ubicacion");
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