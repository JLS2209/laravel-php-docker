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
$sqlNav = "
SELECT * FROM tb_menu_item m
INNER JOIN tb_rol_menu rm ON m.id_item = rm.id_item
WHERE id_rol = $rol AND item_tipo = 'nav-menu';
";
$rsNav = mysqli_query($cn, $sqlNav);

$sqlBtn = "
SELECT * FROM tb_menu_item m
INNER JOIN tb_rol_menu rm ON m.id_item = rm.id_item
WHERE id_rol = $rol AND item_tipo = 'btn-menu';
";
$rsBtn = mysqli_query($cn, $sqlBtn);
?>

<!-- Barra de menús -->
<nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #211f1d;">
    <div class="container-fluid">
        <!-- Título y logo -->
        <?php
        echo "<img class='navbar-brand' src='$raiz/multimedia/imagenes/logo.png' width='250px'>"
        ?>
        <!-- Botón de toggle -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mi-navbar"
            aria-controls="mi-navbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <!-- Contenido -->
        <div class="collapse navbar-collapse" id="mi-navbar">

            <ul class="nav me-auto nav-pills nav-fill align-items-center">
                <?php
                $arr_link = explode("/", $_SERVER["PHP_SELF"]);
                $current_link = implode("/", array_splice($arr_link,3,-1))."/";
                while ($row = mysqli_fetch_array($rsNav)) {
                    $label = $row["item_label"];
                    $link = $row["item_link"];
                    $active = "";
                    if ($link == $current_link)
                        $active = "active";
                    if ($link == "./" && $current_link == "/")
                        $active = "active";

                    echo "
                        <li class='nav-item'>
                            <a class='nav-link $active' href='$raiz/$link'>$label</a>
                        </li>
                        ";
                }
                ?>
            </ul>

            <!-- Media audio -->
            <?php
            echo "<audio class='ms-4 me-4 mt-2' src='$raiz/multimedia/sonido/musica1.mp3' controls loop></audio>"
            ?>
            
            <!-- Botones de login -->
            <div class="mt-2 d-flex">
                <?php
                $color = "btn-danger";
                while ($row = mysqli_fetch_array($rsBtn)) {
                    // Recoger atributos
                    $label = $row["item_label"];
                    $link = $row["item_link"];
                    $color = ($color == "btn-secondary") ? "btn-danger" : "btn-secondary";

                    // Si el botón es SALIR
                    if ($label == "Salir") {
                        // Crear un form artificial que permita redirigir al Home
                        // con el $_POST['logout'] = 1
                        echo "
                        <form method='post' action='$raiz/$link'>
                            <input type='hidden' name='logout' value = 1>
                            <input type='submit' class='btn $color me-4' value= '$label'>
                        </form
                        ";
                    } else {
                        // Crear elemento simple
                        echo "<a class='btn $color me-4' href='$raiz/$link'>$label</a>";
                    }
                }

                // Cerrar conexión
                mysqli_free_result($rsNav);
                mysqli_free_result($rsBtn);
                mysqli_close($cn);
                ?>
            </div>
        </div>
    </div>
</nav>