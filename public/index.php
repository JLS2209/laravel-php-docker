<?php
// Acceder al modelo de clase Pedido
include "./modelo/cls_Pedido.php";

// Acceder a la clase Cliente y su controlador
include "./modelo/cls_Cliente.php";
include "./modelo/cls_Ubicacion.php";
include "./controlador/ctrl_Cliente.php";

// Iniciar o reanudar sesión
@session_start();

// El acceso no requiere credenciales

// En caso la página haya sido redirigida desde el botón de logout
if (isset($_POST['logout']) && $_POST['logout'] == 1) {
    // Eliminar variables de la sesión
    session_unset();
}

// Se recuperan las siguientes variables para llenar la tarjeta "Plato del día"
// -- Recupera el id_rol de la sesión (por defecto -> 1:invitado)
$rol = isset($_SESSION["id_rol"]) ? $_SESSION["id_rol"] : 1;
// -- Recupera el número de cliente/empleado de la sesión (por defecto -> -1)
$nro = isset($_SESSION["nro"]) ? $_SESSION["nro"] : -1;

// Acceder a la clase Conectar
include "./cls_conectar/cls_Conectar.php";

?>

<!-- Armar contenido del carrito, si existe -->
<?php
$carrito = "<p> Su carrito está vacío </p>";
if (isset($_SESSION["pedido"])) {
    // Invocar búsqueda de cliente para recuperar sus datos
    $nro = isset($_SESSION["nro"]) ? $_SESSION["nro"] : -1;
    $ctrlCliente = new ControladorCliente();
    $cliente = $ctrlCliente->show($nro);

    // Cargar datos de pedido
    $tarjeta = (isset($_SESSION["tarjeta"])) ? $_SESSION["tarjeta"]["numero-tarjeta"] : "";
    $opcion = ($_SESSION["pedido"]->opcion_entrega == 1) ? "Delivery" : "Pickup en tienda";
    $pago = ($_SESSION["pedido"]->metodo_pago == 1) ? "Tarjeta - $tarjeta" : "Efectivo";

    // Cargar costos
    $delivery = $_SESSION["pedido"]->costo_delivery;
    $_SESSION["pedido"]->set_total();
    $total = $_SESSION["pedido"]->total_pagar;

    // Cargar datos de ubicación a partir del Pedido (si existen)
    if ($_SESSION["pedido"]->ubicacion != null) {
        $distrito = $_SESSION["pedido"]->ubicacion->distrito->nombre_distrito;
        $direccion = $_SESSION["pedido"]->ubicacion->direccion;
    } else {
        $distrito = "";
        $direccion = "";
    }

    // Formar lista de items
    $tabla = "";
    foreach ($_SESSION["pedido"]->lista_platos as $item) {
        $nombre_item = $item["nombre_plato"];
        $cantidad_item = $item["cantidad_plato"];
        $precio_item = $item["precio_un_plato"] * $item["cantidad_plato"];
        $tabla .= "
        <tr>
            <td class='ps-3'>$nombre_item</td>
            <td>$cantidad_item</td>
            <td>S/. " . number_format($precio_item, 2) . "</td>
        </tr>
    ";
    }
    foreach ($_SESSION["pedido"]->lista_promociones as $item) {
        $nombre_item = $item["nombre_promocion"];
        $cantidad_item = $item["cantidad_promocion"];
        $precio_item = $item["precio_un_promocion"] * $item["cantidad_promocion"];
        $tabla .= "
        <tr>
            <td class='ps-3'>$nombre_item</td>
            <td>$cantidad_item</td>
            <td>S/. " . number_format($precio_item, 2) . "</td>
        </tr>
    ";
    }

    $carrito = "<p> <strong> Cliente: </strong> $cliente->nombre $cliente->apellido </p>";
    $carrito .= "<p> <strong> Teléfono: </strong> $cliente->telefono </p>";
    $carrito .= "<p> <strong> Método de pago: </strong> $pago </p>";
    $carrito .= "<p> <strong> Opción de entrega: </strong> $opcion </p>";
    $carrito .= "<p> <strong> Dirección de entrega: </strong> $distrito, $direccion </p>";
    $carrito .= "<div class='table-responsive'>
                    <table class='table table-light mt-3'>
                        <thead class='table-info'>
                            <tr>
                                <th scope='col' class='ps-3'>Item</th>
                                <th scope='col'>Cantidad</th>
                                <th scope='col'>Precio subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            $tabla
                        </tbody>
                    </table>
                </div>";
    $carrito .= "<p> <strong> Costo de delivery: </strong>S/. " . number_format($delivery, 2) . "</p>";
    $carrito .= "<p> <strong> Total a pagar: </strong>S/. " . number_format($total, 2) . "</p>";
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

        /* Banner de bienvenida*/
        #banner {
            /*Tamaño y centrado del banner*/
            height: 350px;
            width: 80%;
            margin: 0 auto;
            /*Imagen mostrada en el banner*/
            background-image: url('./multimedia/imagenes/banner.gif');
            background-size: 100%;
            border: #211F1D solid 2px;
            /*Ubica el slogan contenido a la derecha*/
            display: flex;
            justify-content: end;
        }

        /* Contenedor interno del cuerpo de la página */
        .inner-container {
            background-color: rgba(0, 0, 0, 0.8);
            color: wheat;
        }

        /* Efectos al cargar la página */
        .slide-in {
            animation: 1s ease-out 0s 1 slideInFromLeft;
        }

        .fade-in {
            animation: fadein 2s;
        }

        @keyframes slideInFromLeft {
            0% {
                transform: translateX(-100%);
            }

            100% {
                transform: translateX(0);
            }
        }

        @keyframes fadein {
            from {
                opacity: 0
            }

            to {
                opacity: 1
            }
        }

        /* Estilos del Botón carrito */
        .floating-container {
            position: fixed;
            width: 100px;
            height: 100px;
            bottom: 0;
            left: 0;
            margin: 35px 25px;
        }

        .floating-container .floating-button {
            position: absolute;
            width: 65px;
            height: 65px;
            background: black;
            bottom: 0;
            border-radius: 50%;
            border: none;
            left: 0;
            right: 0;
            margin: auto;
            color: white;
            line-height: 65px;
            text-align: center;
            font-size: 23px;
            z-index: 100;
            box-shadow: 0 10px 25px -5px rgba(44, 179, 240, 0.6);
            cursor: pointer;
            -webkit-transition: all 0.3s;
            transition: all 0.3s;
        }
    </style>
</head>

<body style="background-color: #211f1d;">
    <!--SweetAlert JS-->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.1/dist/sweetalert2.all.min.js"></script>
    <!-- En caso la página haya sido redirigida desde el botón de logout -->
    <?php
    if (isset($_POST['logout']) && $_POST['logout'] == 1) {
        // Mostrar mensaje de logout
        echo "
        <script>
            Swal.fire({
                title: 'Hasta luego!',
                text: 'Ha salido de su cuenta.',
                icon: 'success'
            });
        </script>
        ";
        // Eliminar variables de la sesión
        session_unset();
    }
    ?>


    <!-- Incluir Barra de menús de navegación -->
    <?php include "./templates/nav.php"; ?>

    <!-- Cuerpo de la página -->
    <div class="container-fluid fade-in"
        style="background-image: url('./multimedia/imagenes/fondo.jpg'); background-size: 100%;">

        <div class="container py-4 inner-container slide-in" style="">
            <!-- Banner de bienvenida -->
            <div id="banner">
                <div class="me-5 ms-5 p-5" style="background-color: rgba(71, 2, 13, 0.8); width: 250px">
                    <h3><em>Sabores que encienden tus sentidos</em></h3>
                    <p>Una explosión de fuego y sazón en cada bocado</p>
                </div>
            </div>

            <hr style="border: wheat solid 2px; opacity: 1.0; margin: 30px auto; width: 75%">

            <!-- Slider de promociones -->
            <h1 class="text-center mb-4">Aprovecha nuestras promociones</h1>
            <?php include "./templates/slider-prom.php"; ?>

            <hr style="border: wheat solid 2px; opacity: 1.0; margin: 30px auto; width: 75%">

            <!-- Container de reseñas -->
            <h1 class="text-center mb-4">Testimonios de nuestros clientes</h1>
            <div class="container-fluid text-center">
                <div class="row mx-5 gx-5">
                    <div class="col-md">
                        <!-- Ícono -->
                        <i class="fa-solid fa-quote-left fa-3x"></i>
                        <!-- Información -->
                        <p class="fst-italic mt-4">
                            Si te gusta la comida que despierta tus sentidos, este es el lugar. La atención al detalle
                            en cada plato es evidente, y el ambiente acogedor te hace sentir como en casa. El sabor de
                            cada bocado es increíble. ¡Recomendadísimo para los amantes de la buena comida y la
                            parrilla!
                        </p>
                    </div>
                    <div class="col-md">
                        <!-- Ícono -->
                        <i class="fa-solid fa-quote-left fa-3x"></i>
                        <!-- Información -->
                        <p class="fst-italic mt-4">
                            Este restaurante ha sido un gran descubrimiento. El sabor de cada plato es una obra de arte,
                            con una fusión perfecta entre sazón y el uso del fuego. El personal siempre está atento y
                            hacen que la experiencia sea aún mejor. ¡100% recomendable!
                        </p>
                    </div>
                    <div class="col-md">
                        <!-- Ícono -->
                        <i class="fa-solid fa-quote-left fa-3x"></i>
                        <!-- Información -->
                        <p class="fst-italic mt-4">
                            ¡Qué experiencia tan fantástica! El ambiente es acogedor, la comida es deliciosa y la
                            atención es de primera. Probamos varios platos del menú y todos estaban a otro nivel. Se
                            nota la dedicación en cada preparación. Sazón & Fuego realmente enciende los sentidos, tal
                            como lo promete su slogan.
                        </p>
                    </div>
                </div>
            </div>

            <hr style="border: wheat solid 2px; opacity: 1.0; margin: 30px auto; width: 75%">

            <!-- Plato del día -->
            <h1 class="text-center mb-4">Plato del día</h1>
            <?php
            // Acceder a la clase Plato y su controlador
            include "./modelo/cls_Plato.php";
            include "./controlador/ctrl_Plato.php";

            // Recuperar Plato del día a partir de la BD
            $ctrlPlato = new ControladorPlato();
            $plato = $ctrlPlato->show_plato_dia();

            // Diferenciar si el usuario es REGULAR (descuento normal) o ESPECIAL(descuento por fidelidad)
            // -- Si el rol de usuario no es cliente registrado (rol=2), se considera que es REGULAR
            $is_cliente_regular = true;
            if ($rol == 2) {
                // Recuperar el cliente registrado y su grado de fidelidad
                $ctrlCliente = new ControladorCliente();
                $cliente = $ctrlCliente->show($nro);
                $is_cliente_regular = $cliente->is_regular();
            }

            // Genera la carta
            $plato->card_horizontal("./multimedia/imagenes", $is_cliente_regular);
            ?>

        </div>

        <!-- Botón Carrito -->
        <!-- Solo se muestra a los clientes registrados (rol=2) -->
        <?php if ($rol == 2) { ?>
            <div class="floating-container">
                <button class="floating-button" type="button" data-bs-toggle="offcanvas" data-bs-target="#carrito">
                    <i class="fa-solid fa-cart-shopping"></i>
                </button>
            </div>

            <div class="offcanvas offcanvas-end text-bg-dark" tabindex="-1" id="carrito">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title">Carrito de compra</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
                </div>
                <div class="offcanvas-body">
                    <?php echo $carrito ?>
                </div>
            </div>
        <?php } ?>
    </div>

    <!-- Incluir Pie de página -->
    <?php include "./templates/footer.php"; ?>

    <!--JQuery JS-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <!--Bootstrap JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
        crossorigin="anonymous"></script>
    <!--Fontawesome JS-->
    <script src="https://kit.fontawesome.com/1da5200486.js" crossorigin="anonymous"></script>

    <!-- Chatbot -->
    <!-- Solo se muestra a los visitantes (rol=1) y clientes registrados (rol=2) -->
    <?php if ($rol == 1 || $rol == 2) { ?>
        <script>
            window.addEventListener('mouseover', initLandbot, { once: true });
            window.addEventListener('touchstart', initLandbot, { once: true });
            var myLandbot;
            function initLandbot() {
                if (!myLandbot) {
                    var s = document.createElement('script'); s.type = 'text/javascript'; s.async = true;
                    s.addEventListener('load', function () {
                        var myLandbot = new Landbot.Livechat({
                            configUrl: 'https://storage.googleapis.com/landbot.online/v3/H-2687179-MQ6RJ4BXDLSLK94Z/index.json',
                        });
                    });
                    s.src = 'https://cdn.landbot.io/landbot-3/landbot-3.0.0.js';
                    var x = document.getElementsByTagName('script')[0];
                    x.parentNode.insertBefore(s, x);
                }
            }
        </script>
    <?php } ?>

</body>

</html>