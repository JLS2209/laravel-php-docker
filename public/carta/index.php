<?php
// Iniciar o reanudar sesión
session_start();

// El acceso no requiere credenciales, aunque debería mostrarse solo a clientes

// Acceder a la clase Conectar
include "../cls_conectar/cls_Conectar.php";

// Acceder a la clase Cliente y su controlador
include "../modelo/cls_Ubicacion.php";
include "../modelo/cls_Cliente.php";
include "../controlador/ctrl_Cliente.php";

// Diferenciar si el usuario es REGULAR (descuento normal) o ESPECIAL(descuento por fidelidad)
// -- Recupera el id_rol de la sesión (por defecto -> 1:invitado)
$rol = isset($_SESSION["id_rol"]) ? $_SESSION["id_rol"] : 1;
// -- Recupera el número de cliente/empleado de la sesión (por defecto -> -1)
$nro = isset($_SESSION["nro"]) ? $_SESSION["nro"] : -1;
// -- Si el rol de usuario no es cliente registrado (rol=2), se considera que es REGULAR
$is_cliente_regular = true;
if ($rol == 2) {
    // Recuperar el cliente registrado y su grado de fidelidad
    $ctrlCliente = new ControladorCliente();
    $cliente = $ctrlCliente->show($nro);
    $is_cliente_regular = $cliente->is_regular();
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
    <?php include "../templates/nav.php"; ?>

    <!-- Cuerpo de la página -->
    <div class="container-fluid"
        style="background-image: url('../multimedia/imagenes/fondo.jpg'); background-size: 100%;">
        <div class="container p-4" style="background-color: rgba(0, 0, 0, 0.8); color: wheat;">

            <h1 class="text-center mb-4">LA CARTA</h1>

            <hr>

            <div class="row justify-content-between g-5 px-4 mb-4">
                <!-- Filtro de consulta por tipo -->
                <div class="col-md-3">
                    <label for="cbx-filtro-tipo" class="form-label fs-6">Seleccione un tipo de plato:</label>
                    <select class=" form-select" id="cbx-filtro-tipo">
                        <option value="0">Todos</option>
                        <option value="1">Entradas</option>
                        <option value="2">Platos principales</option>
                        <option value="3">Postres</option>
                        <option value="4">Bebidas</option>
                    </select>
                    <button id="btn-filtro-tipo" class="btn btn-success w-100 mt-3">Consultar</button>
                </div>
                <!-- Filtro de consulta por categoría -->
                <div class="col-md-3">
                    <label for="cbx-filtro-categoria" class="form-label fs-6">O seleccione una categoría:</label>
                    <select class=" form-select" id="cbx-filtro-categoria">
                        <option value="0">Todos</option>
                    </select>
                    <button id="btn-filtro-categoria" class="btn btn-success w-100 mt-3">Consultar</button>
                </div>
                <!-- Filtro de consulta por nombre -->
                <div class="col-md-3">
                    <label for="txt-filtro-nombre" class="form-label fs-6">O ingrese un nombre:</label>
                    <input type="text" class="form-control" id="txt-filtro-nombre" placeholder="Nombre del plato">
                    <button id="btn-filtro-nombre" class="btn btn-success w-100 mt-3">Consultar</button>
                </div>
                <!-- Filtro de consulta por telefono -->
                <div class="col-md-3">
                    <label for="txt-filtro-precio" class="form-label fs-6">O desde el precio regular:</label>
                    <input type="number" class="form-control" id="txt-filtro-precio" value="0">
                    <button id="btn-filtro-precio" class="btn btn-success w-100 mt-3">Consultar</button>
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

            <!-- Botón Descargar Carta -->
            <div class="col-md-6 d-flex align-items-center mx-auto">
                <button class="btn btn-primary btn-lg w-100 h-50" id="btn-descargar" type="button" data-bs-toggle="modal"
                    data-bs-target="#modal-objetivo">DESCARGAR CARTA</button>
            </div>

            <!--Modal-->
            <div class="modal fade" id="modal-objetivo" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog" style="color:black">
                    <div class="modal-content">
                        <!-- Cabecera del modal -->
                        <div class="modal-header" style="background-color: #F7BA00;">
                            <h1 class="modal-title fs-5">Descargar Carta</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Formulario E-mail -->
                            <form class="row g-3 mb-4" method="post" action="mail-controller.php">
                                <!-- Input E-mail -->
                                <div class="col-12">
                                    <label for="txtEmail" class="form-label fw-bold">Ingrese el e-mail a donde
                                        enviaremos la carta</label>
                                    <input type="text" class="form-control" name="email" id="txtEmail" required
                                        value="u22249301@utp.edu.pe">
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-success w-100">Enviar PDF</button>
                                </div>
                            </form>
                            <!-- PDF iframe -->
                            <iframe id="pdf-carta" width="100%" height="500px" frameborder="0"></iframe>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>            

        </div>
    </div>

    <!-- Incluir Pie de página -->
    <?php include "../templates/footer.php"; ?>

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
                msg("Operación exitosa", "El mensaje ha sido enviado!", "success");
                break;
            case -1:
                msg("Operación fallida", "No se pudo enviar el mensaje.", "error");
                break;
        }
    }
    
    // Eliminar mensaje de la sesión
    unset($_SESSION["mensaje"]);
    ?>

    <!-- Llenar dinamicamente el filtro de Categoría -->
    <script>
        $.get("json/list_categoria.php",
            function (response) {
                response.forEach(categoria => {
                    $('#cbx-filtro-categoria').append(`<option value='${categoria.id}'>${categoria.nombre}</option>`);
                });
            });
    </script>

    <!-- Asignar evento click al botón de filtro por tipo -->
    <script>
        // Función
        const mostrarPlatos = function () {
            // Recuperar selección del combo
            let tipo = $('#cbx-filtro-tipo').val();

            // Limpiar filtros alternativos
            $('#cbx-filtro-categoria').val("0");
            $('#txt-filtro-nombre').val("");
            $('#txt-filtro-precio').val("0");

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
            $.post("json/list_plato_by_tipo.php",
                {
                    tipo: tipo,
                    is_regular: <?php echo $is_cliente_regular ? "1" : "0"; ?>
                },
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
        $(document).on("click", "#btn-filtro-tipo", mostrarPlatos);        
    </script>

    <!-- Asignar evento click al botón de filtro por categoria -->
    <script>
        $(document).on("click", "#btn-filtro-categoria", function () {
            // Recuperar selección del combo
            let categoria = $('#cbx-filtro-categoria').val();

            // Limpiar filtros alternativos
            $('#cbx-filtro-tipo').val("0");
            $('#txt-filtro-nombre').val("");
            $('#txt-filtro-precio').val("0");

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
                {
                    categoria: categoria,
                    is_regular: <?php echo $is_cliente_regular ? "1" : "0"; ?>
                },
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
        });        
    </script>

    <!-- Asignar evento click al botón de filtro por nombre -->
    <script>
        $(document).on("click", "#btn-filtro-nombre", function () {
            // Recuperar selección del combo
            let nombre = $('#txt-filtro-nombre').val();

            // Limpiar filtros alternativos
            $('#cbx-filtro-tipo').val("0");
            $('#cbx-filtro-categoria').val("0");
            $('#txt-filtro-precio').val("0");

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
            $.post("json/list_plato_by_nombre.php",
                {
                    nombre: nombre,
                    is_regular: <?php echo $is_cliente_regular ? "1" : "0"; ?>
                },
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
        });        
    </script>

    <!-- Asignar evento click al botón de filtro por precio -->
    <script>
        $(document).on("click", "#btn-filtro-precio", function () {
            // Recuperar selección del combo
            let precio = $('#txt-filtro-precio').val();

            // Limpiar filtros alternativos
            $('#cbx-filtro-tipo').val("0");
            $('#cbx-filtro-categoria').val("0");
            $('#txt-filtro-nombre').val("");

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
            $.post("json/list_plato_by_precio.php",
                {
                    precio: precio,
                    is_regular: <?php echo $is_cliente_regular ? "1" : "0"; ?>
                },
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
        });        
    </script>

    <!-- Asignar eventos click al botón de Descargar PDF-->
    <script>
        $(document).on("click", "#btn-descargar", function () {
            // Muestra el PDF
            $('#pdf-carta').attr('src', "pdf-carta.php");    
        });
    </script>
</body>

</html>