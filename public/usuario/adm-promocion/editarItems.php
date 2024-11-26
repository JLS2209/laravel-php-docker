<?php
// Iniciar o reanudar sesión
session_start();

// Restringir acceso si el id_rol no corresponde a empleado administrador (4)
if (!(isset($_SESSION["id_rol"]) && $_SESSION["id_rol"] == 4)) {
    error_log("Intento de acceso sin credenciales adecuadas de administrador!");
    header("location: ../../");
}

// Acceder a la clase Conectar
include "../../cls_conectar/cls_Conectar.php";

// Acceder a la clase Promocion y su controlador
include "../../modelo/cls_Promocion.php";
include "../../controlador/ctrl_Promocion.php";
$ctrl = new ControladorPromocion();

// Recuperar Promoción a partir de la sesión
$promocion = $ctrl->show($_SESSION["id_promocion"]);

// Recoger los items de la promocion
$promocion->items = $ctrl->list_items($promocion->id);
$nombre = $promocion->nombre;
$descripcion = $promocion->descripcion;

// Construir listado de platos para la tabla
$cn = (new Conectar())->getConectar();
$sql = "SELECT id_plato, nombre, precio_regular FROM tb_plato;";
$rs = mysqli_query($cn, $sql);
$tabla = "";
while ($row = mysqli_fetch_array($rs)) {
    // Formar fila
    $fila = "
        <tr>
            <!-- Nombre de plato -->
            <td class='td-nombre'>$row[1]</td>
            <!-- Precio regular de plato -->
            <td>S/ $row[2]</td>
            ";

    // Evaluar si el plato está incluido en la lista de items de la promoción
    $hasPlato = false;
    foreach ($promocion->items as $item) {
        if ($item["id_plato"] == $row[0]) {
            $fila .= "
            <!-- Input hidden ID de Plato -->
            <td class='d-none'>
                <input type='hidden' class='txt-id-plato' value='$row[0]' name = 'id-plato[]'>
            </td>
            <!-- Input Cantidad de Plato -->
            <td>
                <input type='number' class='txt-cantidad-plato' min='1' max='10'
                    value='" . $item['cantidad_plato'] . "' name = 'cantidad-plato[]' required>
                <div class='invalid-feedback'>
                    Debe ser una cantidad positiva menor a 10 unidades.
                </div>
            </td>
            <!-- Checkbox -->
            <td>
                <input type='checkbox' class='chk-plato form-check-input' checked>
            </td>
        </tr>    
            ";
            $hasPlato = true;
            break;
        }
    }

    // Si no estaba en la lista, se deja con cantidad 0 y desactivado
    if (!$hasPlato) {
        $fila .= "
            <!-- Input hidden ID de Plato -->
            <td class='d-none'>
                <input type='hidden' class='txt-id-plato' value='$row[0]'>
            </td>
            <!-- Input Cantidad de Plato -->
            <td>
                <input type='number' class='txt-cantidad-plato' min='1' max='10'
                    value='0' disabled>
                <div class='invalid-feedback'>
                    Debe ser una cantidad positiva menor a 10 unidades.
                </div>
            </td>
            <!-- Checkbox -->
            <td>
                <input type='checkbox' class='chk-plato form-check-input'>
            </td>
        </tr>    
        ";
    }

    // Agregar a la tabla
    $tabla .= $fila;
}
// Cerrar conexión
mysqli_free_result($rs);
mysqli_close($cn);
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
        /* Color de fondo de la página */
        body {
            background: linear-gradient(to right, #F7BA00, #F0A330);
        }

        /*Estilos de los nav-pills*/
        .nav-pills .nav-link.active {
            color: black;
            background-color: #F7BA00;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <!-- Cuerpo de la página -->
    <div class="container">
        <div class="card flex-row my-5 border-0 shadow rounded-3 overflow-hidden">
            <div class="card-body p-4 p-sm-5">
                <!-- Datos de la promoción -->
                <h2 class="card-title fw-light"><?php echo $nombre; ?></h2>
                <p><?php echo $descripcion; ?></p>

                <hr class="my-4">

                <!-- Filtrar componentes por nombre -->
                <div class="col-12">
                    <label for="txt-filtro-nombre" class="form-label">Busque platos por nombre:</label>
                    <input type="text" class="form-control" id="txt-filtro-nombre" placeholder="Nombre del plato">
                </div>

                <hr class="my-4">

                <!-- Formulario / Tabla de Items -->
                <form class="table-responsive" method="post" action="editarItemscontroller.php" id="frm-tabla-items"
                    novalidate>
                    <!-- Input hidden ID de Promocion -->
                    <input type='hidden' name='id-promocion' value='<?php echo $promocion->id; ?>'>

                    <!-- Tabla de Listado -->
                    <table class="table table-light table-striped mt-3">
                        <thead class="table-dark">
                            <tr>
                                <th scope='col'>Plato</th>
                                <th scope='col'>Precio regular</th>
                                <th scope='col' class='d-none'></th> <!-- Input hidden ID de Plato -->
                                <th scope='col'>Cantidad</th> <!-- Input Cantidad de Plato -->
                                <th scope='col'></th> <!-- Checkbox -->
                            </tr>
                        </thead>
                        <tbody>
                            <?php echo $tabla ?>
                        </tbody>
                    </table>

                    <!-- Botón de submit -->
                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-success btn-lg px-5 py-2">GRABAR</button>
                    </div>
                </form>

                <hr class="my-4">

                <!-- Botón para volver al mantenimiento de Promociones -->
                <div class="col-12 text-center">
                    <a href="./" class="btn btn-danger btn-lg px-5 py-2">VOLVER</a>
                </div>

            </div>
        </div>
    </div>

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

    <!-- Mostrar mensaje -->
    <?php
    function msg($titulo, $texto, $icono)
    {
        echo "
        <script>
            Swal.fire({
                title: '$titulo',
                text: '$texto',
                icon: '$icono'
            }); 
        </script>";
    }
    if (isset($_SESSION["mensaje"])) {
        switch ($_SESSION["mensaje"]) {
            case 1:
                msg("Operación exitosa", "Se han eliminado todos los items de la promoción", "success");
                break;
            case 2:
                msg("Operación exitosa", "Se han guardado los items en la promoción", "success");
                break;
            case -1:
                msg("Operación fallida", "No se pudo guardar los items", "error");
                break;
            case -10:
                msg("Operación fallida", "Hubo un error", "error");
                break;
        }
    }

    // Eliminar mensaje de la sesión
    unset($_SESSION["mensaje"]);
    ?>

    <!-- Asignar evento input a la caja de filtro -->
    <script>
        $(document).on("keyup", "#txt-filtro-nombre", function () {
            // Recuperar input
            const filtro = $("#txt-filtro-nombre").val().toLowerCase();

            // Filtrar componentes
            $("tbody tr").filter(function () {
                $(this).toggle($(this).find(".td-nombre").text().toLowerCase().indexOf(filtro) > -1)
            });
        });
    </script>

    <!-- Asignar evento change a los checkbox de plato -->
    <script>
        $(document).on("change", ".chk-plato", function () {
            // Recuperar estado del check
            const isChecked = this.checked;

            // Activar o desactivar inputs según selección
            $(this).parents("tr").find(".txt-cantidad-plato").attr('disabled', !isChecked);

            // Dar o quitar atributo name y required para los inputs
            if (isChecked) {
                $(this).parents("tr").find(".txt-id-plato").attr('name', 'id-plato[]');
                $(this).parents("tr").find(".txt-cantidad-plato").attr('name', 'cantidad-plato[]');
                $(this).parents("tr").find(".txt-cantidad-plato").attr('required', true);
            } else {
                $(this).parents("tr").find(".txt-id-plato").removeAttr('name');
                $(this).parents("tr").find(".txt-cantidad-plato").removeAttr('name');
                $(this).parents("tr").find(".txt-cantidad-plato").removeAttr('required');
                $(this).parents("tr").find(".txt-cantidad-plato").val('0');
            }
        });
    </script>

    <!-- Validaciones -->
    <script>
        // Desactivar el submit del formulario si presenta inputs inválidos
        let form = document.getElementById("frm-tabla-items");
        form.addEventListener("submit", event => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();

                Swal.fire({
                    icon: "warning",
                    title: "Advertencia",
                    text: "Debe corregir las cantidades de los items",
                });
            }
            form.classList.add('was-validated');
        }, false);
    </script>

</body>

</html>