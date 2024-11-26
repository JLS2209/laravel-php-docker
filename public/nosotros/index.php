<?php
// Iniciar o reanudar sesión
session_start();

// El acceso no requiere credenciales, aunque debería mostrarse solo a clientes

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

            <!-- Sección superior -->
            <h1 class="text-center mb-4">Visítanos</h1>
            <div class="row g-2 mx-4 text-center">
                <!-- Youtube -->
                <div class="col-md-6">
                    <iframe class="media rounded-4" src="https://www.youtube.com/embed/n8YwWZy3bcM?si=oHNqIwiEuc573uPn"
                        title="YouTube video player" style="border:0; width:80%; height: 400px"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                        referrerpolicy="strict-origin-when-cross-origin" allowfullscreen>
                    </iframe>
                </div>
                <!-- Google Maps -->
                <div class="col-md-6">
                    <iframe class="media rounded-4"
                        src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d7806.623916028263!2d-77.070192!3d-11.95289!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x9105d1d877f532d7%3A0x8db19fe8e1f40feb!2sUniversidad%20Tecnol%C3%B3gica%20del%20Per%C3%BA!5e0!3m2!1ses-419!2spe!4v1726113492352!5m2!1ses-419!2spe"
                        style="border:0; width:80%; height: 400px" allowfullscreen="" loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>

            <hr style="border: wheat solid 2px; opacity: 1.0; margin: 30px auto; width: 75%">
            <br>

            <!-- Sección inferior -->
            <div class="mx-4">
                <!-- Misión y visión -->
                <div class="row g-2 m-4 text-center justify-content-center">
                    <!-- Misión -->
                    <div class="col-md px-4">
                        <h2 class="mb-4">Nuestra misión</h2>
                        <p>
                            <em>
                                "En Sazón & Fuego, nuestra misión es ofrecer una experiencia culinaria única que
                                encienda los sentidos de nuestros comensales. Combinamos ingredientes frescos de calidad
                                con el arte de la parrilla y el sazón auténtico, creando platos llenos de sabor y
                                tradición. Nos comprometemos a brindar un servicio cálido y acogedor, en un ambiente que
                                celebre la pasión por la buena comida y la cultura gastronómica local."
                            </em>
                        </p>
                    </div>
                    <!-- Línea vertical -->
                    <div class="d-flex" style="width: 2px;">
                        <div class="vr" style="border: wheat solid 2px; opacity: 1.0;"></div>
                    </div>
                    <!-- Visión -->
                    <div class="col-md px-4">
                        <h2 class="mb-4">Nuestra visión</h2>
                        <p>
                            <em>
                                "Nuestra visión es consolidarnos como el restaurante de referencia para quienes buscan
                                una experiencia culinaria auténtica, donde el fuego y el sazón se combinan para crear
                                momentos inolvidables. Aspiramos a expandir nuestro concepto, promoviendo la excelencia
                                gastronómica y el compromiso con la calidad, mientras fortalecemos nuestra presencia en
                                la comunidad."
                            </em>
                        </p>
                    </div>
                </div>

                <br>
                <hr style="border: wheat solid 2px; opacity: 1.0; margin: 30px auto; width: 80%">

                <!-- Historia -->
                <h1 class="text-center mb-4">Conoce nuestra Historia</h1><br>
                <div class="mx-4">
                    <!-- Intro -->
                    <p>
                        La historia de Sazón & Fuego comenzó como un sueño entre amigos apasionados por la cocina y la
                        parrilla. Inspirados por los sabores tradicionales y el poder del fuego para transformar los
                        ingredientes más simples en platos llenos de vida, decidieron abrir su primer local en 2010 en
                        un pequeño rincón de la ciudad. Desde el principio, el compromiso fue claro: ofrecer a los
                        comensales una experiencia gastronómica única, donde la sazón y la parrilla fueran
                        protagonistas.
                    </p>
                    <br>
                    <!-- Item 1 -->
                    <div class="row g-2 my-1">
                        <div class="col-md-3 d-flex justify-content-center align-items-center">
                            <img src="../multimedia/imagenes/historia1.jpg" class="rounded-4 border border-dark w-75">
                        </div>
                        <div class="col-md-9">
                            <h4>1. La apertura del primer local - 2010</h4>
                            <p class="ms-4">
                                El primer restaurante de Sazón & Fuego abrió sus puertas en 2010, con una carta
                                pequeña pero cuidadosamente seleccionada. Los platos a la parrilla y el uso de
                                ingredientes frescos rápidamente ganaron popularidad entre los clientes locales. La
                                combinación de sabores auténticos y el ambiente acogedor hicieron que el restaurante
                                se convirtiera en un referente para los amantes de la buena comida.
                            </p>
                        </div>
                    </div>
                    <br>
                    <!-- Item 2 -->
                    <div class="row g-2 my-1">
                        <div class="col-md-3 order-md-last d-flex justify-content-center align-items-center">
                            <img src="../multimedia/imagenes/historia2.jpg" class="rounded-4 border border-dark w-75">
                        </div>
                        <div class="col-md-9 order-md-first">
                            <h4>2. Expansión y renovación del menú - 2015</h4>
                            <p class="ms-4">
                                Cinco años después de su apertura, en 2015, Sazón & Fuego expandió sus instalaciones
                                para acomodar a más comensales. Además, el menú se renovó, incorporando nuevas
                                opciones como mariscos a la parrilla y postres elaborados en casa. Esta evolución
                                permitió que el restaurante siguiera creciendo y atrajera a un público más diverso,
                                consolidando su lugar como un espacio para disfrutar de sabores intensos y únicos.
                            </p>
                        </div>
                    </div>
                    <br>
                    <!-- Item 3 -->
                    <div class="row g-2 my-1">
                        <div class="col-md-3 d-flex justify-content-center align-items-center">
                            <img src="../multimedia/imagenes/historia3.jpg" class="rounded-4 border border-dark w-75">
                        </div>
                        <div class="col-md-9">
                            <h4>3. Reconocimiento gastronómico - 2020</h4>
                            <p class="ms-4">
                                En 2020, Sazón & Fuego recibió un prestigioso reconocimiento en el ámbito
                                gastronómico local, siendo galardonado como uno de los mejores restaurantes de
                                comida a la parrilla. Este hito marcó el comienzo de su proyección como un referente
                                en la cocina con fuego, y solidificó su reputación como un lugar donde la calidad,
                                el servicio y la pasión por la parrilla son inigualables.
                            </p>
                        </div>
                    </div>
                    <br>
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
</body>

</html>