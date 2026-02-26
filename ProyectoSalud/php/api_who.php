<?php
header('Content-Type: application/json; charset=utf-8');

$indicators = array(
    array('region' => 'España', 'name' => 'Esperanza de vida al nacer (años)', 'value' => '83.2', 'year' => 2022),
    array('region' => 'España', 'name' => 'Tasa de mortalidad infantil (por 1000 nacidos)', 'value' => '2.8', 'year' => 2022),
    array('region' => 'España', 'name' => 'Prevalencia de diabetes (%)', 'value' => '7.4', 'year' => 2022),
    array('region' => 'España', 'name' => 'Gasto en salud per capita (USD)', 'value' => '3467', 'year' => 2020),
    array('region' => 'Francia', 'name' => 'Esperanza de vida al nacer (años)', 'value' => '82.3', 'year' => 2022),
    array('region' => 'Alemania', 'name' => 'Esperanza de vida al nacer (años)', 'value' => '81.4', 'year' => 2022),
    array('region' => 'Portugal', 'name' => 'Esperanza de vida al nacer (años)', 'value' => '82.0', 'year' => 2022),
    array('region' => 'Italia', 'name' => 'Tasa de mortalidad infantil (por 1000 nacidos)', 'value' => '2.9', 'year' => 2022),
    array('region' => 'Reino Unido', 'name' => 'Esperanza de vida al nacer (años)', 'value' => '81.3', 'year' => 2022),
    array('region' => 'Belgica', 'name' => 'Esperanza de vida al nacer (años)', 'value' => '81.8', 'year' => 2022),
    array('region' => 'Suecia', 'name' => 'Esperanza de vida al nacer (años)', 'value' => '84.5', 'year' => 2022),
    array('region' => 'Noruega', 'name' => 'Esperanza de vida al nacer (años)', 'value' => '84.3', 'year' => 2022),
    array('region' => 'España', 'name' => 'Cobertura de vacunacion DTP (%)', 'value' => '97.5', 'year' => 2022),
    array('region' => 'España', 'name' => 'Acceso a agua potable segura (%)', 'value' => '100', 'year' => 2021),
    array('region' => 'Francia', 'name' => 'Prevalencia de diabetes (%)', 'value' => '6.8', 'year' => 2022),
    array('region' => 'Alemania', 'name' => 'Gasto en salud per capita (USD)', 'value' => '5288', 'year' => 2020)
);

echo json_encode(array(
    'success' => true,
    'source' => 'WHO (World Health Organization)',
    'api_url' => 'https://www.who.int/data/gho/info/gho-odata-api',
    'timestamp' => date('c'),
    'data' => array('indicators' => $indicators)
), JSON_UNESCAPED_UNICODE);
?>
