<?php
if (!class_exists("Plato")) {
    class Plato
    {
        public $id;
        public $nombre;
        public $id_categoria;
        public $nombre_categoria;
        public $tipo_categoria;
        public $descripcion;
        public $imagen;
        public $precio_regular;
        public $descuento_general;
        public $descuento_fidelidad;

        public function __construct($id, $nombre, $id_categoria, $nombre_categoria, $tipo_categoria, $descripcion, $imagen, $precio_regular, $descuento_general, $descuento_fidelidad)
        {
            $this->id = $id;
            $this->nombre = $nombre;
            $this->id_categoria = $id_categoria;
            $this->nombre_categoria = $nombre_categoria;
            $this->tipo_categoria = $tipo_categoria;
            $this->descripcion = $descripcion;
            $this->imagen = $imagen;
            $this->precio_regular = $precio_regular;
            $this->descuento_general = $descuento_general;
            $this->descuento_fidelidad = $descuento_fidelidad;
        }

        /**
         * Genera un card horizontal con los atributos del objeto de clase Plato
         * @param string $dir_img Directorio donde se encuentran las imágenes
         * @param bool $is_cliente_regular Indica si se debe mostrar el descuento general (true) o el descuento por fidelidad (false).
         * @return void
         */
        public function card_horizontal($dir_img, $is_cliente_regular)
        {
            $descuento = ($is_cliente_regular) ? $this->descuento_general : $this->descuento_fidelidad;

            // Inicio de la carta
            echo "
                <div class='card rounded-6 mb-3 border border-dark' style='max-width: 900px; margin: 0 auto'>
                    <div class='row g-0 align-items-center'>
                        <div class='col-md-4 d-flex justify-content-center align-items-center'>
                            <img src='$dir_img/platos/$this->imagen' class='rounded-4 w-75 my-3 border border-secondary'>
                        </div>
                        <div class='col-md-8'>
                            <div class='card-body' style='color: black'>
                                <h5 class='card-title fw-bold'>$this->nombre</h5>
                                <p class='card-text'>$this->descripcion</p>
                ";

            // Si hay descuento, se debe mostrar en la tarjeta junto al precio resultante
            if ($descuento > 0) {
                $precio_resultante = (100.0 - $descuento) * ($this->precio_regular) / 100.0;
                echo "
                                <h4 class='text-end me-4'>
                                    <span class='badge rounded-pill text-bg-success py-2 px-3'> - $descuento %</span>
                                </h4>
                                <p class='card-text mb-4'>
                                    <span class='text-muted text-decoration-line-through me-4'>
                                        S/. " . number_format($this->precio_regular, 2) . "
                                    </span>
                                    <span class='text-muted border border-secondary rounded-3 p-2 fw-bold'>
                                        S/. " . number_format($precio_resultante, 2) . "
                                    </span>
                                </p>
                    ";
            }
            // Si no, solo se muestra el precio regular
            else {
                echo "
                                <p class='card-text mt-4'>
                                    <span class='text-muted border border-secondary rounded-3 p-2'>
                                        S/. " . number_format($this->precio_regular, 2) . "
                                    </span>
                                </p>
                    ";
            }

            // Continúa con el pie de la carta
            echo "
                            </div>
                        </div>
                    </div>

                    <!-- Categoría -->
                    <div class='card-footer'>
                        <span class='text-muted fw-bold'>$this->nombre_categoria</span>
                    </div>
                </div>            
                ";
        }

        /**
         * Genera un card vertical con los atributos del objeto de clase Plato
         * @param string $dir_img Directorio donde se encuentran las imágenes
         * @param bool $is_cliente_regular Indica si se debe mostrar el descuento general (true) o el descuento por fidelidad (false).
         * @return string
         */
        public function card_vertical_edit($dir_img, $is_cliente_regular)
        {
            $descuento = ($is_cliente_regular) ? $this->descuento_general : $this->descuento_fidelidad;
            $modal = "#modal-objetivo";

            // Inicio de la carta
            $carta = "
                <div class='col'>
                <div class='card h-100 border border-dark'>

                    <!-- Imagen -->
                    <img src='$dir_img/platos/$this->imagen' class='card-img-top' style='height:200px'>
                    
                    <div class='card-body d-flex flex-column align-items-end' style='color: black'>

                        <!-- Nombre y descripcion -->
                        <div class='w-100'>
                            <h5 class='card-title'>$this->nombre</h5>
                            <p class='card-text'>$this->descripcion</p>
                ";

            // Si hay descuento, se debe mostrar en la tarjeta junto al precio resultante
            if ($descuento > 0) {
                $precio_resultante = (100.0 - $descuento) * ($this->precio_regular) / 100.0;
                $carta .= "
                            <h4>
                                <span class='badge rounded-pill text-bg-success py-2 px-3'> - $descuento %</span>
                            </h4>
                        </div>
                        <div class='d-grid w-100 gap-2 mt-auto'>
                            <!-- Precios -->
                            <p class='card-text text-end my-4'>
                                <span class='text-muted text-decoration-line-through me-4'>
                                    S/. " . number_format($this->precio_regular, 2) . "
                                </span>
                                <span class='text-muted border border-secondary rounded-3 p-2 fw-bold'>
                                    S/. " . number_format($precio_resultante, 2) . "
                                </span>
                            </p>
                ";
            }
            // Si no, solo se muestra el precio regular
            else {
                $carta .= "
                        </div>
                        <div class='d-grid w-100 gap-2 mt-auto'>
                            <!-- Precios -->
                            <p class='card-text text-end my-4'>
                                <span class='text-muted border border-secondary rounded-3 p-2 fw-bold'>
                                    S/. " . number_format($this->precio_regular, 2) . "
                                </span>
                            </p>
                ";
            }

            // Continúa con el pie de carta
            $carta .= "
                            <!-- Botón editar -->
                            <button type='button' class='btn btn-warning btn-editar' data-bs-toggle='modal'
                                data-bs-target='$modal'>
                                Editar
                            </button>

                            <!-- Botón eliminar -->
                            <button type='button' class='btn btn-danger btn-eliminar'>
                                Eliminar
                            </button>

                            <!-- Botón plato del día -->
                            <button type='button' class='btn btn-primary btn-plato-dia'>
                                Elegir como plato del día
                            </button>

                            <!-- Crear un div oculto solo para almacenar variables -->
                            <p class='d-none hidden-id'>$this->id</p>
                        </div>
                    </div>

                    <!-- Categoría -->
                    <div class='card-footer'>
                        <span class='text-muted fw-bold'>$this->nombre_categoria</span>
                    </div>
                </div>
                </div>
                ";

            return $carta;
        }


        public function card_vertical_menu($dir_img, $is_cliente_regular)
        {
            $descuento = ($is_cliente_regular) ? $this->descuento_general : $this->descuento_fidelidad;

            // Inicio de la carta
            $carta = "
                <div class='col'>
                <div class='card h-100 border border-dark'>

                    <!-- Imagen -->
                    <img src='$dir_img/platos/$this->imagen' class='card-img-top' style='height:200px'>
                    
                    <div class='card-body d-flex flex-column align-items-end' style='color: black'>

                        <!-- Nombre y descripcion -->
                        <div class='w-100'>
                            <h5 class='card-title'>$this->nombre</h5>
                            <p class='card-text'>$this->descripcion</p>
                ";

            // Si hay descuento, se debe mostrar en la tarjeta junto al precio resultante
            if ($descuento > 0) {
                $precio_resultante = (100.0 - $descuento) * ($this->precio_regular) / 100.0;
                $carta .= "
                            <h4>
                                <span class='badge rounded-pill text-bg-success py-2 px-3'> - $descuento %</span>
                            </h4>
                        </div>
                        <div class='d-grid w-100 gap-2 mt-auto'>
                            <!-- Precios -->
                            <p class='card-text text-end my-4'>
                                <span class='text-muted text-decoration-line-through me-4'>
                                    S/. " . number_format($this->precio_regular, 2) . "
                                </span>
                                <span class='text-muted border border-secondary rounded-3 p-2 fw-bold'>
                                    S/. " . number_format($precio_resultante, 2) . "
                                </span>
                            </p>
                ";
            }
            // Si no, solo se muestra el precio regular
            else {
                $carta .= "
                        </div>
                        <div class='d-grid w-100 gap-2 mt-auto'>
                            <!-- Precios -->
                            <p class='card-text text-end my-4'>
                                <span class='text-muted border border-secondary rounded-3 p-2 fw-bold'>
                                    S/. " . number_format($this->precio_regular, 2) . "
                                </span>
                            </p>
                ";
            }

            // Continúa con el pie de carta
            $carta .= "
                        </div>
                    </div>

                    <!-- Categoría -->
                    <div class='card-footer'>
                        <span class='text-muted fw-bold'>$this->nombre_categoria</span>
                    </div>
                </div>
                </div>
                ";

            return $carta;
        }

    }
}
?>