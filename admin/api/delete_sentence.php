<?php
require_once(__DIR__ . '/_auth.php');

$lang = $_POST['lang'] ?? $_SESSION['lang_admin'] ?? 'es';
$_SESSION['lang_admin'] = $lang;

$dificultad = $_POST['dificultad'] ?? '';
$fraseAEliminar = $_POST['frase'] ?? '';

if ($dificultad === '' || $fraseAEliminar === '') {
    http_response_code(400);
    echo json_encode([
        'ok' => false,
        'error' => 'BAD_REQUEST',
        'message' => 'Faltan parametros: dificultad o frase',
    ]);
    exit;
}

$archivo = __DIR__ . '/../../sentences' . $lang . '.txt';
$usuario = $_SESSION['usuario'] ?? 'Desconocido';

if (!file_exists($archivo)) {
    http_response_code(404);
    echo json_encode([
        'ok' => false,
        'error' => 'NOT_FOUND',
        'message' => 'Archivo de frases no encontrado',
    ]);
    exit;
}

$lineas = file($archivo, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$nuevasLineas = [];
$fraseEncontrada = false;

foreach ($lineas as $linea) {
    $parts = explode('|', $linea, 2);
    $dif = isset($parts[0]) ? trim($parts[0]) : '';
    $frases = isset($parts[1]) ? $parts[1] : '';

    if ($dif !== $dificultad) {
        $nuevasLineas[] = $linea;
        continue;
    }

    $fraseArray = array_filter(array_map('trim', explode(',', $frases)), fn($v) => $v !== '');
    $originalCount = count($fraseArray);

    $fraseArray = array_values(array_filter($fraseArray, function($f) use ($fraseAEliminar) {
        return trim($f) !== trim($fraseAEliminar);
    }));

    if (count($fraseArray) < $originalCount) {
        $fraseEncontrada = true;
    }

    if (!empty($fraseArray)) {
        $nuevasLineas[] = $dif . '|' . implode(',', $fraseArray);
    }
}

if (!$fraseEncontrada) {
    http_response_code(404);
    echo json_encode([
        'ok' => false,
        'error' => 'NOT_FOUND',
        'message' => 'Frase no encontrada',
    ]);
    registrarLog("admin/api/delete_sentence.php", "El admin '$usuario' intento eliminar frase inexistente: '$fraseAEliminar' ($dificultad, $lang)");
    exit;
}

file_put_contents($archivo, implode(PHP_EOL, $nuevasLineas) . PHP_EOL, LOCK_EX);

$soloFrase = explode('@@', $fraseAEliminar, 2)[0] ?? $fraseAEliminar;
registrarLog("admin/api/delete_sentence.php", "El admin '$usuario' elimino la frase '$soloFrase' ($dificultad, $lang)");

echo json_encode([
    'ok' => true,
]);
