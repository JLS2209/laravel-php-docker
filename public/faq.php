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

            <!-- FAQ -->
            <h1 class="text-center mb-4">Preguntas frecuentes (FAQ)</h1>
            <p class="lead px-4">
                Se ofrece aquí respuestas a una selección de las preguntas más frecuentes planteadas por los clientes
                del restaurante Sazón & Fuego.
            </p>

            <!-- Contenido -->
            <div class="w-75 mx-4">
                <hr>
                <div>
                    <h5>¿Es necesario registrarse para hacer un pedido?</h5>
                    <p class="lead fs-6">
                        Sí, debes registrarte antes de hacer un pedido en línea. Al registrarte podrás acceder a
                        descuentos exclusivos y guardar tus datos para futuros pedidos.
                    </p>
                </div>
                <hr>
                <div>
                    <h5>¿Cómo puedo hacer un pedido en línea?</h5>
                    <p class="lead fs-6">
                        Puedes realizar un pedido dirigiéndote a la sección "Pedidos". Allí podrás seleccionar la
                        modalidad de compra, seleccionar los platos de nuestro menú y promociones añadiéndolos al
                        carrito de compras y seguir las instrucciones para completar la compra.
                    </p>
                </div>
                <hr>
                <div>
                    <h5>¿Qué métodos de pago aceptan?</h5>
                    <p class="lead fs-6">
                        Aceptamos tarjetas de crédito y débito, y pago contra entrega (efectivo).
                    </p>
                </div>
                <hr>
                <div>
                    <h5>¿Tienen opciones para personas con restricciones dietéticas?</h5>
                    <p class="lead fs-6">
                        Sí, ofrecemos opciones vegetarianas y sin gluten. Puedes ver las especificaciones de cada plato
                        en el menú y contactarnos si tienes alguna duda.
                    </p>
                </div>
                <hr>
                <div>
                    <h5>¿Cuál es el tiempo estimado de entrega?</h5>
                    <p class="lead fs-6">
                        El tiempo de entrega varía según la ubicación, pero generalmente es de entre 30 y 60 minutos.
                        Puedes revisar el estado de tu pedido dirigiéndote a la sección "Mi Perfil" y luego a "Mis
                        Pedidos".
                    </p>
                </div>
                <hr>
                <div>
                    <h5>¿Tienen promociones especiales para clientes registrados?</h5>
                    <p class="lead fs-6">
                        Sí, los clientes registrados tienen acceso a descuentos exclusivos, promociones y un programa
                        gratuito de fidelización con beneficios.
                    </p>
                </div>
                <hr>
                <div>
                    <h5>¿Cuál es su política de devolución o cancelación de pedidos?</h5>
                    <p class="lead fs-6">
                        Puedes cancelar tu pedido dentro de los primeros 5 minutos después de realizarlo. Si necesitas
                        más información sobre reembolsos, consulta nuestra política de devoluciones en la sección de
                        "Términos y Condiciones".
                    </p>
                </div>
                <hr>
                <div>
                    <h5>¿Cómo puedo enviar sugerencias o comentarios sobre mi experiencia?</h5>
                    <p class="lead fs-6">
                        Puedes enviar tus comentarios a través del formulario en la sección "Contacto". Valoramos mucho
                        tu opinión y la usamos para mejorar nuestros servicios.
                    </p>
                </div>
                <hr>
                <div>
                    <h5>¿Cuáles son sus horarios de atención?</h5>
                    <p class="lead fs-6">
                        Nuestro restaurante está abierto de lunes a sábado, de 1:00 p.m. a 10:00 p.m. Los horarios de
                        pedidos en línea pueden variar.
                    </p>
                </div>
                <hr>
                <div>
                    <h5>¿Ofrecen entregas a toda la ciudad?</h5>
                    <p class="lead fs-6">
                        Ofrecemos entregas en los distintos distritos de Lima Metropolitana y el Callao. Puedes
                        verificar si tu dirección está dentro de nuestro rango de entrega al realizar tu pedido.
                    </p>
                </div>
                <hr>
                <div>
                    <h5>¿Qué beneficios tiene unirse al programa de fidelización?</h5>
                    <p class="lead fs-6">
                        Al unirte, puedes acumular puntos por cada compra y obtener descuentos especiales exclusivos.
                        Puedes unirte automáticamente al registrarte.
                    </p>
                </div>
                <hr>
                <div>
                    <h5>¿Cómo puedo actualizar mi información de cuenta?</h5>
                    <p class="lead fs-6">
                        Inicia sesión en tu cuenta y dirígete a la sección "Mi Perfil" para actualizar tu información
                        personal, dirección o detalles de contacto.
                    </p>
                </div>
                <hr>
                <div>
                    <h5>¿Qué debo hacer si tengo un problema con mi pedido?</h5>
                    <p class="lead fs-6">
                        Si tienes algún inconveniente, por favor contáctanos inmediatamente a través de la sección
                        "Contacto" o por correo electrónico, y resolveremos el problema lo más rápido posible.
                    </p>
                </div>
                <hr>
            </div>


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