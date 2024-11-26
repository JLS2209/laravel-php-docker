<?php
// Iniciar o reanudar sesión
session_start();

// El acceso no requiere credenciales

// Acceder a la clase Conectar
include "./cls_conectar/cls_Conectar.php";

// Recuperar el id_rol de la sesión (por defecto -> 1:invitado)
$rol = isset($_SESSION["id_rol"]) ? $_SESSION["id_rol"] : "1";

// Conectar a base de datos
$cn = (new Conectar())->getConectar();
// Ejecutar select
$sql1 = "
SELECT * FROM tb_menu_item m
INNER JOIN tb_rol_menu rm ON m.id_item = rm.id_item
WHERE id_rol = $rol AND NOT item_tipo = 'footer-menu';
";
$rs1 = mysqli_query($cn, $sql1);

$sql2 = "
SELECT * FROM tb_menu_item m
INNER JOIN tb_rol_menu rm ON m.id_item = rm.id_item
WHERE id_rol = $rol AND item_tipo = 'footer-menu';
";
$rs2 = mysqli_query($cn, $sql2);

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

            <h1 class="text-center mb-4">Mapa de Sitio</h1>
            <a href="http://" target="_blank" rel="noopener noreferrer"></a>

            <hr>

            <h4 class=" mb-4">Secciones principales</h4>
            <ul class="lead fs-5">
                <?php
                while ($row = mysqli_fetch_array($rs1)) {
                    $id = $row["id_item"];
                    $label = $row["item_label"];
                    $link = $row["item_link"];
                    if ($id == 15) {    // Perfil de cliente
                        echo "
                        <li>
                            <a class='text-reset' href='./$link' target='_blank'>$label</a>
                            <ul>
                                <li>Mis Pedidos</li>
                                <li>Mis Mensajes</li>
                            </ul>
                        </li>
                        ";
                    }
                    else if ($id != 17) {    // Excepto Salir
                        echo "
                        <li>
                            <a class='text-reset' href='./$link' target='_blank'>$label</a>
                        </li>
                        ";
                    }
                }
                ?>
            </ul>

            <hr>

            <h4 class=" mb-4">Recursos adicionales</h4>
            <ul class="lead fs-5">
                <?php
                while ($row = mysqli_fetch_array($rs2)) {
                    $label = $row["item_label"];
                    $link = $row["item_link"];
                    echo "
                    <li>
                        <a class='text-reset' href='./$link' target='_blank'>$label</a>
                    </li>
                    ";
                }
                ?>
            </ul>

            <hr>

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