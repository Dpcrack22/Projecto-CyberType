<?php
require_once(__DIR__ . '/_auth.php');

$lang = $_POST['lang'] ?? $_SESSION['lang_admin'] ?? 'es';
$_SESSION['lang_admin'] = $lang;

$archivo = __DIR__ . '/../../sentences' . $lang . '.txt';

$items = [];

if (file_exists($archivo)) {
    $lineas = file($archivo, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lineas as $linea) {
        if (strpos($linea, '|') === false) continue;

        list($dificultad, $fraseStr) = explode('|', $linea, 2);
        $dificultad = trim($dificultad);

        $frases = array_filter(array_map('trim', explode(',', $fraseStr)), fn($v) => $v !== '');

        foreach ($frases as $f) {
            $raw = $f;
            $partes = explode('@@', $f, 2);
            $soloFrase = trim($partes[0] ?? '');
            $imagen = trim($partes[1] ?? '');

            $items[] = [
                'id' => sha1($lang . '|' . $dificultad . '|' . $raw),
                'dificultad' => $dificultad,
                'frase' => $soloFrase,
                'imagen' => $imagen,
                'raw' => $raw,
            ];
        }
    }
}

echo json_encode([
    'ok' => true,
    'lang' => $lang,
    'count' => count($items),
    'items' => $items,
]);
