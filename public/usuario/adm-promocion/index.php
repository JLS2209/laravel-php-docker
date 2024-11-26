<?php
// Iniciar o reanudar sesión
session_start();

// Restringir acceso si el id_rol no corresponde a empleado administrador (4)
if (!(isset($_SESSION["id_rol"]) && $_SESSION["id_rol"] == 4)) {
    error_log("Intento de acceso sin credenciales adecuadas de administrador!");
    header("location: ../../");
}

// Eliminar variable de sesión "id_promocion" si existe
// Esto ocurre al regresar de la página editar items
unset($_SESSION["id_promocion"]);

// Acceder a la clase Conectar
include "../../cls_conectar/cls_Conectar.php";

// Acceder a la clase Promocion y su controlador
include "../../modelo/cls_Promocion.php";
include "../../controlador/ctrl_Promocion.php";
$ctrl = new ControladorPromocion();
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

            <h1 class="text-center mb-4">REGISTRO DE PROMOCIONES</h1>

            <hr>

            <div class="row justify-content-between g-5 px-4 mb-4">
                <!-- Filtro de consulta por precio -->
                <div class="col-md-4">
                    <label for="txt-filtro-precio" class="form-label fs-5">Desde el precio de:</label>
                    <input type="number" class="form-control" id="txt-filtro-precio" value="0">
                    <button id="btn-filtro-precio" class="btn btn-success w-100 mt-3">Consultar</button>
                </div>
                <!-- Filtro de consulta por nombre -->
                <div class="col-md-4">
                    <label for="txt-filtro-nombre" class="form-label fs-5">O ingrese un nombre:</label>
                    <input type="text" class="form-control" id="txt-filtro-nombre" placeholder="Nombre de la promoción">
                    <button id="btn-filtro-nombre" class="btn btn-success w-100 mt-3">Consultar</button>
                </div>
                <!-- Botón Nueva Promoción -->
                <div class="col-md-3 d-flex align-items-center">
                    <button class="btn btn-primary btn-lg btn-nuevo w-100" type="button" data-bs-toggle="modal"
                        data-bs-target="#modal-objetivo">Nueva Promoción</button>
                </div>
            </div>

            <hr>

            <!-- Contenedor de promociones. El llenado es dinámico desde JS -->
            <div class="px-4" id="contenedor-promociones">
            </div>

            <!--Modal-->
            <div class="modal fade" id="modal-objetivo" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog" style="color:black">
                    <div class="modal-content">
                        <!-- Cabecera del modal -->
                        <div class="modal-header" style="background-color: #F7BA00;">
                            <h1 class="modal-title fs-5">Promoción</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <!-- Formulario -->
                        <div class="modal-body">
                            <form class="row g-3" method="post" action="controller.php" id="frm-insert-update"
                                enctype="multipart/form-data" novalidate>
                                <!-- Input hidden para el código id -->
                                <input type="hidden" class="form-control" name="id-promocion" readonly id="txtId"
                                    value="0">
                                <!-- Input Nombre -->
                                <div class="col-12">
                                    <label for="txtNombre" class="form-label fw-bold">Nombre de la promoción</label>
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
                                <!-- Input Cantidad -->
                                <div class="col-md-6">
                                    <label for="txtCantidad" class="form-label fw-bold">Cantidad máxima por
                                        pedido</label>
                                    <input type="number" class="form-control" name="cantidad-max" id="txtCantidad"
                                        min="1" max="10" required autofocus>
                                    <div class="invalid-feedback">
                                        Debe ingresar un número entre 1 y 10.
                                    </div>
                                </div>

                                <!-- Input Descuento -->
                                <div class="col-md-6">
                                    <label for="txtDescuento" class="form-label fw-bold">Descuento promocional</label>
                                    <input type="number" class="form-control" name="descuento" id="txtDescuento" min="1"
                                        max="100" value="0" required autofocus>
                                    <div class="invalid-feedback">
                                        Debe ingresar un valor entero entre 1 y 100.
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
                                <input type="hidden" name="tipo-crud" value="insert-update">

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
        <input type="hidden" id="txtIdEliminar" name="id-promocion">
        <!-- Tipo de operacion CRUD -->
        <input type="hidden" name="tipo-crud" value="delete">
        <!-- La acción submit se hace mediante JQuery -->
    </form>

    <!-- Formulario artificial para abrir la página de editarItems.php con el ID de promoción -->
    <form action="controller.php" method="post" id="frm-editar-items">
        <!-- Argumento para el insert (codigo) -->
        <input type="hidden" id="txtIdItems" name="id-promocion">
        <!-- Tipo de operacion CRUD -->
        <input type="hidden" name="tipo-crud" value="editar-items">
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
                msg("Operación exitosa", "Promoción eliminada", "success");
                break;
            case 2:
                msg("Operación exitosa", "Promoción agregada", "success");
                break;
            case 3:
                msg("Operación exitosa", "Promoción actualizada", "success");
                break;
            case -1:
                msg("Operación fallida", "No se pudo eliminar la promoción", "error");
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
                msg("Operación fallida", "No se pudo crear la promoción", "error");
                break;
            case -8:
                msg("Operación fallida", "No se pudo actualizar la promoción", "error");
                break;
            case -10:
                msg("Operación fallida", "Hubo un error", "error");
                break;
        }
    }

    // Eliminar mensaje de la sesión
    unset($_SESSION["mensaje"]);
    ?>

    <!-- Asignar evento click al botón de filtro por precio-->
    <script>
        // Función
        const mostrarPromociones = function () {
            // Recuperar selección del combo
            let precio = $('#txt-filtro-precio').val();

            // Limpiar el filtro alternativo
            $('#txt-filtro-nombre').val("");

            // Vaciar el contenedor
            $('#contenedor-promociones').html("");

            // Consulta asíncrona
            $.post("json/list_promocion_by_precio.php",
                { precio: precio },
                function (response) {

                    $.each(response, function (index, item) {
                        // Llenar contenido
                        $('#contenedor-promociones').append(
                            `${item.carta}`
                        );
                    });
                });
        };

        // Llamar a la función al iniciar la página
        mostrarPromociones();

        // Evento
        $(document).on("click", "#btn-filtro-precio", mostrarPromociones);        
    </script>

    <!-- Asignar evento click al botón de filtro por nombre -->
    <script>
        $(document).on("click", "#btn-filtro-nombre", function () {
            // Recuperar selección del combo
            let nombre = $('#txt-filtro-nombre').val();

            // Limpiar el filtro alternativo
            $('#txt-filtro-precio').val("0");

            // Vaciar el contenedor
            $('#contenedor-promociones').html("");

            // Consulta asíncrona
            $.post("json/list_promocion_by_nombre.php",
                { nombre: nombre },
                function (response) {

                    $.each(response, function (index, item) {
                        // Llenar contenido
                        $('#contenedor-promociones').append(
                            `${item.carta}`
                        );
                    });
                });
        });        
    </script>

    <!-- Asignar evento click al botón Nuevo -->
    <script>
        $(document).on("click", ".btn-nuevo", function () {
            // Cambiar título del modal
            $('.modal-title').html('Nueva Promoción');

            // Limpiar los inputs
            $("#txtId").val("0");
            $("#txtNombre").val("");
            $("#txaDescripcion").val("");
            $("#txtCantidad").val("0");
            $("#txtDescuento").val("0");
            $("#fileImagen").attr("required", true); // Es obligatorio subir una nueva imagen al crear
            $("#txtNombreImagen").val("");
            $("#imgPreview").attr("src", "");
        });
    </script>

    <!-- Asignar evento click a los botones Editar Descripción -->
    <script>
        $(document).on("click", ".btn-editar-prom", function () {
            // Cambiar título del modal
            $('.modal-title').html('Editar Promoción');

            // Recuperar el ID en el componente donde está el botón
            let id = $(this).parent("div").find(".hidden-id")[0].innerHTML;

            // Consulta asíncrona
            $.post("json/find_promocion.php",
                { id_promocion: id },
                function (response) {
                    // Mostrar en el formulario el valor de las variables
                    $("#txtId").val(response.id);
                    $("#txtNombre").val(response.nombre);
                    $("#txaDescripcion").val(response.descripcion);
                    $("#txtCantidad").val(response.cantidad_max);
                    $("#txtDescuento").val(response.descuento);
                    $("#fileImagen").removeAttr("required"); // No es obligatorio subir una nueva imagen al editar
                    $("#txtNombreImagen").val(response.imagen);
                    $("#imgPreview").attr("src", "../../multimedia/imagenes/promociones/" + response.imagen);
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
                title: "¿Estás seguro de eliminar esta promoción?",
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
                        text: "No se eliminó la promoción",
                        icon: "error"
                    });
                }
            });
        });
    </script>

    <!-- Asignar evento click a los botones Editar items -->
    <script>
        $(document).on("click", ".btn-editar-items", function () {
            // Recuperar el ID en el componente donde está el botón
            let id = $(this).parent("div").find(".hidden-id")[0].innerHTML;

            // Mostrar en las cajas el valor de las variables
            $("#txtIdItems").val(id);

            // Hacer submit en el formulario #frm-plato-dia
            $("#frm-editar-items").submit();
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