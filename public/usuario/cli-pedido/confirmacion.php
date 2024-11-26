<?php
// Acceder al modelo de clase Pedido
include "../../modelo/cls_Pedido.php";

// Acceder a la clase Cliente y su controlador
include "../../modelo/cls_Cliente.php";
include "../../modelo/cls_Ubicacion.php";
include "../../controlador/ctrl_Cliente.php";

// Iniciar o reanudar sesión
session_start();

// Restringir acceso si el id_rol no corresponde a cliente (2)
if (!(isset($_SESSION["id_rol"]) && $_SESSION["id_rol"] == 2)) {
    error_log("Intento de acceso sin credenciales adecuadas de cliente!");
    header("location: ../../");
}

// Si no existe $_SESSION["pedido"], expulsar de la página
if (!isset($_SESSION["pedido"])) {
    error_log("Intento de acceder a pedido/ubicacion sin pasar por las secciones previas de pedido.");
    header("location: ../../");
    return;
}

// Acceder a la clase Conectar
include "../../cls_conectar/cls_Conectar.php";

// Invocar búsqueda de cliente para recuperar sus datos
$nro = isset($_SESSION["nro"]) ? $_SESSION["nro"] : -1;
$ctrlCliente = new ControladorCliente();
$cliente = $ctrlCliente->show($nro);

// Cargar datos de pedido
$tarjeta = (isset($_SESSION["tarjeta"])) ? $_SESSION["tarjeta"]["numero-tarjeta"] : "";
$opcion = ($_SESSION["pedido"]->opcion_entrega == 1) ? "Delivery" : "Pickup en tienda";
$pago = ($_SESSION["pedido"]->metodo_pago == 1) ? "Tarjeta: $tarjeta" : "Efectivo";

// Cargar costos
$delivery = $_SESSION["pedido"]->costo_delivery;
$_SESSION["pedido"]->set_total();
$total = $_SESSION["pedido"]->total_pagar;

// Cargar datos de ubicación a partir del Pedido (si existen)
if ($_SESSION["pedido"]->ubicacion != null) {
    $distrito = $_SESSION["pedido"]->ubicacion->distrito->nombre_distrito;
    $direccion = $_SESSION["pedido"]->ubicacion->direccion;
} else {
    $distrito = "";
    $direccion = "";
}

// Formar lista de items
$tabla = "";
foreach ($_SESSION["pedido"]->lista_platos as $item) {
    $nombre_item = $item["nombre_plato"];
    $cantidad_item = $item["cantidad_plato"];
    $precio_item = $item["precio_un_plato"] * $item["cantidad_plato"];
    $tabla .= "
        <tr>
            <td class='ps-3'>$nombre_item</td>
            <td>$cantidad_item</td>
            <td>S/. " . number_format($precio_item, 2) . "</td>
        </tr>
    ";
}
foreach ($_SESSION["pedido"]->lista_promociones as $item) {
    $nombre_item = $item["nombre_promocion"];
    $cantidad_item = $item["cantidad_promocion"];
    $precio_item = $item["precio_un_promocion"] * $item["cantidad_promocion"];
    $tabla .= "
        <tr>
            <td class='ps-3'>$nombre_item</td>
            <td>$cantidad_item</td>
            <td>S/. " . number_format($precio_item, 2) . "</td>
        </tr>
    ";
}

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
    <?php include "../../templates/nav.php"; ?>
    
    <!-- Cuerpo de la página -->
    <div class="container">
        <div class="card flex-row my-5 border-0 shadow rounded-3 overflow-hidden">
            <div class="card-body p-4 p-sm-5">
                <!-- Título -->
                <h2 class="card-title text-center mb-5 fw-light">Confirma tu pedido</h2>

                <!-- Datos del pedido -->
                <div class="row g-3">
                    <!-- Nombres -->
                    <div class="form-floating col-md-8 mb-1">
                        <input type="text" class="form-control" id="txtNombre"
                            value='<?php echo "$cliente->nombre $cliente->apellido" ?>' readonly>
                        <label class="ps-4" for="txtNombre">Cliente</label>
                    </div>
                    <!-- Teléfono -->
                    <div class="form-floating col-md-4 mb-1">
                        <input type="tel" class="form-control" id="txtTelefono"
                            value='<?php echo "$cliente->telefono" ?>' readonly>
                        <label class="ps-4" for="txtTelefono">Teléfono celular</label>
                    </div>
                    <!-- Método de pago -->
                    <div class="form-floating col-md-8 mb-1">
                        <input type="text" class="form-control" id="txtPago" value='<?php echo "$pago" ?>' readonly>
                        <label class="ps-4" for="txtPago">Método de pago</label>
                    </div>
                    <!-- Opción de entrega -->
                    <div class="form-floating col-md-4 mb-1">
                        <input type="tel" class="form-control" id="txtEntrega" value='<?php echo "$opcion" ?>' readonly>
                        <label class="ps-4" for="txtEntrega">Opción de entrega</label>
                    </div>
                    <!-- Dirección -->
                    <div class="form-floating col-md-8 mb-1">
                        <input type="text" class="form-control" id="txtDireccion" value='<?php echo "$direccion" ?>'
                            readonly>
                        <label class="ps-4" for="txtDireccion">Dirección de entrega</label>
                    </div>
                    <!-- Distrito -->
                    <div class="form-floating col-md-4 mb-1">
                        <input type="tel" class="form-control" id="txtDistrito" value='<?php echo "$distrito" ?>'
                            readonly>
                        <label class="ps-4" for="txtDistrito">Distrito</label>
                    </div>
                </div>

                <hr>

                <!-- Tabla de items -->
                <div class='table-responsive'>
                    <table class='table mt-3'>
                        <thead class='table-dark'>
                            <tr>
                                <th scope='col' class='ps-3'>Item</th>
                                <th scope='col'>Cantidad</th>
                                <th scope='col'>Precio subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php echo $tabla; ?>
                        </tbody>
                    </table>
                </div>

                <hr>

                <!-- Costo de delivery y Total a pagar -->
                <div class="row g-3">
                    <!-- Costo Delivery -->
                    <div class="form-floating col-md-4 mb-1">
                        <input type="text" class="form-control" id="txtDelivery"
                            value='S/ <?php echo number_format($delivery, 2); ?>' readonly>
                        <label class="ps-4" for="txtDelivery">Costo de Delivery</label>
                    </div>
                    <!-- Total a pagar -->
                    <div class="form-floating col-md-8 mb-1">
                        <input type="tel" class="form-control" id="txtTotal"
                            value='S/ <?php echo number_format($total, 2); ?>' readonly>
                        <label class="ps-4" for="txtTotal">Total a pagar</label>
                    </div>
                </div>

                <hr>

                <!-- Formulario artificial para descartar -->
                <form class="d-none" method="post" action="controller-confirmacion.php" id="frm-descartar">
                    <input type="hidden" name="confirmar-descartar" value='0'>
                </form>

                <!-- Formulario artificial para confirmar -->
                <form class="d-none" method="post" action="controller-confirmacion.php" id="frm-confirmar">
                    <input type="hidden" name="confirmar-descartar" value='1'>
                </form>

                <!-- Botones -->
                <div class="row g-3">
                    <div class="col-4 text-center">
                        <a href="./promociones.php" class="btn btn-warning btn-lg px-5 py-2">VOLVER</a>
                    </div>
                    <div class="col-4 text-center">
                        <button type="submit" class="btn btn-danger btn-lg px-5 py-2" id="btn-descartar">DESCARTAR ESTE PEDIDO</button>
                    </div>
                    <div class="col-4 text-center">
                        <button type="submit" class="btn btn-success btn-lg px-5 py-2" form="frm-confirmar">CONFIRMAR PEDIDO</button>
                    </div>
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

    <!-- Asignar evento click al botón Descartar -->
    <script>
        $(document).on("click", "#btn-descartar", function () {
            // Crear mensaje de confirmación
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: "btn btn-success me-4",
                    cancelButton: "btn btn-danger me-4"
                },
                buttonsStyling: false
            });
            swalWithBootstrapButtons.fire({
                title: "¿Seguro que desea descartar su pedido?",
                text: "Esta operación es irreversible",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Sí, descartar",
                cancelButtonText: "No, cancelar",
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Hacer submit en el formulario
                    $("#frm-descartar").submit();
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    swalWithBootstrapButtons.fire({
                        title: "Operación cancelada",
                        text: "No se descartó el pedido",
                        icon: "success"
                    });
                }
            });
        });
    </script>

</body>

</html>