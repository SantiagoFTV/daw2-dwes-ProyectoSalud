<?php
/**
 * Punto de entrada principal del proyecto
 * Implementa el patrón MVC (Modelo-Vista-Controlador)
 * 
 * - Modelo: modelo/ModeloSalud.php - Lógica de negocio y conexiones a APIs externas
 * - Vista: vista/VistaInicio.php - Presentación HTML
 * - Controlador: controlador/ControladorPrincipal.php - Coordinación entre modelo y vista
 */

// Incluir el controlador principal
require_once __DIR__ . '/controlador/ControladorPrincipal.php';

// Crear instancia del controlador
$controlador = new ControladorPrincipal();

// Ejecutar la acción de inicio (mostrar la vista principal)
$controlador->inicio();
