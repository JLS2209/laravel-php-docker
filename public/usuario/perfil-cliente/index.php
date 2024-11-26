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

// Acceder a la clase Cliente y su controlador
include "../../modelo/cls_Cliente.php";
include "../../modelo/cls_Ubicacion.php";
include "../../controlador/ctrl_Cliente.php";
$ctrlCliente = new ControladorCliente();

// Recuperar Cliente a partir de la sesión
$cliente = $ctrlCliente->show($_SESSION['nro']);
// Tarjeta de perfil
$nombre_rol = ($cliente->is_regular()) ? "Cliente Regular" : "Cliente Fiel";
$nombre_completo = "$cliente->nombre $cliente->apellido";
// Valores actuales de ubicación
$id_ubicacion = ($cliente->ubicacion == null) ? "0" : $cliente->ubicacion->id_ubicacion;
$provincia = ($cliente->ubicacion == null) ? "" : $cliente->ubicacion->distrito->nombre_provincia;
$id_distrito = ($cliente->ubicacion == null) ? "" : $cliente->ubicacion->distrito->id_distrito;
$direccion = ($cliente->ubicacion == null) ? "" : $cliente->ubicacion->direccion;
$lat = ($cliente->ubicacion == null) ? "-11.9529" : $cliente->ubicacion->lat;
$long = ($cliente->ubicacion == null) ? "-77.0702" : $cliente->ubicacion->long;
$coord = ($cliente->ubicacion == null) ? "" : "($lat, $long)";
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
                                <span class="card-text"><?php echo "$cliente->codigo_usuario" ?></span>
                                <span class="card-text">Fidelidad del mes:
                                    <?php echo "$cliente->fidelidad" ?></span><br>
                                <div class="mt-auto">
                                    <p class="card-text text-end"><small class="text-muted">
                                            <?php echo "Fecha de registro: $cliente->fecha_registro" ?>
                                        </small></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botón Mensajes y Pedidos -->
                <div class="row g-4 col-6 mx-auto">
                    <div class="col-md-6">
                        <a class="btn btn-warning btn-lg w-100" href="mensajes.php">Mis Mensajes</a>
                    </div>
                    <div class="col-md-6">
                        <a class="btn btn-warning btn-lg w-100" href="#">Mis Pedidos</a>
                    </div>
                </div>

                <hr class="my-4">

                <!-- Formulario de Datos personales -->
                <h2 class="card-title text-center mb-5 fw-light">Datos personales</h2>
                <form class="row g-3" method="post" action="controller-datos-perfil.php" id="frm-editar-perfil" novalidate>
                    <!-- Input hidden para el número id -->
                    <input type="hidden" class="form-control" id="txtNro1" name="nro-cliente" readonly
                        value='<?php echo "$cliente->nro_cliente" ?>'>
                    <!-- Input Nombre -->
                    <div class="form-floating col-md-6 mb-1">
                        <input type="text" class="form-control" id="txtNombre" name="nombre"
                            value='<?php echo "$cliente->nombre" ?>' pattern="([A-Za-z]|[ÁÉÍÓÚáéíóúÑñüÜ ]){3,30}"
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
                            value='<?php echo "$cliente->apellido" ?>' pattern="([A-Za-z]|[ÁÉÍÓÚáéíóúÑñüÜ ]){3,30}"
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
                            value='<?php echo "$cliente->email" ?>' pattern="[a-z0-9._%+\-]+@[a-z0-9.\-]+\.[a-z]{2,}$"
                            maxlength="80" required autofocus>
                        <label for="txtEmail">
                            <i class="fas fa-at fa-lg me-1 fa-fw"></i>Correo electrónico
                        </label>
                        <div class="invalid-feedback">
                            Introduzca un e-mail válido, de máximo 80 caracteres.
                        </div>
                    </div>
                    <!-- Input Teléfono -->
                    <div class="form-floating col-md-6 mb-1">
                        <input type="tel" class="form-control" id="txtTelefono" name="telefono"
                            value='<?php echo "$cliente->telefono" ?>' pattern="[0-9]{9,10}" required autofocus>
                        <label for="txtTelefono">
                            <i class="fas fa-phone fa-lg me-1 fa-fw"></i>Teléfono celular
                        </label>
                        <div class="invalid-feedback">
                            Introduzca un número de teléfono válido, sin espacios entre los dígitos.
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
                <form class="row g-3" method="post" action="controller-clave.php" id="frm-cambiar-clave" novalidate>
                    <!-- Input hidden para el número id -->
                    <input type="hidden" class="form-control" id="txtNro2" name="nro-cliente" readonly
                        value='<?php echo "$cliente->nro_cliente" ?>'>
                    <!-- Input hidden para el código de usuario -->
                    <input type="hidden" class="form-control" id="txtUsuario" name="codigo-usuario" readonly
                        value='<?php echo "$cliente->codigo_usuario" ?>' autocomplete="username">
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

                <!-- Formulario de Ubicación -->
                <h2 class="card-title text-center mb-5 fw-light">Mi Ubicación</h2>
                <form class="row g-3" method="post" action="controller-ubicacion.php" id="frm-cambiar-ubicacion"
                    novalidate>
                    <!-- Input hidden para el número id -->
                    <input type="hidden" class="form-control" id="txtNro3" name="nro-cliente" readonly
                        value='<?php echo "$cliente->nro_cliente" ?>'>
                    <!-- Input hidden para el id ubicacion -->
                    <input type="hidden" class="form-control" id="txtIdUbicacion" name="id-ubicacion" readonly
                        value='<?php echo "$id_ubicacion" ?>'>

                    <!-- Input Provincia -->
                    <div class="form-floating col-md-6 mb-1">
                        <select class="form-select" id="cbxProvincia">
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
                        <select class="form-select" id="cbxDistrito" name="id_distrito">
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
                            value='<?php echo "$direccion" ?>'>
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
                        value='<?php echo "$coord" ?>' pattern="\(-?\d+\.\d+, -?\d+\.\d+\)">
                        <label for="txtCoordenadas">
                            <i class="fas fa-location-dot fa-lg me-1 fa-fw"></i>Seleccione su ubicación
                            aproximada en el mapa
                        </label>
                        <div class="invalid-feedback">
                            No ha seleccionado una ubicación en el mapa.
                        </div>
                        <div id="map" class="rounded mt-3" style="height:300px;"></div>
                    </div>

                    <!-- Botón Cambiar Ubicacion -->
                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-success btn-lg px-5 py-2"
                            id="btn-cambiar-ubicacion">GUARDAR UBICACION</button>
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
            case 3:
                msg("Operación exitosa", "Se ha guardado su ubicación exitosamente", "success");
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
            case -4:
                msg("Operación fallida", "No se pudo guardar su nueva ubicación", "error");
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
                    if ($('#cbxDistrito').val() == null) {$('#cbxDistrito').val('');}
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

        // Desactivar el submit del formulario si presenta inputs inválidos
        let form3 = document.getElementById("frm-cambiar-ubicacion");
        form3.addEventListener("submit", event => {
            if (!form3.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form3.classList.add('was-validated');
        }, false);
    </script>

</body>

</html>