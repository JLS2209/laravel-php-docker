<?php
// Iniciar o reanudar sesión
@session_start();

// La clase Conectar debería existir desde la página donde se redirigió hasta aquí
// Si no existe, se debe expulsar porque el acceso es incorrecto
if (!class_exists("Conectar")) {
    error_log("Intento de acceso invalido!");
    header("location: ../");
    return;
}

// Recuperar el id_rol de la sesión (por defecto -> 1:invitado)
$rol = isset($_SESSION["id_rol"]) ? $_SESSION["id_rol"] : "1";

// Variable para retroceder a la raíz del proyecto desde el link actual
$raiz = "";
switch (preg_match_all("/\//", $_SERVER["PHP_SELF"])) {
    case 3:
        $raiz = ".";
        break;
    case 4:
        $raiz = "..";
        break;
    case 5:
        $raiz = "../..";
        break;
}

// Conectar a base de datos
$cn = (new Conectar()) ->getConectar();

// Ejecutar select
$sqlFoot = "
SELECT * FROM tb_menu_item m
INNER JOIN tb_rol_menu rm ON m.id_item = rm.id_item
WHERE id_rol = $rol AND item_tipo = 'footer-menu';
";
$rsFoot = mysqli_query($cn, $sqlFoot);
?>

<!-- Pie de página -->
<div class="container-fluid text-center pt-4" style="background-color: #211f1d; color: #F7BA00;">
    <div class="row">
        <div class="col-md">
            <!-- Título y logo -->
            <?php
            echo "<img class='navbar-brand' src='$raiz/multimedia/imagenes/logo.png' width='250px'>"
                ?>
            <!-- Información -->
            <div class="mt-4">
                <p>© 2024 Sazón & Fuego <br> Todos los derechos reservados </p>
            </div>
            <!-- Ícono y teléfono -->
            <div class="mt-4 mb-4">
                <span class="fa-stack fa-1x">
                    <i class="fa-brands fa-whatsapp fa-2x" style="color: #F7BA00;"></i>
                </span>
                <span class="ms-1">987 654 321</span>
            </div>
        </div>
        <div class="col-md">
            <!-- Título -->
            <h4 class="mb-4">MÁS INFORMACIÓN</h4>
            <!-- Lista con links -->
            <ul class="text-start ms-5">
            <?php
                while ($row = mysqli_fetch_array($rsFoot)) {
                    $label = $row["item_label"];
                    $link = $row["item_link"];
                    echo "
                        <li>
                            <a class='text-reset text-decoration-none' href='$raiz/$link'>$label</a>
                        </li>
                        ";
                }

                // Cerrar conexión
                mysqli_free_result($rsFoot);
                mysqli_close($cn);
                ?>
            </ul>
             
        </div>
        <div class="col-md">
            <!-- Título -->
            <h4 class="mb-4">ENCUÉNTRANOS EN</h4>
            <!-- Información -->
            <p>Panamericana Norte, <br> Av. Alfredo Mendiola 6377, Los Olivos 15306</p>
            <p>Lunes a sábado <br> 1:00 p.m. a 10:00 p.m.</p>
            <!-- Íconos -->
            <div class="mt-4 mb-4">
            <i class="fa-brands fa-facebook fa-2x me-3" style="color: #F7BA00;"></i>
            <i class="fa-brands fa-x-twitter fa-2x me-3" style="color: #F7BA00;"></i>
            <i class="fa-brands fa-instagram fa-2x me-3" style="color: #F7BA00;"></i>
            <i class="fa-brands fa-youtube fa-2x me-3" style="color: #F7BA00;"></i>
            </div>
        </div>
    </div>
</div>