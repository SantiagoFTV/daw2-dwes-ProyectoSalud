<?php
// ControladorPrincipal.php
class ControladorPrincipal {
    public function inicio() {
        require_once __DIR__ . '/../modelo/ModeloSalud.php';
        $modelo = new ModeloSalud();
        $datos = $modelo->obtenerDatosSalud();
        require_once __DIR__ . '/../vista/VistaInicio.php';
        mostrarInicio($datos);
    }
}
