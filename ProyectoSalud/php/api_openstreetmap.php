<?php
/**
 * API OpenStreetMap - Endpoint que utiliza el Controlador MVC
 * Delega la lógica al ControladorPrincipal
 */

require_once __DIR__ . '/../controlador/ControladorPrincipal.php';

$controlador = new ControladorPrincipal();
$controlador->apiOpenStreetMap();
