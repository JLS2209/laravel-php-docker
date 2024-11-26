<?php
// Iniciar o reanudar sesión
session_start();

// Restringir acceso si el id_rol no corresponde a empleado administrador (4)
if (! (isset($_SESSION["id_rol"]) && $_SESSION["id_rol"] == 4)) {
    error_log("Intento de acceso sin credenciales adecuadas de administrador!");
    header("location: ../../");
}

// Acceder a la clase Conectar
include "../../cls_conectar/cls_Conectar.php";

// Acceder a la clase Plato y su controlador
include "../../modelo/cls_Plato.php";
include "../../controlador/ctrl_Plato.php";
$ctrl = new ControladorPlato();
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
        <div class="container p-4" style="background-color: rgba(0, 0, 0, 0.8); color: wheat;">

            <h1 class="text-center mb-4">REGISTRO DE PLATOS</h1>

            <hr>

            <div class="row justify-content-between g-5 px-4 mb-4">
                <!-- Filtro de consulta -->
                <div class="col-md-4">
                    <label for="cbx-filtro" class="form-label fs-5">Seleccione un tipo de plato:</label>
                    <select class=" form-select" id="cbx-filtro">
                        <option value="0">Todos</option>
                        <option value="1">Entradas</option>
                        <option value="2">Platos principales</option>
                        <option value="3">Postres</option>
                        <option value="4">Bebidas</option>
                    </select>
                </div>
                <!-- Botón Nuevo Plato -->
                <div class="col-md-3">
                    <button class="btn btn-primary btn-lg btn-nuevo w-100 h-100" type="button" data-bs-toggle="modal"
                        data-bs-target="#modal-objetivo">Nuevo Plato</button>
                </div>
            </div>

            <hr>

            <!-- Contenedor de platos. El llenado es dinámico desde JS -->
            <div class="px-4">
                <!-- Platos Tipo 1:  Entradas -->
                <div id="contenedor-tipo-1" style="display: none;">
                    <h2 class="lead fs-3 fw-bold mb-4">Entradas</h2>
                    <div id="cartas-tipo-1" class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4"> </div>
                    <hr>
                </div>
                <!-- Platos Tipo 2:  Platos principales -->
                <div id="contenedor-tipo-2" style="display: none;">
                    <h2 class="lead fs-3 fw-bold mb-4">Platos principales</h2>
                    <div id="cartas-tipo-2" class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4"> </div>
                    <hr>
                </div>
                <!-- Platos Tipo 3:  Postres -->
                <div id="contenedor-tipo-3" style="display: none;">
                    <h2 class="lead fs-3 fw-bold mb-4">Postres</h2>
                    <div id="cartas-tipo-3" class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4"> </div>
                    <hr>
                </div>
                <!-- Platos Tipo 4:  Bebidas -->
                <div id="contenedor-tipo-4" style="display: none;">
                    <h2 class="lead fs-3 fw-bold mb-4">Bebidas</h2>
                    <div id="cartas-tipo-4" class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4"> </div>
                    <hr>
                </div>
            </div>

            <!--Modal-->
            <div class="modal fade" id="modal-objetivo" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog" style="color:black">
                    <div class="modal-content">
                        <!-- Cabecera del modal -->
                        <div class="modal-header" style="background-color: #F7BA00;">
                            <h1 class="modal-title fs-5">Plato</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <!-- Formulario -->
                        <div class="modal-body">
                            <form class="row g-3" method="post" action="controller.php" id="frm-insert-update"
                                enctype="multipart/form-data" novalidate>
                                <!-- Input hidden para el código id -->
                                <input type="hidden" class="form-control" name="id-plato" readonly id="txtId" value="0">
                                <!-- Input Nombre -->
                                <div class="col-12">
                                    <label for="txtNombre" class="form-label fw-bold">Nombre del plato</label>
                                    <input type="text" class="form-control" name="nombre" id="txtNombre"
                                        pattern=".{5,50}" required autofocus>
                                    <div class="invalid-feedback">
                                        Introduzca un nombre válido, de entre 5-50 caracteres.
                                    </div>
                                </div>
                                <!-- Input Descripción -->
                                <div class="col-12">
                                    <label for="txaDescripcion" class="form-label fw-bold">Descripción</label>
                                    <textarea class="form-control" name="descripcion" rows="3" id="txaDescripcion"
                                        required autofocus></textarea>
                                    <div class="invalid-feedback">
                                        Introduzca una descripción.
                                    </div>
                                </div>
                                <!-- Input Categoría -->
                                <div class="col-md-6">
                                    <label for="cbxCategoria" class="form-label fw-bold">Categoría</label>
                                    <select class="form-select" id="cbxCategoria" name="id-categoria" required>
                                        <option selected disabled value="">Seleccione...</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        Debe seleccionar una Categoría.
                                    </div>
                                </div>
                                <!-- Input Precio -->
                                <div class="col-md-6">
                                    <label for="txtPrecio" class="form-label fw-bold">Precio regular</label>
                                    <input type="number" class="form-control" name="precio" id="txtPrecio" step="0.01"
                                        min="0.50" max="500.0" required autofocus>
                                    <div class="invalid-feedback">
                                        Debe ingresar un valor numérico entre 0.50 y 500, con máximo dos cifras
                                        decimales.
                                    </div>
                                </div>

                                <!-- Input Descuento general -->
                                <div class="col-md-6">
                                    <label for="txtDescuentoG" class="form-label fw-bold">Descuento general</label>
                                    <input type="number" class="form-control" name="descuento-general"
                                        id="txtDescuentoG" min="0" max="100" value="0" required autofocus>
                                    <div class="invalid-feedback">
                                        Debe ingresar un valor entero entre 0 y 100.
                                    </div>
                                </div>

                                <!-- Input Descuento por fidelidad -->
                                <div class="col-md-6">
                                    <label for="txtDescuentoF" class="form-label fw-bold">Descuento por
                                        fidelidad</label>
                                    <input type="number" class="form-control" name="descuento-fidelidad"
                                        id="txtDescuentoF" max="100" value="0" required autofocus>
                                    <!-- El valor mínimo se configura desde JS -->
                                    <div class="invalid-feedback">
                                        Debe ingresar un valor entero entre 0 y 100, mayor que el descuento general.
                                    </div>
                                </div>

                                <!-- Input Imagen -->
                                <div class="col-12">
                                    <label for="fileImagen" class="form-label fw-bold">Seleccionar imagen</label>
                                    <input type="file" class="form-control" name="imagen" id="fileImagen"
                                        accept=".png, .jpg, .jpeg" pattern="/(\.jpg|\.jpeg|\.png)$/" required>
                                    <div class="invalid-feedback">
                                        Debe seleccionar o arrastrar un archivo con formato de imagen válido (.PNG, .JPG
                                        o .JPEG) y menor a 500 KB.
                                    </div>
                                </div>
                                <div>
                                    <img id="imgPreview" class="img-thumbnail" src="">
                                </div>
                                <input type="hidden" id="txtNombreImagen" name="nombre-imagen" value="">

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
        <input type="hidden" id="txtIdEliminar" name="id-plato">
        <!-- Tipo de operacion CRUD -->
        <input type="hidden" name="tipo-crud" value="delete">
        <!-- La acción submit se hace mediante JQuery -->
    </form>

    <!-- Formulario artificial con el propósito de realizar insert en Plato del día -->
    <form action="controller.php" method="post" id="frm-plato-dia">
        <!-- Argumento para el insert (codigo) -->
        <input type="hidden" id="txtIdPlatoDia" name="id-plato">
        <!-- Tipo de operacion CRUD -->
        <input type="hidden" name="tipo-crud" value="plato-dia">
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
                msg("Operación exitosa", "Plato eliminado", "success");
                break;
            case 2:
                msg("Operación exitosa", "Plato agregado", "success");
                break;
            case 3:
                msg("Operación exitosa", "Plato actualizado", "success");
                break;
            case 4:
                msg("Operación exitosa", "Se actualizó el plato del día", "success");
                break;
            case -1:
                msg("Operación fallida", "No se pudo eliminar el plato", "error");
                break;
            case -2:
                msg("Operación fallida", "El archivo subido no es una imagen", "error");
                break;
            case -3:
                msg("Operación fallida", "El archivo subido ya existe. Intente cambiar el nombre", "error");
                break;
            case -4:
                msg("Operación fallida", "El archivo subido es muy grande", "error");
                break;
            case -5:
                msg("Operación fallida", "No se permite esta extensión de imagen", "error");
                break;
            case -6:
                msg("Operación fallida", "Ocurrió un error al subir la imagen", "error");
                break;
            case -7:
                msg("Operación fallida", "No se pudo agregar el plato", "error");
                break;
            case -8:
                msg("Operación fallida", "No se pudo actualizar el plato", "error");
                break;
            case -9:
                msg("Operación fallida", "No se pudo actualizar el plato del día", "error");
                break;
            case -10:
                msg("Operación fallida", "Hubo un error", "error");
                break;
        }
    }

    // Eliminar mensaje de la sesión
    unset($_SESSION["mensaje"]);
    ?>

    <!-- Llenar dinamicamente el combo de Categoría en el modal -->
    <script>
        $.get("json/list_categoria.php",
            function (response) {
                response.forEach(categoria => {
                    $('#cbxCategoria').append(`<option value='${categoria.id}'>${categoria.nombre}</option>`);
                });
            });
    </script>

    <!-- Asignar evento change al combo de filtro -->
    <script>
        // Función
        const mostrarPlatos = function () {
            // Recuperar selección del combo
            let tipo = $('#cbx-filtro').val();

            // Esconder y vaciar los contenedores
            $('#contenedor-tipo-1').hide();
            $('#contenedor-tipo-2').hide();
            $('#contenedor-tipo-3').hide();
            $('#contenedor-tipo-4').hide();
            $('#cartas-tipo-1').html("");
            $('#cartas-tipo-2').html("");
            $('#cartas-tipo-3').html("");
            $('#cartas-tipo-4').html("");

            // Consulta asíncrona
            $.post("json/list_plato_by_categoria.php",
                { tipo: tipo },
                function (response) {

                    $.each(response, function (index, item) {
                        // Mostrar el contenedor
                        $(`#contenedor-tipo-${item.plato.tipo_categoria}`).show();

                        // Llenar contenido
                        $(`#cartas-tipo-${item.plato.tipo_categoria}`).append(
                            `${item.carta}`
                        );
                    });
                });
        };

        // Llamar a la función al iniciar la página
        mostrarPlatos();

        // Evento
        $(document).on("change", "#cbx-filtro", mostrarPlatos);        
    </script>

    <!-- Asignar evento click al botón Nuevo -->
    <script>
        $(document).on("click", ".btn-nuevo", function () {
            // Cambiar título del modal
            $('.modal-title').html('Nuevo Plato');

            // Limpiar los inputs
            $("#txtId").val("0");
            $("#txtNombre").val("");
            $("#txaDescripcion").val("");
            $("#cbxCategoria").val("");
            $("#txtPrecio").val("");
            $("#txtDescuentoG").val("0");
            $("#txtDescuentoF").val("0");
            $("#fileImagen").attr("required", true); // Es obligatorio subir una nueva imagen al crear
            $("#txtNombreImagen").val("");
            $("#imgPreview").attr("src", "");
        });
    </script>

    <!-- Asignar evento click a los botones Editar -->
    <script>
        $(document).on("click", ".btn-editar", function () {
            // Cambiar título del modal
            $('.modal-title').html('Editar Plato');

            // Recuperar el ID en el componente donde está el botón
            let id = $(this).parent("div").find(".hidden-id")[0].innerHTML;

            // Consulta asíncrona
            $.post("json/find_plato.php",
                { id_plato: id },
                function (response) {
                    // Mostrar en el formulario el valor de las variables
                    $("#txtId").val(response.id);
                    $("#txtNombre").val(response.nombre);
                    $("#txaDescripcion").val(response.descripcion);
                    $("#cbxCategoria").val(response.id_categoria);
                    $("#txtPrecio").val(response.precio_regular);
                    $("#txtDescuentoG").val(response.descuento_general);
                    $("#txtDescuentoF").val(response.descuento_fidelidad);
                    $("#fileImagen").removeAttr("required"); // No es obligatorio subir una nueva imagen al editar
                    $("#txtNombreImagen").val(response.imagen);
                    $("#imgPreview").attr("src", "../../multimedia/imagenes/platos/" + response.imagen);
                });
        });
    </script>

    <!-- Asignar evento click a los botones Eliminar -->
    <script>
        $(document).on("click", ".btn-eliminar", function () {
            // Recuperar el ID en el componente donde está el botón
            let id = $(this).parent("div").find(".hidden-id")[0].innerHTML;

            // Mostrar en las cajas el valor de las variables
            $("#txtIdEliminar").val(id);
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: "btn btn-success me-4",
                    cancelButton: "btn btn-danger me-4"
                },
                buttonsStyling: false
            });
            swalWithBootstrapButtons.fire({
                title: "¿Estás seguro de eliminar este plato?",
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
                        text: "No se eliminó el plato",
                        icon: "error"
                    });
                }
            });
        });
    </script>

    <!-- Asignar evento click a los botones Plato del día -->
    <script>
        $(document).on("click", ".btn-plato-dia", function () {
            // Recuperar el ID en el componente donde está el botón
            let id = $(this).parent("div").find(".hidden-id")[0].innerHTML;

            // Mostrar en las cajas el valor de las variables
            $("#txtIdPlatoDia").val(id);

            // Hacer submit en el formulario #frm-plato-dia
            $("#frm-plato-dia").submit();
        });
    </script>

    <!-- Permitir arrastrar imagen al área del input file-->
    <script>
        let file_input = document.getElementById('fileImagen');
        let img_preview = document.getElementById('imgPreview');
        // Agregar eventos drag & drop
        img_preview.addEventListener("dragenter", e => { e.stopPropagation(); e.preventDefault(); }, false);
        img_preview.addEventListener("dragover", e => { e.stopPropagation(); e.preventDefault(); }, false);
        img_preview.addEventListener("drop", e => {
            e.stopPropagation(); e.preventDefault();
            const dt = e.dataTransfer;
            const files = dt.files;
            handleFiles(files);
        }, false);
    </script>

    <!-- Validaciones -->
    <script>
        // Configurar valor mínimo de Descuento por fidelidad: debe ser mayor al Descuento general
        let txtDesF = document.getElementById("txtDescuentoF");
        let txtDesG = document.getElementById("txtDescuentoG");
        txtDesF.addEventListener("input", event => {
            txtDesF.min = txtDesG.value;
        });
        txtDesG.addEventListener("input", event => {
            txtDesF.min = txtDesG.value;
        });

        // Validar input de imagen        
        file_input.addEventListener("change", event => {
            // Regex de los formatos válidos
            let regex = /(\.jpg|\.jpeg|\.png)$/i;
            // Validar el formato del archivo
            if (!regex.test(file_input.value)) {
                file_input.value = '';
                return false;
            }
            else if (file_input.files && file_input.files[0]) {
                // Mostrar el preview de la imagen seleccionada
                let reader = new FileReader();
                reader.onload = function (e) { img_preview.src = e.target.result; };
                reader.readAsDataURL(file_input.files[0]);
            }
        });

        // Desactivar el submit del formulario si presenta inputs inválidos
        let form = document.getElementById("frm-insert-update");
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