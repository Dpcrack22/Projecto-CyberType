<?php
    session_name("jugadorSession");
    session_start();

    $difficulty = $_POST['difficulty'] ?? '';

    $archivo = __DIR__ . '/sentences.txt';
    $lineas = file($archivo, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    $frasesFiltradas = [];
    foreach ($lineas as $linea) {
        list($nivelFrase, $frases) = explode('|', $linea, 2);
        if (strtolower($nivelFrase) === strtolower($difficulty)) {
            $frasesFiltradas = array_map('trim', explode(',', $frases));
            shuffle($frasesFiltradas);
            break;
        }
    }

$lang = $_POST['lang'] ?? $_SESSION['lang'] ?? 'es';
$_SESSION['lang'] = $lang;

$difficulty = $_POST['difficulty'] ?? 'facil';

$archivo = "./sentences{$lang}.txt";
if (!file_exists($archivo)) {
    echo json_encode([]);
    exit;
}

$lineas = file($archivo, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$frasesFiltradas = [];

foreach ($lineas as $linea) {
    if (strpos($linea, '|') === false) continue;
    list($nivelFrase, $frases) = explode('|', $linea, 2);
    if ($nivelFrase === $difficulty) {
        $frasesFiltradas = array_map('trim', explode(',', $frases));
        shuffle($frasesFiltradas);
        break;
    }
}

$numFrases = match($difficulty) {
    'facil' => 3,
    'medio' => 4,
    'dificil' => 5,
    default => 3
};

$frasesAleatorias = array_slice($frasesFiltradas, 0, $numFrases);
$resultados = [];

foreach ($frasesAleatorias as $frase) {
    $partes = explode('@@', $frase);
    $resultados[] = [
        "frase" => $partes[0],
        "imagen" => isset($partes[1]) ? trim($partes[1]) : ''
    ];
}

echo json_encode($resultados);
