<?php
// Cabecera JSON
header("Content-Type: application/json");

// Acceder a la clase Conectar
include "../../../cls_conectar/cls_Conectar.php";

// Acceder al modelo de clase Pedido
include "../../../modelo/cls_Pedido.php";

// Iniciar o reanudar sesión
session_start();

// Acceder a la clase Cliente y su controlador para reconocer si es fiel o no
include "../../../modelo/cls_Cliente.php";
include "../../../modelo/cls_Ubicacion.php";
include "../../../controlador/ctrl_Cliente.php";
$ctrlCliente = new ControladorCliente();
$cliente = $ctrlCliente->show($_SESSION['nro']);
$is_fiel = !($cliente->is_regular());

// Recuperar los parámetros de la consulta
$nombre = $_REQUEST['nombre'];

// Conectar a base de datos
$cn = (new Conectar())->getConectar();

// Ejecutar select
$sql = "SELECT id_plato, pl.nombre as nombre, descripcion, imagen, pl.id_categoria, cat.nombre as categoria,
        (precio_regular * (100.0 - descuento_general)/100) as precio_final_g,
        (precio_regular * (100.0 - descuento_fidelidad)/100) as precio_final_f
        FROM tb_plato pl
        INNER JOIN tb_categoria cat ON cat.id_categoria = pl.id_categoria
        WHERE pl.nombre LIKE '%$nombre%'
        ;";
$rs = mysqli_query($cn, $sql);

// Arreglo que almacena cada dato
$arr = [];
while ($row = mysqli_fetch_array($rs)) {
    $precio_final = $is_fiel ? $row[7] : $row[6];

    // Formar tarjeta
    $tarjeta = "
        <div class='col tarjeta-item'>
        <div class='card h-100 border border-dark'>
            <!-- Imagen -->
            <img src='../../multimedia/imagenes/platos/$row[3]' class='card-img-top' style='height:200px'>
            
            <div class='card-body d-flex flex-column align-items-end' style='color: black'>
                <div class='w-100'>
                    <!-- Nombre y descripcion -->
                    <h5 class='card-title'>$row[1]</h5>
                    <p class='card-text'>$row[2]</p>
                    <!-- Oculto para filtro por nombre y categoría -->
                    <p class='d-none target-nombre'>$row[1]</p>
                    <p class='d-none target-categoria'>$row[5]</p>
                </div>
                <div class='d-grid w-100 gap-2 mt-auto'>
                    <!-- Precio -->
                    <p class='card-text text-end my-4'>
                        <span class='text-muted border border-secondary rounded-3 p-2 fw-bold'>
                            S/. " . number_format($precio_final, 2) . "
                        </span>
                    </p>
                </div>
        ";

    // Evaluar si el plato está incluido en la lista de items del pedido
    $hasItem = false;
    foreach ($_SESSION["pedido"]->lista_platos as $item) {
        if ($item["id_plato"] == $row[0]) {
            $tarjeta .= "
                <div class='inputs-item w-100 row g-2'>
                    <!-- Inputs hidden ID, nombre y precio final de Plato -->
                    <div class='d-none'>
                        <input type='hidden' class='txt-id-plato' value='$row[0]' name = 'id-plato[]'>
                        <input type='hidden' class='txt-nombre-plato' value='$row[1]' name = 'nombre-plato[]'>
                        <input type='hidden' class='txt-precio-un-plato' value='$precio_final' name = 'precio-un-plato[]'>
                    </div>
                    <!-- Input Cantidad de Plato -->
                    <div class='col-10'>
                        <input type='number' class='txt-cantidad-plato w-75' min='1' max='10'
                            value='" . $item['cantidad_plato'] . "' name = 'cantidad-plato[]' required>
                        <div class='invalid-feedback'>
                            Debe ser una cantidad positiva menor a 10 unidades.
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
                <span class='text-muted fw-bold'>$row[5]</span>
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
                    <!-- Inputs hidden ID, nombre y precio final de Plato -->
                    <div class='d-none'>
                        <input type='hidden' class='txt-id-plato' value='$row[0]' '>
                        <input type='hidden' class='txt-nombre-plato' value='$row[1]' >
                        <input type='hidden' class='txt-precio-un-plato' value='$precio_final' >
                    </div>
                    <!-- Input Cantidad de Plato -->
                    <div class='col-10'>
                        <input type='number' class='txt-cantidad-plato w-75' min='1' max='10'
                            value='0' disabled>
                        <div class='invalid-feedback'>
                            Debe ser una cantidad positiva menor a 10 unidades.
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
                <span class='text-muted fw-bold'>$row[5]</span>
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