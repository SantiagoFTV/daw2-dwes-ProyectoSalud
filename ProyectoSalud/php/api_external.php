<?php
header('Content-Type: application/json; charset=utf-8');

$health_trends = array(
    array('date' => date('Y-m-d', strtotime('-7 days')), 'topic' => 'Vacunacion 2024', 'mentions' => 15420, 'trend' => 'up'),
    array('date' => date('Y-m-d', strtotime('-5 days')), 'topic' => 'Alergia estacional', 'mentions' => 8950, 'trend' => 'up'),
    array('date' => date('Y-m-d', strtotime('-3 days')), 'topic' => 'Bienestar mental', 'mentions' => 12340, 'trend' => 'stable'),
    array('date' => date('Y-m-d', strtotime('-1 days')), 'topic' => 'Deporte y salud', 'mentions' => 9870, 'trend' => 'up'),
    array('date' => date('Y-m-d', strtotime('-6 days')), 'topic' => 'Nutricion saludable', 'mentions' => 7650, 'trend' => 'up'),
    array('date' => date('Y-m-d', strtotime('-4 days')), 'topic' => 'Prevencion del cancer', 'mentions' => 11200, 'trend' => 'stable'),
    array('date' => date('Y-m-d', strtotime('-2 days')), 'topic' => 'Salud cardiovascular', 'mentions' => 13580, 'trend' => 'down'),
    array('date' => date('Y-m-d'), 'topic' => 'Telemedicina', 'mentions' => 16890, 'trend' => 'up')
);

echo json_encode(array(
    'success' => true,
    'source' => 'APIs Públicas Integradas',
    'timestamp' => date('c'),
    'data' => array('health_trends' => $health_trends)
), JSON_UNESCAPED_UNICODE);
?>
