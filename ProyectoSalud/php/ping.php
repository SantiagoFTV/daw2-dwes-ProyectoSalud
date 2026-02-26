<?php
// Simple test - verifica si PHP funciona
header('Content-Type: application/json; charset=utf-8');

echo json_encode([
    'php_works' => true,
    'timestamp' => date('c'),
    'message' => 'PHP está funcionando correctamente'
], JSON_UNESCAPED_UNICODE);
?>
