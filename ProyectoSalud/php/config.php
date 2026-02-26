<?php
/**
 * Configuración del Proyecto Salud
 * Configuración común y utilidades
 */

// Definir zona horaria
date_default_timezone_set('Europe/Madrid');

// Constantes de configuración
define('PROJECT_NAME', 'Portal Integral de Salud');
define('PROJECT_VERSION', '1.0');
define('API_TIMEOUT', 10);
define('CACHE_ENABLED', true);
define('CACHE_DIR', __DIR__ . '/../data/cache/');

// Crear directorio de cache si no existe
if (!file_exists(CACHE_DIR)) {
    mkdir(CACHE_DIR, 0777, true);
}

/**
 * Función para hacer llamadas HTTP GET
 */
function makeHTTPRequest($url, $timeout = API_TIMEOUT) {
    $context = stream_context_create([
        'http' => [
            'timeout' => $timeout,
            'method' => 'GET',
            'header' => 'User-Agent: ' . PROJECT_NAME . '/' . PROJECT_VERSION,
            'ignore_errors' => false
        ],
        'https' => [
            'timeout' => $timeout,
            'method' => 'GET',
            'header' => 'User-Agent: ' . PROJECT_NAME . '/' . PROJECT_VERSION,
            'ignore_errors' => false,
            'verify_peer' => false,
            'verify_peer_name' => false
        ]
    ]);

    try {
        $response = @file_get_contents($url, false, $context);
        if ($response === false) {
            return array('success' => false, 'error' => 'No se pudo conectar con la API');
        }
        return array('success' => true, 'data' => $response);
    } catch (Exception $e) {
        return array('success' => false, 'error' => $e->getMessage());
    }
}

/**
 * Función para obtener datos con caché
 */
function getWithCache($url, $cacheKey, $cacheTTL = 3600) {
    if (CACHE_ENABLED) {
        $cacheFile = CACHE_DIR . md5($cacheKey) . '.json';
        
        // Verificar si existe cache válido
        if (file_exists($cacheFile)) {
            $fileTime = filemtime($cacheFile);
            $currentTime = time();
            
            if (($currentTime - $fileTime) < $cacheTTL) {
                return json_decode(file_get_contents($cacheFile), true);
            }
        }
    }

    // Obtener datos frescos
    $result = makeHTTPRequest($url);
    
    if ($result['success']) {
        $data = json_decode($result['data'], true);
        
        // Guardar en cache
        if (CACHE_ENABLED) {
            $cacheFile = CACHE_DIR . md5($cacheKey) . '.json';
            file_put_contents($cacheFile, json_encode($data));
        }
        
        return $data;
    }
    
    return null;
}

/**
 * Función para procesar errores
 */
function handleError($message, $statusCode = 500) {
    http_response_code($statusCode);
    header('Content-Type: application/json; charset=utf-8');
    
    return json_encode(array(
        'success' => false,
        'error' => $message,
        'timestamp' => date('c')
    ));
}

/**
 * Función para validar JSON
 */
function isValidJSON($string) {
    json_decode($string);
    return json_last_error() === JSON_ERROR_NONE;
}

/**
 * Función para obtener cliente remoto
 */
function getClientIP() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

/**
 * Log de acceso
 */
function logAccess($endpoint, $status = 'success') {
    $logFile = __DIR__ . '/../data/access.log';
    $logMessage = date('Y-m-d H:i:s') . ' | ' . 
                  getClientIP() . ' | ' . 
                  $_SERVER['REQUEST_METHOD'] . ' | ' . 
                  $endpoint . ' | ' . 
                  $status . "\n";
    
    file_put_contents($logFile, $logMessage, FILE_APPEND);
}

// Registrar acceso
if (isset($_SERVER['REQUEST_URI'])) {
    logAccess($_SERVER['REQUEST_URI']);
}
?>
