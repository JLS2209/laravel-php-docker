<?php
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

    // Acceder al modelo y controlador
    include "../../modelo/cls_Promocion.php";
    include "../../controlador/ctrl_Promocion.php";
    $ctrlPromocion = new ControladorPromocion();

    // Recuperar valores de los controles del formulario "post"
    $id_promocion = $_POST['id-promocion'];
    $arr_id_plato = @$_POST['id-plato'];
    $arr_cantidad_plato = @$_POST['cantidad-plato'];

    // Conectar a base de datos
    $cn = (new Conectar())->getConectar();

    // 1) Eliminar todos los detalles actuales de la promoción
    $sql = "DELETE FROM tb_detalle_promocion WHERE id_promocion='$id_promocion';";
    $estado = mysqli_query($cn, $sql);

    // Verificar éxito de la sentencia
    if ($estado == false) {
        $_SESSION["mensaje"] = -10; // Mensaje: Hubo un error
        // Cerrar conexión
        mysqli_close($cn);
        // Redirigir a la página de editar items
        header("location: ../adm-promocion/editarItems.php");
        return;
    } else {
        $_SESSION["mensaje"] = 1;  // Mensaje: Se eliminaron todos los items
    }

    // 2) Proceder con el INSERT solo si los arreglos no son nulos
    if ($arr_id_plato != null && $arr_cantidad_plato != null) {
        // Preparar INSERT
        $sql = "INSERT INTO tb_detalle_promocion(id_promocion, id_plato, cantidad_plato) VALUES "; 
        // Recorrer los arreglos en paralelo
        for ($i=0; $i < sizeof($arr_id_plato); $i++) { 
            // Acceder a los parámetros
            $id_plato = $arr_id_plato[$i];
            $cantidad_plato = $arr_cantidad_plato[$i];

            // Agregar a la sentencia
            $sql .= "('$id_promocion', '$id_plato', '$cantidad_plato') ";
            if ($i != sizeof($arr_id_plato) - 1) {$sql .= ", ";}
        }

        // Ejecutar INSERT
        $estado = mysqli_query($cn, $sql);

        // Verificar éxito de la sentencia
        if ($estado == false) {
            $_SESSION["mensaje"] = -1; // Mensaje: No se actualizó
        } else {
            $_SESSION["mensaje"] = 2;  // Mensaje: Sí se actualizó
        }
    }

    // Redirigir a la página de registro
    header("location: ../adm-promocion/editarItems.php");

} catch (Exception $e) {
    $_SESSION["mensaje"] = -10; // Mensaje: Hubo un error
    header("location: ../adm-promocion/editarItems.php");
}
?>