<?php
    session_name("jugadorSession");
    session_start();

    $lang = $_POST['lang'] ?? $_SESSION['lang'] ?? 'es';
    $_SESSION['lang'] = $lang;

    $difficulty = $_POST['difficulty'];

    $archivo = __DIR__ . "/sentences{$lang}.txt";
    if (!file_exists($archivo)) {
        echo json_encode([]);
        exit;
    }

    $lineas = file($archivo, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $frasesFiltradas = [];

    foreach ($lineas as $linea) {
        if (strpos($linea, '|') === false) continue;
        list($nivelFrase, $frases) = explode('|', $linea, 2);
        if (strtolower(trim($nivelFrase)) === strtolower(trim($difficulty))) {
            $frasesFiltradas = array_map('trim', explode(',', $frases));
            shuffle($frasesFiltradas);
            break;
        }
    }

    $numFrases = match(strtolower(trim($difficulty))) {
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
            "frase" => trim($partes[0]),
            "imagen" => isset($partes[1]) ? trim($partes[1]) : ''
        ];
    }

    header('Content-Type: application/json');
    echo json_encode($resultados);
