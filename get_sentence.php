<?php
session_name("jugadorSession");
session_start();

$difficulty = $_POST['difficulty'] ?? '';

$archivo = './sentences.txt';
$lineas = file($archivo, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

$frasesFiltradas = [];
foreach ($lineas as $linea) {
    list($nivelFrase, $frases) = explode('|', $linea, 2);
    if ($nivelFrase === $difficulty) {
        $frasesFiltradas = array_map('trim', explode(',', $frases));
        break;
    }
}

$fraseAleatoria = $frasesFiltradas[array_rand($frasesFiltradas)];
list($soloFrase, $soloImagen) = explode('@@', $fraseAleatoria . '@@');

echo json_encode([
    "frase" => $soloFrase,
    "imagen" => $soloImagen
]);
?>