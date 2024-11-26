<?php
if (!class_exists("Promocion")) {
    class Promocion
    {
        public $id;
        public $nombre;
        public $descripcion;
        public $imagen;
        public $cantidad_max;
        public $descuento;
        public $items;
        public $precio_regular;
        public $precio_final;

        public function __construct($id, $nombre, $descripcion, $imagen, $cantidad_max, $descuento)
        {
            $this->id = $id;
            $this->nombre = $nombre;
            $this->descripcion = $descripcion;
            $this->imagen = $imagen;
            $this->cantidad_max = $cantidad_max;
            $this->descuento = $descuento;
            $this->items = [];
        }

        public function set_precio_regular() {
            $precio = 0.0;
            foreach ($this->items as $item) {
                $precio += $item["precio_plato"]*$item["cantidad_plato"];
            }
            $this->precio_regular = $precio;
        }

        public function set_precio_final() {
            $this->precio_final = $this->precio_regular * (100 - $this->descuento)/100.0;
        }

        public function slider_item($dir_img, $is_active)
        {
            if ($is_active) {
                echo "
                <div class='carousel-item active'>
                    <img src='$dir_img/promociones/$this->imagen' class='d-block w-75 m-auto'>
                    <div class='carousel-caption d-none d-md-block' style='background-color: black; opacity:0.75'>
                        <h5>$this->nombre</h5>
                        <p>$this->descripcion <br> Descuento promocional: $this->descuento %</p>
                    </div>
                </div>
                ";
            } else {
                echo "
                <div class='carousel-item'>
                    <img src='$dir_img/promociones/$this->imagen' class='d-block w-75 m-auto'>
                    <div class='carousel-caption d-none d-md-block' style='background-color: black; opacity:0.75'>
                        <h5>$this->nombre</h5>
                        <p>$this->descripcion <br> Descuento promocional: $this->descuento %</p>
                    </div>
                </div>
                ";
            }
        }

        public function card_vertical_edit($dir_img)
        {
            $modal = "#modal-objetivo";
            // Abrir carta
            $carta = "
            <div class='card rounded-6 mb-3 border border-dark' style='max-width: 900px; margin: 0 auto'>
                    <div class='row g-0 align-items-center'>
                        <!-- Sección izquierda -->
                        <div class='col-md-5 d-flex flex-column justify-content-center align-items-center'>
                            <!-- Imagen -->
                            <img src='$dir_img/promociones/$this->imagen'
                                class='rounded-4 w-75 my-3 border border-secondary'>
                            
                            <div class='d-grid w-50 gap-2 mt-auto mb-2'>
                                <!-- Botón editar promoción -->
                                <button type='button' class='btn btn-secondary btn-editar-prom' data-bs-toggle='modal'
                                    data-bs-target='$modal'>
                                    Editar descripción
                                </button>

                                <!-- Botón editar items -->
                                <button type='button' class='btn btn-secondary btn-editar-items'>
                                    Editar items
                                </button>

                                <!-- Botón eliminar -->
                                <button type='button' class='btn btn-danger btn-eliminar'>
                                    Eliminar promoción
                                </button>                            

                                <!-- Crear un div oculto solo para el ID -->
                                <p class='d-none hidden-id'>$this->id</p>
                            </div>
                        </div>

                        <!-- Sección derecha -->
                        <div class='col-md-7'>
                            <div class='card-body' style='color: black'>
                                <!-- Nombre y descripción -->
                                <h5 class='card-title fw-bold'>$this->nombre</h5>
                                <p class='card-text'>$this->descripcion</p>

                                <!-- Cantidad máxima y descuento promocional -->
                                <p class='card-text'>Cantidad máxima por pedido: <strong>$this->cantidad_max</strong></p>
                                <h4 class='text-end me-4'>
                                    <span class='badge rounded-pill text-bg-success py-2 px-3'> - $this->descuento %</span>
                                </h4>

                                <!-- Precio regular y Precio final -->
                                <p class='card-text mb-4'>
                                    <span class='text-muted text-decoration-line-through me-4'>
                                        S/. " . number_format($this->precio_regular, 2) . "
                                    </span>
                                    <span class='text-muted border border-secondary rounded-3 p-2 fw-bold'>
                                        S/. " . number_format($this->precio_final, 2) . "
                                    </span>
                                </p>

                                <!-- Items -->
                                <div class='table-responsive'>
                                    <table class='table mt-3'>
                                        <thead class='table-dark'>
                                            <tr>
                                                <th scope='col' class='ps-3'>Item</th>
                                                <th scope='col'>Cantidad</th>
                                                <th scope='col'>Precio regular</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Llenar items en la tabla -->
                    ";
            // Ingresar cada item
            foreach ($this->items as $item) {
                $nombre_plato = $item["nombre_plato"];
                $cantidad_plato = $item["cantidad_plato"];
                $precio_plato = $item["precio_plato"];
                $carta .= "
                                            <tr>
                                                <td class='ps-3'>$nombre_plato</td>
                                                <td>$cantidad_plato</td>
                                                <td>S/. " . number_format($precio_plato, 2) . "</td>
                                            </tr>
                    ";
            }
            // Cerrar carta
            $carta .= "
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>                    
                </div>";
            
            return $carta;
        }


    }
}
?>