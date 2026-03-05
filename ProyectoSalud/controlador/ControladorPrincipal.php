<?php
/**
 * Controlador Principal
 * Maneja las peticiones y coordina el modelo con la vista
 */
require_once __DIR__ . '/../modelo/ModeloSalud.php';

class ControladorPrincipal {
    
    private $modelo;
    
    public function __construct() {
        $this->modelo = new ModeloSalud();
    }
    
    /**
     * Muestra la página principal
     */
    public function inicio() {
        require_once __DIR__ . '/../vista/VistaInicio.php';
    }
    
    /**
     * API: Obtener datos de WHO
     */
    public function apiWHO() {
        header('Content-Type: application/json; charset=utf-8');
        
        try {
            $datos = $this->modelo->obtenerDatosWHO();
            
            if (!$datos['success'] || empty($datos['data'])) {
                echo json_encode(array(
                    'success' => false,
                    'source' => $datos['source'],
                    'api_url' => $datos['api_url'],
                    'timestamp' => date('c'),
                    'error' => 'No se pudieron obtener datos de la API de WHO. Esto puede deberse a problemas de conectividad o limitaciones de la API.',
                    'note' => 'La API de WHO puede tardar en responder o tener limitaciones de acceso.'
                ), JSON_UNESCAPED_UNICODE);
            } else {
                echo json_encode(array(
                    'success' => true,
                    'source' => $datos['source'],
                    'api_url' => $datos['api_url'],
                    'timestamp' => date('c'),
                    'data' => array('indicators' => $datos['data']),
                    'count' => $datos['count']
                ), JSON_UNESCAPED_UNICODE);
            }
        } catch (Exception $e) {
            echo json_encode(array(
                'success' => false,
                'error' => 'Error al procesar datos: ' . $e->getMessage(),
                'timestamp' => date('c')
            ), JSON_UNESCAPED_UNICODE);
        }
    }
    
    /**
     * API: Obtener datos de España
     */
    public function apiEspana() {
        header('Content-Type: application/json; charset=utf-8');
        
        try {
            $datos = $this->modelo->obtenerDatosEspana();
            
            if (!$datos['success']) {
                echo json_encode(array(
                    'success' => false,
                    'source' => isset($datos['source']) ? $datos['source'] : 'Portal de Datos Abiertos de España',
                    'api_url' => isset($datos['api_url']) ? $datos['api_url'] : 'https://datos.gob.es/apidata/catalog/dataset',
                    'timestamp' => date('c'),
                    'error' => isset($datos['error']) ? $datos['error'] : 'Error desconocido',
                    'note' => 'Los datos se obtienen directamente de la API CKAN de datos.gob.es'
                ), JSON_UNESCAPED_UNICODE);
            } else {
                echo json_encode(array(
                    'success' => true,
                    'source' => $datos['source'],
                    'api_url' => $datos['api_url'],
                    'timestamp' => date('c'),
                    'data' => array(
                        'health_datasets' => $datos['data']
                    ),
                    'count' => $datos['count']
                ), JSON_UNESCAPED_UNICODE);
            }
        } catch (Exception $e) {
            echo json_encode(array(
                'success' => false,
                'error' => 'Error al procesar datos: ' . $e->getMessage(),
                'timestamp' => date('c')
            ), JSON_UNESCAPED_UNICODE);
        }
    }
    
    /**
     * API: Obtener hospitales de OpenStreetMap
     */
    public function apiOpenStreetMap() {
        header('Content-Type: application/json; charset=utf-8');
        
        $lat = isset($_GET['lat']) ? floatval($_GET['lat']) : 40.4168;
        $lon = isset($_GET['lon']) ? floatval($_GET['lon']) : -3.7038;
        $radius = isset($_GET['radius']) ? intval($_GET['radius']) : 10000;
        
        try {
            $datos = $this->modelo->obtenerHospitalesOSM($lat, $lon, $radius);
            
            if (!$datos['success']) {
                echo json_encode(array(
                    'success' => false,
                    'source' => isset($datos['source']) ? $datos['source'] : 'OpenStreetMap - Overpass API',
                    'api_url' => isset($datos['api_url']) ? $datos['api_url'] : 'https://overpass-api.de/api/interpreter',
                    'timestamp' => date('c'),
                    'error' => isset($datos['error']) ? $datos['error'] : 'Error desconocido',
                    'note' => 'Los datos se obtienen en tiempo real desde OpenStreetMap usando Overpass API'
                ), JSON_UNESCAPED_UNICODE);
            } else {
                echo json_encode(array(
                    'success' => true,
                    'source' => isset($datos['source']) ? $datos['source'] : 'OpenStreetMap - Overpass API',
                    'api_url' => isset($datos['api_url']) ? $datos['api_url'] : 'https://overpass-api.de/api/interpreter',
                    'timestamp' => date('c'),
                    'query_params' => array(
                        'latitude' => $lat,
                        'longitude' => $lon,
                        'radius_meters' => $radius
                    ),
                    'data' => array(
                        'hospitals' => isset($datos['data']) ? $datos['data'] : array()
                    ),
                    'count' => isset($datos['count']) ? $datos['count'] : 0
                ), JSON_UNESCAPED_UNICODE);
            }
        } catch (Exception $e) {
            echo json_encode(array(
                'success' => false,
                'error' => 'Error al procesar datos: ' . $e->getMessage(),
                'timestamp' => date('c')
            ), JSON_UNESCAPED_UNICODE);
        }
    }
}
