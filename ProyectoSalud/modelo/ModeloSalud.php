<?php
/**
 * Modelo de Salud
 * Contiene toda la lógica de negocio y conexiones con APIs externas
 */
require_once __DIR__ . '/../php/config.php';

class ModeloSalud {
    
    /**
     * Obtener datos de WHO (Organización Mundial de la Salud)
     */
    public function obtenerDatosWHO() {
        $baseURL = 'https://ghoapi.azureedge.net/api';
        
        $indicators = array(
            'WHOSIS_000001' => 'Esperanza de vida al nacer (años)',
            'MDG_0000000001' => 'Tasa de mortalidad infantil (por 1000 nacidos)',
            'SA_0000001688' => 'Prevalencia de diabetes (%)'
        );
        
        $countries = array('ESP', 'FRA', 'DEU', 'ITA', 'PRT', 'GBR');
        $allData = array();
        
        foreach ($indicators as $code => $name) {
            $cacheKey = "who_indicator_" . $code;
            $cachedData = $this->getFromCache($cacheKey, 86400); // 24 horas en lugar de 1 hora
            
            if ($cachedData) {
                $data = $cachedData;
            } else {
                $url = $baseURL . '/' . $code;
                $result = makeHTTPRequest($url, 5); // Reducido timeout
                
                if (!$result['success']) {
                    continue;
                }
                
                $data = json_decode($result['data'], true);
                
                if (!$data || !isset($data['value'])) {
                    continue;
                }
                
                $this->saveToCache($cacheKey, $data, 86400); // 24 horas
            }
            
            if (isset($data['value']) && is_array($data['value'])) {
                foreach ($data['value'] as $item) {
                    if (isset($item['SpatialDim']) && in_array($item['SpatialDim'], $countries)) {
                        if (isset($item['NumericValue']) && $item['NumericValue'] !== null) {
                            $allData[] = array(
                                'region' => $this->getCountryName($item['SpatialDim']),
                                'name' => $name,
                                'value' => number_format($item['NumericValue'], 1, '.', ''),
                                'year' => isset($item['TimeDim']) ? intval($item['TimeDim']) : 2021
                            );
                        }
                    }
                }
            }
        }
        
        return array(
            'success' => !empty($allData),
            'data' => $allData,
            'count' => count($allData),
            'source' => 'WHO (World Health Organization) - API GHO',
            'api_url' => 'https://ghoapi.azureedge.net/api'
        );
    }
    
    /**
     * Obtener datos de España desde datos.gob.es
     */
    public function obtenerDatosEspana() {
        $baseURL = 'https://datos.gob.es/apidata/catalog/dataset';
        
        // La API datos.gob.es no acepta filtros complejos, obtenemos más resultados y filtramos en PHP
        $params = array(
            '_pageSize' => '100'  // Obtener más resultados para filtrar
        );
        
        $url = $baseURL . '?' . http_build_query($params);
        $cacheKey = "datos_gob_es_salud";
        
        $cachedData = $this->getFromCache($cacheKey, 86400); // 24 horas
        
        if ($cachedData) {
            return $cachedData;
        }
        
        $result = makeHTTPRequest($url, 10); // Aumentar timeout a 10 segundos para esta API
        
        if (!$result['success']) {
            return array(
                'success' => false,
                'error' => isset($result['error']) ? $result['error'] : 'No se pudo conectar con la API de datos.gob.es',
                'source' => 'Portal de Datos Abiertos de España',
                'api_url' => $url
            );
        }
        
        $data = json_decode($result['data'], true);
        
        if (!$data || !isset($data['result']) || !isset($data['result']['items'])) {
            return array(
                'success' => false,
                'error' => 'Formato de respuesta inesperado de la API',
                'source' => 'Portal de Datos Abiertos de España',
                'api_url' => $url
            );
        }
        
        // Filtrar datasets relacionados con salud
        $datasets = array();
        $healthKeywords = array('salud', 'health', 'hospital', 'sanitari', 'médico', 'medico', 'enferm', 'sanidad', 'covid', 'vacun');
        
        foreach ($data['result']['items'] as $item) {
            $title = '';
            $description = '';
            
            // Extraer título
            if (isset($item['title'])) {
                if (is_array($item['title'])) {
                    $title = isset($item['title'][0]['_value']) ? $item['title'][0]['_value'] : '';
                } else {
                    $title = $item['title'];
                }
            }
            
            // Extraer descripción
            if (isset($item['description'])) {
                if (is_array($item['description'])) {
                    $description = isset($item['description'][0]['_value']) ? $item['description'][0]['_value'] : '';
                } else {
                    $description = $item['description'];
                }
            }
            
            // Verificar si contiene palabras clave de salud
            $isHealthRelated = false;
            $textToSearch = strtolower($title . ' ' . $description);
            
            foreach ($healthKeywords as $keyword) {
                if (strpos($textToSearch, $keyword) !== false) {
                    $isHealthRelated = true;
                    break;
                }
            }
            
            if ($isHealthRelated) {
                // Extraer publicador
                $publisher = 'Desconocido';
                if (isset($item['publisher'])) {
                    if (is_array($item['publisher'])) {
                        $publisher = isset($item['publisher'][0]['_value']) ? $item['publisher'][0]['_value'] : 
                                    (isset($item['publisher'][0]['name']) ? $item['publisher'][0]['name'] : 'Desconocido');
                    } else {
                        $publisher = $item['publisher'];
                    }
                }
                
                // Extraer fecha de modificación
                $modified = 'N/A';
                if (isset($item['modified'])) {
                    if (is_array($item['modified'])) {
                        $modified = isset($item['modified'][0]['_value']) ? $item['modified'][0]['_value'] : 'N/A';
                    } else {
                        $modified = $item['modified'];
                    }
                    if ($modified != 'N/A' && strlen($modified) > 10) {
                        $modified = date('Y-m-d', strtotime($modified));
                    }
                }
                
                $datasets[] = array(
                    'title' => substr($title, 0, 200),
                    'description' => substr($description, 0, 250) . (strlen($description) > 250 ? '...' : ''),
                    'publisher' => $publisher,
                    'modified' => $modified,
                    'url' => isset($item['_about']) ? $item['_about'] : '#'
                );
                
                // Limitar a 20 resultados
                if (count($datasets) >= 20) {
                    break;
                }
            }
        }
        
        $resultData = array(
            'success' => true,
            'data' => $datasets,
            'count' => count($datasets),
            'source' => 'Portal de Datos Abiertos de España',
            'api_url' => $url
        );
        
        $this->saveToCache($cacheKey, $resultData, 86400); // 24 horas
        
        return $resultData;
    }
    
    /**
     * Obtener hospitales desde OpenStreetMap
     */
    public function obtenerHospitalesOSM($lat = 40.4168, $lon = -3.7038, $radius = 10000) {
        $overpassURL = 'https://overpass-api.de/api/interpreter';
        
        $query = <<<OVERPASS
[out:json][timeout:10];
(
  node["amenity"="hospital"](around:{$radius},{$lat},{$lon});
  way["amenity"="hospital"](around:{$radius},{$lat},{$lon});
  node["amenity"="clinic"](around:{$radius},{$lat},{$lon});
  way["amenity"="clinic"](around:{$radius},{$lat},{$lon});
  node["amenity"="doctors"](around:{$radius},{$lat},{$lon});
);
out center;
OVERPASS;
        
        $cacheKey = "osm_hospitals_{$lat}_{$lon}_{$radius}";
        
        $cachedData = $this->getFromCache($cacheKey, 86400); // 24 horas
        
        if ($cachedData) {
            return $cachedData;
        }
        
        $context = stream_context_create(array(
            'http' => array(
                'method' => 'POST',
                'header' => 'Content-Type: application/x-www-form-urlencoded',
                'content' => 'data=' . urlencode($query),
                'timeout' => 10  // Reducido de 30 a 10 segundos
            )
        ));
        
        $response = @file_get_contents($overpassURL, false, $context);
        
        if ($response === false) {
            return array(
                'success' => false,
                'error' => 'No se pudo conectar con la API Overpass de OpenStreetMap',
                'source' => 'OpenStreetMap'
            );
        }
        
        $data = json_decode($response, true);
        
        if (!$data || !isset($data['elements'])) {
            return array(
                'success' => false,
                'error' => 'Formato de respuesta inesperado de Overpass API',
                'source' => 'OpenStreetMap'
            );
        }
        
        $hospitals = array();
        foreach ($data['elements'] as $element) {
            $name = 'Centro de Salud';
            if (isset($element['tags']['name'])) {
                $name = $element['tags']['name'];
            }
            
            $type = 'Hospital';
            if (isset($element['tags']['amenity'])) {
                switch ($element['tags']['amenity']) {
                    case 'hospital':
                        $type = 'Hospital';
                        break;
                    case 'clinic':
                        $type = 'Clínica';
                        break;
                    case 'doctors':
                        $type = 'Consultorio Médico';
                        break;
                }
            }
            
            $elementLat = null;
            $elementLon = null;
            
            if (isset($element['lat']) && isset($element['lon'])) {
                $elementLat = $element['lat'];
                $elementLon = $element['lon'];
            } elseif (isset($element['center'])) {
                $elementLat = $element['center']['lat'];
                $elementLon = $element['center']['lon'];
            }
            
            if ($elementLat && $elementLon) {
                $address = '';
                if (isset($element['tags']['addr:street'])) {
                    $address = $element['tags']['addr:street'];
                    if (isset($element['tags']['addr:housenumber'])) {
                        $address .= ', ' . $element['tags']['addr:housenumber'];
                    }
                }
                
                $hospitals[] = array(
                    'name' => $name,
                    'type' => $type,
                    'lat' => floatval($elementLat),
                    'lon' => floatval($elementLon),
                    'address' => $address ? $address : 'Dirección no disponible',
                    'osm_id' => isset($element['id']) ? $element['id'] : null
                );
            }
        }
        
        $resultData = array(
            'success' => true,
            'data' => $hospitals,
            'count' => count($hospitals),
            'source' => 'OpenStreetMap - Overpass API',
            'api_url' => 'https://overpass-api.de/api/interpreter'
        );
        
        $this->saveToCache($cacheKey, $resultData, 86400); // 24 horas
        
        return $resultData;
    }
    
    /**
     * Convertir código de país a nombre
     */
    private function getCountryName($code) {
        $countries = array(
            'ESP' => 'España',
            'FRA' => 'Francia',
            'DEU' => 'Alemania',
            'ITA' => 'Italia',
            'PRT' => 'Portugal',
            'GBR' => 'Reino Unido',
            'BEL' => 'Bélgica',
            'SWE' => 'Suecia',
            'NOR' => 'Noruega'
        );
        
        return isset($countries[$code]) ? $countries[$code] : $code;
    }
    
    /**
     * Obtener datos de cache
     */
    private function getFromCache($key, $ttl) {
        if (!CACHE_ENABLED) return null;
        
        $cacheFile = CACHE_DIR . md5($key) . '.json';
        
        if (file_exists($cacheFile)) {
            $fileTime = filemtime($cacheFile);
            $currentTime = time();
            
            if (($currentTime - $fileTime) < $ttl) {
                $content = file_get_contents($cacheFile);
                return json_decode($content, true);
            }
        }
        
        return null;
    }
    
    /**
     * Guardar en cache
     */
    private function saveToCache($key, $data, $ttl) {
        if (!CACHE_ENABLED) return;
        
        try {
            $cacheFile = CACHE_DIR . md5($key) . '.json';
            $jsonData = json_encode($data, JSON_UNESCAPED_UNICODE);
            
            if ($jsonData === false) {
                error_log('Error encoding JSON for cache: ' . json_last_error_msg());
                return;
            }
            
            file_put_contents($cacheFile, $jsonData);
        } catch (Exception $e) {
            error_log('Error saving to cache: ' . $e->getMessage());
        }
    }
}
