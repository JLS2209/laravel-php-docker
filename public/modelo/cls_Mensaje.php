<?php
if (!class_exists("Mensaje")) {
    class Mensaje
    {
        public $id_mensaje;
        public $cliente;
        public $asunto;
        public $contenido;
        public $fecha_hora;

        public function __construct($id_mensaje, $cliente, $asunto, $contenido, $fecha_hora)
        {
            $this->id_mensaje = $id_mensaje;
            $this->cliente = $cliente;
            $this->asunto = $asunto;
            $this->contenido = $contenido;
            $this->fecha_hora = $fecha_hora;
        }

        public function card_edit()
        {
            $nombre_completo = $this->cliente->nombre." ".$this->cliente->apellido;
            $email = $this->cliente->email;
            $tlf = $this->cliente->telefono;
            $usuario = $this->cliente->codigo_usuario;
            $usuario = ($usuario == null) ? "Visitante" : $usuario;

            return "
            <div class='card mb-4 mx-4'>
                <div class='card-body' style='color:black;'>
                    <div class='row'>
                        <!-- Ícono -->
                        <div class='col-md-2 d-flex align-items-center'>
                            <span class='fa-stack fa-2x flex-fill'>
                                <i class='fa-solid fa-circle fa-stack-2x'></i>
                                <i class='fa-solid fa-user fa-stack-1x fa-inverse'></i>
                            </span>
                        </div>
                        <!-- Datos del cliente -->
                        <div class='col-md-10'>
                            <span class='card-text'>$nombre_completo ($usuario)</span><br>
                            <span class='card-text'><small class='text-muted'>E-mail:
                                    $email</small></span><br>
                            <span class='card-text'><small class='text-muted'>Teléfono: $tlf</small></span>
                        </div>
                    </div>
                    <hr>
                    <!-- Asunto y contenido del mensaje -->
                    <h6 class='card-title'>$this->asunto</h6>
                    <p class='card-text' style='text-align: justify'>$this->contenido</p>

                </div>
                <div class='card-footer row'>
                    <!-- Fecha de envío -->
                    <div class='col-md-8 my-auto'>
                        <p class='card-text'><small class='text-muted'>Enviado en $this->fecha_hora</small></p>
                    </div>
                    <div class='col-md-4 row text-end mt-1'>
                        <!-- Botón editar -->
                        <div class='col-6'>
                            <button type='button' class='btn btn-warning btn-editar' data-bs-toggle='modal'
                                data-bs-target='#modal-objetivo'>
                                Editar contenido
                            </button>
                        </div>
                        <!-- Botón eliminar -->
                        <div class='col-6'>
                            <button type='button' class='btn btn-danger btn-eliminar'>
                                Eliminar mensaje
                            </button>
                        </div>

                        <!-- ID oculto -->
                        <div class='d-none'>
                            <table>
                            <tr>
                                <td>$this->id_mensaje</td>                     
                            </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            ";


        }

        public function card_delete()
        {
            $nombre_completo = $this->cliente->nombre." ".$this->cliente->apellido;
            $email = $this->cliente->email;
            $tlf = $this->cliente->telefono;
            $usuario = $this->cliente->codigo_usuario;
            $usuario = ($usuario == null) ? "Visitante" : $usuario;

            return "
            <div class='card mb-4 mx-4'>
                <div class='card-body' style='color:black;'>
                    <div class='row'>
                        <!-- Ícono -->
                        <div class='col-md-2 d-flex align-items-center'>
                            <span class='fa-stack fa-2x flex-fill'>
                                <i class='fa-solid fa-circle fa-stack-2x'></i>
                                <i class='fa-solid fa-user fa-stack-1x fa-inverse'></i>
                            </span>
                        </div>
                        <!-- Datos del cliente -->
                        <div class='col-md-10'>
                            <span class='card-text'>$nombre_completo ($usuario)</span><br>
                            <span class='card-text'><small class='text-muted'>E-mail:
                                    $email</small></span><br>
                            <span class='card-text'><small class='text-muted'>Teléfono: $tlf</small></span>
                        </div>
                    </div>
                    <hr>
                    <!-- Asunto y contenido del mensaje -->
                    <h6 class='card-title'>$this->asunto</h6>
                    <p class='card-text' style='text-align: justify'>$this->contenido</p>

                </div>
                <div class='card-footer row'>
                    <!-- Fecha de envío -->
                    <div class='col-md-8 my-auto'>
                        <p class='card-text'><small class='text-muted'>Enviado en $this->fecha_hora</small></p>
                    </div>
                    <div class='col-md-4 row text-end mt-1'>
                        <!-- Botón eliminar -->
                        <div class='col-12'>
                            <button type='button' class='btn btn-danger btn-eliminar'>
                                Eliminar mensaje
                            </button>
                        </div>

                        <!-- ID oculto -->
                        <div class='d-none'>
                            <table>
                            <tr>
                                <td>$this->id_mensaje</td>                      
                            </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            ";


        }
    }
}
?>