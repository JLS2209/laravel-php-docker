<?php
// Acceder al modelo
include "../../modelo/cls_Pedido.php";

try {
    // Iniciar o reanudar sesión
    session_start();

    // Acceder a la clase Conectar
    include "../../cls_conectar/cls_Conectar.php";

    // Expulsar si el acceso es incorrecto
    if (!$_POST) {
        error_log("Intento invalido de acceder a pagina sin POST!");
        header("location: ../../");
        return;
    }

    // Si no existe $_SESSION["pedido"], expulsar de la página
    if (!isset($_SESSION["pedido"])) {
        error_log("Intento de acceder a pedido/controller-ubicacion sin pasar por las secciones previas de pedido.");
        header("location: ../../");
        return;
    }

    // Acceder al modelo Ubicacion
    include "../../modelo/cls_Ubicacion.php";

    // Recuperar valores de los controles del formulario "post"
    $id_distrito = $_POST['id_distrito'];
    $direccion = $_POST['direccion'];
    $coord = $_POST['coordenadas'];

    // Recuperar distrito
    $cn = (new Conectar())->getConectar();
    $sql = "SELECT * FROM tb_distrito WHERE id_distrito = '$id_distrito';";
    $rs = mysqli_query($cn, $sql);
    // Arreglo que almacena objetos
    $distrito = null;
    while ($row = mysqli_fetch_row($rs)) {
        $distrito = new Distrito($row[0], $row[1], $row[2]);
    }

    // Formar objeto de clase Ubicacion
    $ub = new Ubicacion(
        -1,		// Default. Se crea automáticamente en el INSERT a la BD
        $direccion,
        $distrito,
        explode(',', substr($coord, 1, -1))[0],
        explode(',', substr($coord, 1, -1))[1]
    );

    // Actualizar sesión var
    $_SESSION["pedido"]->ubicacion = $ub;

    // OPCIONAL: Actualizar costo de delivery de acuerdo con el distrito

    // Dirigirse a sección 3 (página de platos)
    header('location: ../cli-pedido/platos.php');

} catch (Exception $e) {
    $_SESSION["mensaje"] = -10; // Mensaje: Hubo un error
    header("location: ../cli-pedido/");
}
?>