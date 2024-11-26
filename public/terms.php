<?php
// Iniciar o reanudar sesión
session_start();

// El acceso no requiere credenciales

// Acceder a la clase Conectar
include "./cls_conectar/cls_Conectar.php";
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
    <?php include "./templates/nav.php"; ?>

    <!-- Cuerpo de la página -->
    <div class="container-fluid" style="background-image: url('multimedia/imagenes/fondo.jpg'); background-size: 100%;">
        <div class="container p-4" style="background-color: rgba(0, 0, 0, 0.8); color: wheat;">

            <!-- Prólogo -->
            <h1 class="text-center mb-4">Términos y Condiciones de Uso</h1>
            <p class="lead px-4">
                Bienvenido a la página web de Sazón & Fuego. Al acceder y utilizar este sitio, aceptas cumplir y estar
                sujeto a los siguientes términos y condiciones. Si no estás de acuerdo con alguna parte de estos
                términos, te recomendamos que no utilices nuestro sitio web.
            </p>

            <!-- Contenido -->
            <div class="lead fs-6 mx-4">
                <hr>

                <p class="fw-bold fs-5">1. Uso del Sitio Web</p>
                <p>
                    El contenido de este sitio web es solo para información general y uso personal. Está sujeto a
                    cambios sin previo aviso. No garantizamos la precisión, puntualidad, rendimiento, integridad o
                    idoneidad de la información y materiales encontrados u ofrecidos en este sitio web para un propósito
                    particular.
                </p>

                <p class="fw-bold fs-5">2. Registro de Usuarios</p>
                <p>
                    Al registrarte en nuestro sitio, declaras que la información proporcionada es válida y actual. El
                    registro te permite acceder a promociones exclusivas y gestionar pedidos de manera más eficiente. Es
                    responsabilidad del usuario mantener la confidencialidad de su contraseña y cuenta.
                </p>

                <p class="fw-bold fs-5">3. Pedidos en Línea</p>
                <p>
                    Todos los pedidos están sujetos a disponibilidad y confirmación. Nos reservamos el derecho de
                    rechazar cualquier pedido por motivos razonables, como errores en la información del producto o
                    falta de inventario. Se requiere un método de pago válido al momento de la compra.
                </p>

                <p class="fw-bold fs-5">4. Política de Cancelación y Devoluciones</p>
                <p>
                    Los pedidos pueden ser cancelados hasta 5 minutos después de haber sido realizados. Para cambios o
                    devoluciones, comunícate con nuestro equipo de atención al cliente lo antes posible. No se
                    realizarán devoluciones por pedidos ya preparados o enviados.
                </p>

                <p class="fw-bold fs-5">5. Promociones y Descuentos</p>
                <p>
                    Los descuentos y promociones solo están disponibles para usuarios registrados y pueden tener límites
                    de tiempo y condiciones específicas. Nos reservamos el derecho de modificar o cancelar promociones
                    en cualquier momento sin previo aviso.
                </p>

                <p class="fw-bold fs-5">6. Comentarios y Sugerencias</p>
                <p>
                    Al enviar comentarios o sugerencias a través de nuestra página web, otorgas a Sazón & Fuego el
                    derecho de usar dichos comentarios para mejorar nuestros servicios sin ninguna compensación.
                </p>

                <p class="fw-bold fs-5">7. Propiedad Intelectual</p>
                <p>
                    El contenido de este sitio web, incluidos textos, imágenes y logotipos, es propiedad de Sazón &
                    Fuego o de sus licenciantes. Queda prohibida la reproducción sin autorización previa.
                </p>

                <p class="fw-bold fs-5">8. Enlaces Externos</p>
                <p>
                    Este sitio web puede contener enlaces a otros sitios de interés. No tenemos control sobre el
                    contenido de esos sitios y no somos responsables de la protección y privacidad de la información que
                    proporciones mientras los visitas.
                </p>

                <p class="fw-bold fs-5">9. Modificaciones de los Términos</p>
                <p>
                    Sazón & Fuego se reserva el derecho de modificar estos términos en cualquier momento. Te
                    recomendamos revisar esta página periódicamente para estar al tanto de los cambios.
                </p>

                <p class="fw-bold fs-5">10. Ley Aplicable</p>
                <p>
                    Estos términos y condiciones se rigen por las leyes locales aplicables. Cualquier disputa que surja
                    en relación con el uso de la página web estará sujeta a la jurisdicción exclusiva de los tribunales
                    competentes.
                </p>

                <hr>
            </div>

            <p class="lead px-4">
                Gracias por utilizar nuestra página web. <br>
                Si tienes preguntas sobre estos términos, contáctanos a través de nuestra página de contacto.
            </p>

        </div>
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
    <!--SweetAlert JS-->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.1/dist/sweetalert2.all.min.js"></script>
</body>

</html>