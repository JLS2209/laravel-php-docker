<?php
// Iniciar o reanudar sesión
session_start();

// El acceso no requiere credenciales, aunque debería mostrarse solo a visitantes

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
    <!-- Leaflet CSS (Mapa) -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
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
                        <h2 class="card-title text-center mb-5 fw-light">Datos de Usuario</h2>
                        <form id="form-validacion" method="post" action="registroController.php" novalidate>
                            <!-- Input Nombre -->
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="txtNombre" name="nombre" placeholder=""
                                    pattern="([A-Za-z]|[ÁÉÍÓÚáéíóúÑñüÜ ]){3,30}" required autofocus>
                                <label for="txtNombre">
                                    <i class="fas fa-pencil fa-lg me-1 fa-fw"></i>Nombre
                                </label>
                                <div class="invalid-feedback">
                                    Introduzca un Nombre válido, entre 3-30 caracteres alfabéticos o espacio.
                                </div>
                            </div>
                            <!-- Input Apellido -->
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="txtApellido" name="apellido" placeholder=""
                                    pattern="([A-Za-z]|[ÁÉÍÓÚáéíóúÑñüÜ ]){3,30}" required autofocus>
                                <label for="txtApellido">
                                    <i class="fas fa-pencil fa-lg me-1 fa-fw"></i>Apellido
                                </label>
                                <div class="invalid-feedback">
                                    Introduzca un Apellido válido, entre 3-30 caracteres alfabéticos o espacio.
                                </div>
                            </div>
                            <!-- Input Email -->
                            <div class="form-floating mb-3">
                                <input type="email" class="form-control" id="txtEmail" name="email" placeholder=""
                                    pattern="[a-z0-9._%+\-]+@[a-z0-9.\-]+\.[a-z]{2,}$" maxlength="80" required
                                    autofocus>
                                <label for="txtEmail">
                                    <i class="fas fa-at fa-lg me-1 fa-fw"></i>Correo electrónico
                                </label>
                                <div class="invalid-feedback">
                                    Introduzca un e-mail válido, de máximo 80 caracteres.
                                </div>
                            </div>
                            <!-- Input Teléfono -->
                            <div class="form-floating mb-3">
                                <input type="tel" class="form-control" id="txtTelefono" name="telefono" placeholder=""
                                    pattern="[0-9]{9,10}" required autofocus>
                                <label for="txtTelefono">
                                    <i class="fas fa-phone fa-lg me-1 fa-fw"></i>Teléfono celular
                                </label>
                                <div class="invalid-feedback">
                                    Introduzca un número de teléfono válido, sin espacios entre los dígitos.
                                </div>
                            </div>

                            <hr>

                            <p>Introduzca el código de usuario y la contraseña que usará para iniciar sesión.</p>
                            <!-- Input Usuario -->
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="txtUsuario" name="usuario" placeholder=""
                                    pattern="[a-z0-9._%+\-]{10,80}" required autofocus autocomplete="username">
                                <label for="txtUsuario">
                                    <i class="fas fa-user fa-lg me-1 fa-fw"></i>Código de Usuario
                                </label>
                                <div class="invalid-feedback">
                                    Introduzca un Código de usuario válido, de entre 10-80 caracteres. No puede usar
                                    mayúsculas ni espacios.
                                </div>
                            </div>
                            <!-- Botón para generar usuario automáticamente -->
                            <div class="d-grid mb-2">
                                <button class="btn btn-lg btn-secondary btn-login fw-bold text-uppercase" type="button"
                                    id="btn-generar">Generar código de usuario</button>
                            </div>
                            <!-- Input Contraseña -->
                            <div class="row">
                                <div class="form-floating col-10 mb-3">
                                    <input type="password" class="form-control" id="txtClave" name="clave"
                                        placeholder="" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,30}" required
                                        autofocus autocomplete="new-password">
                                    <label for="txtClave">
                                        <i class="fas fa-lock fa-lg me-1 fa-fw ms-2"></i>Contraseña
                                    </label>
                                    <div class="invalid-feedback">
                                        Introduzca una Contraseña válida, de entre 8-30 caracteres. Debe contener al
                                        menos un dígito, una letra mayúscula y una letra minúscula.
                                    </div>
                                </div>
                                <div class="col-2">
                                    <i id="toggleClave" class="fas fa-eye-slash fa-2x mt-3" style="cursor: pointer"></i>
                                </div>
                            </div>
                            <!-- Input Confirmar Contraseña -->
                            <div class="row">
                                <div class="form-floating col-10 mb-3">
                                    <input type="password" class="form-control" id="txtClave2" placeholder="" required
                                        autofocus autocomplete="new-password">
                                    <label for="txtClave2">
                                        <i class="fas fa-lock fa-lg me-1 fa-fw ms-2"></i>Confirmar Contraseña
                                    </label>
                                    <div class="invalid-feedback">
                                        Las contraseñas no coinciden.
                                    </div>
                                </div>
                                <div class="col-2">
                                    <i id="toggleClave2" class="fas fa-eye-slash fa-2x mt-3"
                                        style="cursor: pointer"></i>
                                </div>
                            </div>

                            <hr>

                            <p>Si desea, puede brindar detalles sobre su ubicación (opcional).</p>
                            <!-- Check Ubicación -->
                            <div class="form-floating mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="chkUbicacion"
                                        name="has_ubicacion" value="si">
                                    <label class="form-check-label" for="chkUbicacion">
                                        Deseo brindar mi Ubicación
                                    </label>
                                </div>
                            </div>
                            <!-- Input Provincia -->
                            <div class="form-floating mb-3">
                                <select class="form-select" id="cbxProvincia" disabled>
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
                            <div class="form-floating mb-3">
                                <select class="form-select" id="cbxDistrito" name="id_distrito" disabled>
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
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="txtDireccion" name="direccion"
                                    placeholder="" disabled>
                                <label for="txtDireccion">
                                    <i class="fas fa-location-dot fa-lg me-1 fa-fw"></i>Dirección
                                </label>
                                <div class="invalid-feedback">
                                    Debe escribir una dirección válida.
                                </div>
                            </div>

                            <!-- Input Coordenadas y Mapa -->
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="txtCoordenadas" name="coordenadas"
                                    placeholder="" disabled pattern="\(-?\d+\.\d+, -?\d+\.\d+\)">
                                <label for="txtCoordenadas">
                                    <i class="fas fa-location-dot fa-lg me-1 fa-fw"></i>Seleccione su ubicación
                                    aproximada en el mapa
                                </label>
                                <div class="invalid-feedback">
                                    No ha seleccionado una ubicación en el mapa.
                                </div>
                                <div id="map" class="rounded mt-3" style="height:300px;"></div>
                            </div>

                            <hr>

                            <!-- Botón de registro -->
                            <div class="d-grid mb-2">
                                <button class="btn btn-lg btn-warning btn-login fw-bold text-uppercase"
                                    type="submit">Regístrate</button>
                            </div>

                            <!-- Link a página de login -->
                            <a class="d-block text-center mt-2 small" href="../login/">¿Ya tienes una cuenta? Inicia
                                sesión</a>

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
                msg("Operación exitosa", "Registro con éxito.", "success");
                break;
            case -1:
                msg("Operación fallida", "Hubo un error en el registro.", "error");
                break;
            case -2:
                msg("Operación fallida", "El Código de usuario ingresado ya existe. Debe intentar con otro código", "error");
                break;
        }
    }

    // Eliminar variable mensaje de la sesión
    unset($_SESSION["mensaje"]);
    ?>

    <!-- Leaflet JS (Mapa) -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        // Crear mapa y centrarlo
        const map = L.map('map');
        map.setView([-11.9529, -77.0702], 17);
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);
        // Agregar marcador en la ubicación del restaurante
        const marker = L.marker([-11.9529, -77.0702]).addTo(map);
        marker.bindPopup("¡Hola! Aquí se encuentra <b>Sabor & Fuego</b>.").openPopup();

        // Evento al hacer click en el mapa
        let marker2 = L.marker([-11.9529, -77.0702]).addTo(map);
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

    <!-- Generación automática de código de usuario -->
    <script>
        $(document).on("click", "#btn-generar", function () {
            let nom = $("#txtNombre").val().toLowerCase().replace(" ", ".");
            let ape = $("#txtApellido").val().toLowerCase().replace(" ", ".");
            let cod = nom + "." + ape + "." + Math.floor(Math.random() * 1000);
            $("#txtUsuario").val(cod);
        });
    </script>

    <!-- Cambiar estado de los inputs de Ubicación según el checkbox -->
    <script>
        $(document).on("change", "#chkUbicacion", function () {
            // Recuperar estado del check de ubicación
            let isChecked = this.checked;

            // Activar / Desactivar cajas de ubicación y mapa
            $("#cbxProvincia").attr('disabled', !isChecked);
            $("#cbxDistrito").attr('disabled', !isChecked);
            $("#txtDireccion").attr('disabled', !isChecked);
            $("#txtCoordenadas").attr('disabled', !isChecked);
            $("#cbxProvincia").attr('required', isChecked);
            $("#cbxDistrito").attr('required', isChecked);
            $("#txtDireccion").attr('required', isChecked);
            $("#txtCoordenadas").attr('required', isChecked);

            // Resetear las cajas
            if (!isChecked) {
                $("#cbxProvincia").val("");
                $("#cbxDistrito").val("");
                $("#txtDireccion").val("");
                $("#txtCoordenadas").val("");
            }
        });
    </script>

    <!-- Llenar dinamicamente el combo de Distrito -->
    <script>
        $(document).on("change", "#cbxProvincia", function () {
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

    <!-- Validaciones -->
    <script>
        // Validar la confirmación de contraseña
        $(document).on("input", "#txtClave", function (e) {
            $("#txtClave2").attr('pattern', $("#txtClave").val())
        });
        $(document).on("input", "#txtClave2", function (e) {
            $("#txtClave2").attr('pattern', $("#txtClave").val())
        });

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