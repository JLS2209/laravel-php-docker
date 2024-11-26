<?php
if (!class_exists("Ubicacion")) {
    class Ubicacion
    {
        public $id_ubicacion;
        public $direccion;
        public $distrito;
        public $lat;
        public $long;
        
        public function __construct( $id_ubicacion, $direccion, $distrito, $lat, $long ) {
            $this->id_ubicacion = $id_ubicacion;
            $this->direccion = $direccion;
            $this->distrito = $distrito;
            $this->lat = $lat;
            $this->long = $long;
        }

    }
}

if (!class_exists("Distrito")) {
    class Distrito
    {
        public $id_distrito;
        public $nombre_distrito;
        public $nombre_provincia;
        
        public function __construct( $id_distrito, $nombre_distrito, $nombre_provincia ) {
            $this->id_distrito = $id_distrito;
            $this->nombre_distrito = $nombre_distrito;
            $this->nombre_provincia = $nombre_provincia;
        }
    }
}
?>