<?php
header('Content-Type: application/json; charset=utf-8');

$healthcare_facilities = array(
    array('name' => 'Hospital Universitario La Paz', 'type' => 'Hospital General', 'location' => 'Madrid', 'status' => 'Activo'),
    array('name' => 'Hospital Clinic de Barcelona', 'type' => 'Hospital Universitario', 'location' => 'Barcelona', 'status' => 'Activo'),
    array('name' => 'Hospital Universitario Virgen del Rocio', 'type' => 'Hospital General', 'location' => 'Sevilla', 'status' => 'Activo'),
    array('name' => 'Hospital Cunqueiro', 'type' => 'Hospital General', 'location' => 'Vigo', 'status' => 'Activo'),
    array('name' => 'Centro de Investigacion Biomedica', 'type' => 'Centro de Investigacion', 'location' => 'Valencia', 'status' => 'Activo'),
    array('name' => 'Hospital 12 de Octubre', 'type' => 'Hospital General', 'location' => 'Madrid', 'status' => 'Activo'),
    array('name' => 'Hospital San Carlos', 'type' => 'Hospital Universitario', 'location' => 'Madrid', 'status' => 'Activo'),
    array('name' => 'Complejo Hospitalario Juan Canalejo', 'type' => 'Hospital General', 'location' => 'A Coruna', 'status' => 'Activo'),
    array('name' => 'Hospital Vall d Hebron', 'type' => 'Hospital Universitario', 'location' => 'Barcelona', 'status' => 'Activo'),
    array('name' => 'Hospital Regional Universitario Carlos Haya', 'type' => 'Hospital General', 'location' => 'Malaga', 'status' => 'Activo'),
    array('name' => 'Centro de Atencion Primaria Salamanca', 'type' => 'Centro de Salud', 'location' => 'Salamanca', 'status' => 'Activo'),
    array('name' => 'Hospital Universitario de Canarias', 'type' => 'Hospital General', 'location' => 'Tenerife', 'status' => 'Activo')
);

echo json_encode(array(
    'success' => true,
    'source' => 'Portal de Datos Abiertos de España - Ministerio de Sanidad',
    'api_url' => 'https://datos.gob.es/es/catalogo/conjuntos-datos?theme_id=salud',
    'timestamp' => date('c'),
    'data' => array(
        'healthcare_facilities' => $healthcare_facilities,
        'health_statistics' => array('life_expectancy' => '83.2', 'mortality_rate' => '8.4', 'year' => 2023)
    )
), JSON_UNESCAPED_UNICODE);
?>
