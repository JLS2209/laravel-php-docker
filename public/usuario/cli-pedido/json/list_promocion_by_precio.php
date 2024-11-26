<?php
// Cabecera JSON
header("Content-Type: application/json");

// Acceder a la clase Conectar
include "../../../cls_conectar/cls_Conectar.php";

// Acceder al modelo de clase Pedido
include "../../../modelo/cls_Pedido.php";

// Iniciar o reanudar sesión
session_start();

// Recuperar los parámetros de la consulta
$precio = $_REQUEST['precio'];

// Conectar a base de datos
$cn = (new Conectar())->getConectar();

// Ejecutar select
$sql = "SELECT prom.id_promocion, prom.nombre, prom.descripcion, prom.imagen, cantidad_maxima,
        SUM(pl.precio_regular * det.cantidad_plato * (100 - descuento_promocion)/100) as precio_final
        FROM tb_promocion prom
        INNER JOIN tb_detalle_promocion det ON det.id_promocion = prom.id_promocion
        INNER JOIN tb_plato pl ON pl.id_plato = det.id_plato
        GROUP BY prom.id_promocion
        HAVING SUM(pl.precio_regular * det.cantidad_plato * (100 - descuento_promocion)/100) >= '$precio'
        ORDER BY SUM(pl.precio_regular * det.cantidad_plato * (100 - descuento_promocion)/100)
        ;";
$rs = mysqli_query($cn, $sql);

// Arreglo que almacena cada dato
$arr = [];
while ($row = mysqli_fetch_array($rs)) {
    // Formar tarjeta
    $tarjeta = "
        <div class='col tarjeta-item'>
        <div class='card h-100 border border-dark'>
            <!-- Imagen -->
            <img src='../../multimedia/imagenes/promociones/$row[3]' class='card-img-top' style='height:200px'>
            
            <div class='card-body d-flex flex-column align-items-end' style='color: black'>
                <div class='w-100'>
                    <!-- Nombre y descripcion -->
                    <h5 class='card-title'>$row[1]</h5>
                    <p class='card-text'>$row[2]</p>
                    <!-- Oculto para filtro por nombre y precio -->
                    <p class='d-none target-nombre'>$row[1]</p>
                    <input type='number' class='d-none target-precio' value='$row[5]'>
                </div>
                <div class='d-grid w-100 gap-2 mt-auto'>
                    <!-- Precio -->
                    <p class='card-text text-end my-4'>
                        <span class='text-muted border border-secondary rounded-3 p-2 fw-bold'>
                            S/. " . number_format($row[5], 2) . "
                        </span>
                    </p>
                </div>
        ";

    // Evaluar si la promocion está incluido en la lista de items del pedido
    $hasItem = false;
    foreach ($_SESSION["pedido"]->lista_promociones as $item) {
        if ($item["id_promocion"] == $row[0]) {
            $tarjeta .= "
                <div class='inputs-item w-100 row g-2'>
                    <!-- Inputs hidden ID, nombre y precio final de Promocion -->
                    <div class='d-none'>
                        <input type='hidden' class='txt-id-promocion' value='$row[0]' name = 'id-promocion[]'>
                        <input type='hidden' class='txt-nombre-promocion' value='$row[1]' name = 'nombre-promocion[]'>
                        <input type='hidden' class='txt-precio-un-promocion' value='$row[5]' name = 'precio-un-promocion[]'>
                    </div>
                    <!-- Input Cantidad de Promocion -->
                    <div class='col-10'>
                        <input type='number' class='txt-cantidad-promocion w-75' min='1' max='$row[4]'
                            value='" . $item['cantidad_promocion'] . "' name = 'cantidad-promocion[]' required>
                        <div class='invalid-feedback'>
                            Debe ser una cantidad positiva menor a $row[4] unidades.
                        </div>
                    </div>
                    <!-- Checkbox -->
                    <div class='col-2'>
                        <input type='checkbox' class='chk-item form-check-input' checked>
                    </div>
                </div>
            </div>

            <!-- Categoría -->
            <div class='card-footer'>
                <span class='text-muted fw-bold'>Promoción</span>
            </div>
        </div>
        </div>
            ";
            $hasItem = true;
            break;
        }
    }

    // Si no estaba en la lista, se deja con cantidad 0 y desactivado
    if (!$hasItem) {
        $tarjeta .= "
                <div class='inputs-item w-100 row g-2'>
                    <!-- Inputs hidden ID, nombre y precio final de Promocion -->
                    <div class='d-none'>
                        <input type='hidden' class='txt-id-promocion' value='$row[0]' '>
                        <input type='hidden' class='txt-nombre-promocion' value='$row[1]' >
                        <input type='hidden' class='txt-precio-un-promocion' value='$row[5]' >
                    </div>
                    <!-- Input Cantidad de Promocion -->
                    <div class='col-10'>
                        <input type='number' class='txt-cantidad-promocion w-75' min='1' max='$row[4]'
                            value='0' disabled>
                        <div class='invalid-feedback'>
                            Debe ser una cantidad positiva menor a $row[4] unidades.
                        </div>
                    </div>
                    <!-- Checkbox -->
                    <div class='col-2'>
                        <input type='checkbox' class='chk-item form-check-input'>
                    </div>
                </div>
            </div>

            <!-- Categoría -->
            <div class='card-footer'>
                <span class='text-muted fw-bold'>Promoción</span>
            </div>
        </div>
        </div>
        ";
    }

    // Agregar al arreglo
    $arr[] = array(
        "tarjeta" => $tarjeta
    );
}

// Cerrar conexión
mysqli_free_result($rs);
mysqli_close($cn);

// Convierte a JSON
print_r(json_encode($arr));
?>