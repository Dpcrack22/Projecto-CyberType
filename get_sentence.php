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
            shuffle($frasesFiltradas);
            break;
        }
    }

    if ($difficulty === 'facil') {
        $numFrases = 3;
    } elseif ($difficulty === 'medio') {
        $numFrases = 4;
    } elseif ($difficulty === 'dificil') {
        $numFrases = 5;
    } else {
        $numFrases = 0;
    }

    $frasesAleatorias = array_slice($frasesFiltradas, 0, $numFrases);
    $resultados = [];
    foreach ($frasesAleatorias as $frase) {
        $partes = explode('@@', $frase);
        $soloFrase = $partes[0];
        $soloImagen = isset($partes[1]) ? trim($partes[1]) : '';

        $resultados[] = [
            "frase" => $soloFrase,
            "imagen" => $soloImagen
        ];
    }

    echo json_encode($resultados);
?>