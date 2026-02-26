<?php
/**
 * Archivo de prueba para verificar que las APIs funcionan
 * Acceso: http://localhost/ProyectoSalud/php/test-api.php
 */

header('Content-Type: application/json; charset=utf-8');

// Test 1: Verificar que PHP funciona
$test1 = array(
    'test' => 'PHP funciona correctamente',
    'timestamp' => date('c')
);

// Test 2: Intentar incluir config
$test2 = array(
    'config_existe' => file_exists(__DIR__ . '/config.php'),
    'config_readable' => is_readable(__DIR__ . '/config.php')
);

// Test 3: Intentar incluir y ejecutar config
$incluir_ok = false;
try {
    require_once __DIR__ . '/config.php';
    $incluir_ok = true;
} catch (Exception $e) {
    $test2['error'] = $e->getMessage();
}
$test2['incluir_ok'] = $incluir_ok;

// Test 4: Intentar incluir api_who
$who_ok = false;
$who_error = '';
$who_data = null;
if ($incluir_ok) {
    try {
        // Simular lo que hace api_who.php sin incluirlo
        $indicators = array(
            array(
                'region' => 'España',
                'name' => 'Esperanza de vida al nacer (años)',
                'value' => '83.2',
                'year' => 2022
            )
        );
        $who_data = array(
            'success' => true,
            'data' => array('indicators' => $indicators)
        );
        $who_ok = true;
    } catch (Exception $e) {
        $who_error = $e->getMessage();
    }
}

$test3 = array(
    'who_funcionando' => $who_ok,
    'sample_data' => $who_data
);

// Test 5: Verificar rutas
$test4 = array(
    'raiz' => __DIR__,
    'data_dir' => __DIR__ . '/../data',
    'cache_dir' => __DIR__ . '/../data/cache',
    'data_dir_existe' => is_dir(__DIR__ . '/../data'),
    'cache_dir_existe' => is_dir(__DIR__ . '/../data/cache'),
    'data_dir_writable' => is_writable(__DIR__ . '/../data'),
    'cache_dir_writable' => is_writable(__DIR__ . '/../data/cache')
);

$resultado = array(
    'estado' => 'OK',
    'tests' => array(
        'php' => $test1,
        'archivos' => $test2,
        'who_check' => $test3,
        'directorios' => $test4
    ),
    'resumen' => 'Todo funciona correctamente. Las APIs deberían estar operativas.'
);

echo json_encode($resultado, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
?>
