<?php
if (!class_exists("Pedido")) {
    class Pedido
    {
        public $nro_pedido;
        public $nro_cliente;
        public $opcion_entrega;
        /**
         * @var Ubicacion
         */
        public $ubicacion;
        public $metodo_pago;
        public $costo_delivery;
        public $total_pagar;
        public $fecha_hora;
        public $estado;
        /**
         * @var array
         */
        public $lista_platos;
        /**
         * @var array
         */
        public $lista_promociones;


        public function __construct($nro_pedido, $nro_cliente, $opcion_entrega, $ubicacion, $metodo_pago, $costo_delivery, $total_pagar, $fecha_hora, $estado)
        {
            $this->nro_pedido = $nro_pedido;
            $this->nro_cliente = $nro_cliente;
            $this->opcion_entrega = $opcion_entrega;
            $this->ubicacion = $ubicacion;
            $this->metodo_pago = $metodo_pago;
            $this->costo_delivery = $costo_delivery;
            $this->total_pagar = $total_pagar;
            $this->fecha_hora = $fecha_hora;
            $this->estado = $estado;
            $this->lista_platos = [];
            $this->lista_promociones = [];
        }

        public function set_total()
        {
            $precio = 0.0;
            foreach ($this->lista_platos as $plato) {
                $precio += $plato["precio_un_plato"] * $plato["cantidad_plato"];
            }
            foreach ($this->lista_promociones as $promocion) {
                $precio += $promocion["precio_un_promocion"] * $promocion["cantidad_promocion"];
            }
            $precio += $this->costo_delivery;
            $this->total_pagar = $precio;
        }

    }
}
?>