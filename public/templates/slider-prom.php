<?php
// Variable para retroceder a la raíz del proyecto desde el link actual
$raiz = "";

// La clase Conectar debería existir desde la página donde se redirigió hasta aquí
// Si no existe, se debe expulsar porque el acceso es incorrecto
if (!class_exists("Conectar")) {
    error_log("Intento de acceso invalido!");
    header("location: ../");
    return;
}

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

// Acceder a la clase Promocion y su controlador
include "./modelo/cls_Promocion.php";
include "./controlador/ctrl_Promocion.php";

// Invocar método del controlador
$ctrl = new ControladorPromocion();
$arr = $ctrl->list();

// Número de promociones
$numero = count($arr);
?>

<!-- Slider de promociones -->
<div id="slider-prom" class="carousel slide" data-bs-ride="false">
    <div class="carousel-indicators">
        <?php
        for ($i = 0; $i < $numero; $i++) {
            $active = ($i == 0) ? "class='active'" : "";
            echo "<button type='button' data-bs-target='#slider-prom' data-bs-slide-to='$i' $active></button>";
        }
        ?>
    </div>
    <div class="carousel-inner">
        <?php
        $dir_img = "$raiz/multimedia/imagenes";
        for ($i = 0; $i < $numero; $i++) {
            $is_active = ($i == 0);
            $arr[$i]->slider_item($dir_img, $is_active);
        }
        ?>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#slider-prom" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previo</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#slider-prom" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Siguiente</span>
    </button>
</div>