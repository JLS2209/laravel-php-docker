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

// Si no existe $_SESSION["pedido"], expulsar de la página
if (!isset($_SESSION["pedido"])) {
    error_log("Intento de acceder a pedido/platos sin pasar por las secciones previas de pedido.");
    header("location: ../../");
    return;
}

// Acceder a la clase Conectar
include "../../cls_conectar/cls_Conectar.php";
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
    </style>
</head>

<body>
    <!-- Incluir Barra de menús de navegación -->
    <?php include "../../templates/nav.php"; ?>

    <!-- Cuerpo de la página -->
    <div class="container">
        <div class="card flex-row my-5 border-0 shadow rounded-3 overflow-hidden">
            <div class="card-body p-4 p-sm-5">
                <!-- Título -->
                <h2 class="card-title text-center mb-5 fw-light">Seleccione sus Platos</h2>

                <hr>

                <div class="row g-4">
                    <!-- Filtrar componentes por nombre -->
                    <div class="col-md-4">
                        <label for="txt-filtro-nombre" class="form-label">Busque platos por nombre:</label>
                        <input type="text" class="form-control" id="txt-filtro-nombre" placeholder="Nombre del plato">
                        <button id="btn-filtro-nombre" class="btn btn-success w-100 mt-3">Consultar</button>
                    </div>
                    <!-- Filtrar componentes por categoria -->
                    <div class="col-md-4">
                        <label for="cbx-filtro-categoria" class="form-label">O por categoría:</label>
                        <select class="form-select" id="cbx-filtro-categoria">
                            <option value="0" selected>Todos</option>
                        </select>
                        <button id="btn-filtro-categoria" class="btn btn-success w-100 mt-3">Consultar</button>
                    </div>
                    <!-- Filtrar componentes por precio -->
                    <div class="col-md-4">
                        <label for="txt-filtro-precio" class="form-label">O desde el precio de:</label>
                        <input type="number" class="form-control" id="txt-filtro-precio" min="0">
                        <button id="btn-filtro-precio" class="btn btn-success w-100 mt-3">Consultar</button>
                    </div>
                </div>

                <hr>


                <!-- Formulario / Lista de Items -->
                <form class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4" method="post"
                    action="controller-platos.php" id="frm-platos" novalidate>
                </form>

                <hr>

                <div class="row g-3">
                    <!-- Botones -->
                    <div class="col-6 text-center">
                        <a href="./" class="btn btn-danger btn-lg px-5 py-2">VOLVER SIN GUARDAR CAMBIOS</a>
                    </div>
                    <div class="col-6 text-center">
                        <button type="submit" form="frm-platos" class="btn btn-success btn-lg px-5 py-2">GUARDAR Y
                            SEGUIR</button>
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

    <!-- Llenar dinamicamente el combo de filtro de Categoría -->
    <script>
        // Consulta asíncrona
        $.post("json/list_categoria.php",
            function (response) {
                // Agregar opciones al combo de filtro de categorías
                response.forEach(categoria => {
                    $('#cbx-filtro-categoria').append(`<option value='${categoria.id}'>${categoria.nombre}</option>`);
                });
            });
    </script>

    <!-- Asignar evento click al botón de filtro por nombre -->
    <script>
        // Función de filtro
        const filtrarPlatos = function () {
            // Recuperar input
            const filtro = $("#txt-filtro-nombre").val();

            // Limpiar filtro alternativo
            $("#cbx-filtro-categoria").val("0");
            $("#txt-filtro-precio").val("0");

            // Vaciar el contenedor
            $("#frm-platos").html("");

            // Consulta asíncrona
            $.post("json/list_plato_by_nombre.php",
                {
                    nombre: filtro
                },
                function (response) {
                    $.each(response, function (index, item) {
                        // Llenar contenedor
                        $("#frm-platos").append(`${item.tarjeta}`);
                    });
                });

        };

        // Llamar a la función al iniciar la página
        filtrarPlatos();

        // Evento
        $(document).on("click", "#btn-filtro-nombre", filtrarPlatos);
    </script>

    <!-- Asignar evento click al botón de filtro por categoria -->
    <script>
        $(document).on("click", "#btn-filtro-categoria", function () {
            // Recuperar input
            const filtro = $("#cbx-filtro-categoria").val();

            // Limpiar filtro alternativo
            $("#txt-filtro-nombre").val("");
            $("#txt-filtro-precio").val("0");

            // Vaciar el contenedor
            $("#frm-platos").html("");

            // Consulta asíncrona
            $.post("json/list_plato_by_categoria.php",
                {
                    id_categoria: filtro
                },
                function (response) {
                    $.each(response, function (index, item) {
                        // Llenar contenedor
                        $("#frm-platos").append(`${item.tarjeta}`);
                    });
                });
        });
    </script>

    <!-- Asignar evento click al botón de filtro por precio -->
    <script>
        $(document).on("click", "#btn-filtro-precio", function () {
            // Recuperar input
            const filtro = $("#txt-filtro-precio").val();

            // Limpiar filtro alternativo
            $("#txt-filtro-nombre").val("");
            $("#cbx-filtro-categoria").val("0");

            // Vaciar el contenedor
            $("#frm-platos").html("");

            // Consulta asíncrona
            $.post("json/list_plato_by_precio.php",
                {
                    precio: filtro
                },
                function (response) {
                    $.each(response, function (index, item) {
                        // Llenar contenedor
                        $("#frm-platos").append(`${item.tarjeta}`);
                    });
                });
        });
    </script>

    <!-- Asignar evento change a los checkbox de item -->
    <script>
        $(document).on("change", ".chk-item", function () {
            // Recuperar estado del check
            const isChecked = this.checked;

            // Activar o desactivar inputs según selección
            $(this).parents(".inputs-item").find(".txt-cantidad-plato").attr('disabled', !isChecked);

            // Dar o quitar atributo name y required para los inputs
            if (isChecked) {
                $(this).parents(".inputs-item").find(".txt-id-plato").attr('name', 'id-plato[]');
                $(this).parents(".inputs-item").find(".txt-precio-un-plato").attr('name', 'precio-un-plato[]');
                $(this).parents(".inputs-item").find(".txt-nombre-plato").attr('name', 'nombre-plato[]');
                $(this).parents(".inputs-item").find(".txt-cantidad-plato").attr('name', 'cantidad-plato[]');
                $(this).parents(".inputs-item").find(".txt-cantidad-plato").attr('required', true);
                $(this).parents(".inputs-item").find(".txt-cantidad-plato").val('1');
            } else {
                $(this).parents(".inputs-item").find(".txt-id-plato").removeAttr('name');
                $(this).parents(".inputs-item").find(".txt-precio-un-plato").removeAttr('name');
                $(this).parents(".inputs-item").find(".txt-nombre-plato").removeAttr('name');
                $(this).parents(".inputs-item").find(".txt-cantidad-plato").removeAttr('name');
                $(this).parents(".inputs-item").find(".txt-cantidad-plato").removeAttr('required');
                $(this).parents(".inputs-item").find(".txt-cantidad-plato").val('0');
            }
        });
    </script>

    <!-- Validaciones -->
    <script>
        // Desactivar el submit del formulario si presenta inputs inválidos
        let form = document.getElementById("frm-platos");
        form.addEventListener("submit", event => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();

                Swal.fire({
                    icon: "warning",
                    title: "Advertencia",
                    text: "Debe corregir algunas de las cantidades ingresadas",
                });
            }
            form.classList.add('was-validated');
        }, false);
    </script>

</body>

</html>