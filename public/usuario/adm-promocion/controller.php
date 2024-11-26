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
    $tipo_crud = $_POST['tipo-crud'];  // Para reconocer si la operación es INSERT/UPDATE o DELETE
    $id_promocion = $_POST['id-promocion'];    // Es 0 si la operación es INSERT, sino es (UPDATE/DELETE)

    // Identificar operación CRUD: Insert / Update / Delete
    if ($tipo_crud == 'delete') {
        // DELETE
        if ($ctrlPromocion->delete($id_promocion)) {
            $_SESSION["mensaje"] = 1; // Mensaje: Eliminación exitosa
        } else {
            $_SESSION["mensaje"] = -1; // Mensaje: Eliminación fallida            
        }
    } else if ($tipo_crud == 'insert-update') {
        // 1) Recuperar valores de los controles del formulario "post"
        $nombre = @$_POST['nombre'];
        $descripcion = @$_POST['descripcion'];
        $cantidad_max = @$_POST['cantidad-max'];
        $descuento = @$_POST['descuento'];
        $nombre_imagen = @$_POST['nombre-imagen'];

        // 2) Validar y subir el archivo cargado a la carpeta de imágenes
        $target_dir = "../../multimedia/imagenes/promociones/";
        $target_file = $target_dir . basename($_FILES["imagen"]["name"]);
        $validate_file = true;
        $img_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Si se esta intentando editar la promoción y no se ha cargado una nueva imagen 
        if ($id_promocion > 0 && $target_dir == $target_file) {
            // No es necesario subir el archivo al servidor
        } else {
            // -- Validar que el archivo sea de tipo imagen
            if (isset($_POST["submit"])) {
                if (getimagesize($_FILES["imagen"]["tmp_name"]) === false) {
                    $_SESSION["mensaje"] = -2; // Mensaje: El archivo subido no es una imagen
                    $validate_file = false;
                }
            }
            // -- Validar si el archivo ya existe
            while (file_exists($target_file)) {
                $target_file = substr($target_file, 0, -(strlen($img_type) + 1)) . rand() . "." . $img_type;
            }
            // -- Validar que el tamaño no exceda 500 KB
            if ($_FILES["imagen"]["size"] > 500000) {
                $_SESSION["mensaje"] = -4; // Mensaje: El archivo subido es muy grande.
                $validate_file = false;
            }
            // -- Validar extensión
            if ($img_type != "jpg" && $img_type != "png" && $img_type != "jpeg") {
                $_SESSION["mensaje"] = -5; // Mensaje: No se permite esta extensión de imagen.
                $validate_file = false;
            }
            // -- Si todo está validado, subir el archivo
            if ($validate_file) {
                if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $target_file)) {
                    $nombre_imagen = htmlspecialchars(basename($_FILES["imagen"]["name"]));
                } else {
                    $_SESSION["mensaje"] = -6; // Mensaje: Ocurrió un error al subir la imagen.
                    $validate_file = false;
                }
            }
        }

        // 3) Solo proceder si el archivo se subió correctamente
        if ($validate_file) {
            // Crear objeto de clase
            $promocion = new Promocion(
                $id_promocion,
                $nombre,
                $descripcion,
                $nombre_imagen,
                $cantidad_max,
                $descuento
            );

            // Identificar operación CRUD: Insert / Update
            if ($id_promocion == 0) {
                // INSERT
                if ($ctrlPromocion->insert($promocion) > 0) {
                    $_SESSION["mensaje"] = 2; // Mensaje: Inserción exitosa
                } else {
                    $_SESSION["mensaje"] = -7; // Mensaje: Inserción fallida            
                }

            } else {
                // UPDATE
                if ($ctrlPromocion->update($promocion)) {
                    $_SESSION["mensaje"] = 3; // Mensaje: Actualización exitosa
                } else {
                    $_SESSION["mensaje"] = -8; // Mensaje: Actualización fallida            
                }
            }
        }
    } else if ($tipo_crud == 'editar-items') {
        // Guardar en sesión el ID de la promoción
        $_SESSION["id_promocion"] = $id_promocion;

        // Redirigir a la página de Editar Items
        header("location: ../adm-promocion/editarItems.php");
        return;
    }

    // Redirigir a la página de registro
    header("location: ../adm-promocion/");

} catch (Exception $e) {
    $_SESSION["mensaje"] = -10; // Mensaje: Hubo un error
    header("location: ../adm-promocion/");
}
?>