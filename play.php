<?php
    session_start();

    if (isset($_POST['playerName'])) {
        $_SESSION['playerName'] = htmlspecialchars($_POST['playerName']);
    }

    $difficulty = $_POST['difficulty'];

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
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Play - MarvelType</title>
    <link rel="stylesheet" type="text/css" href="./styles.css?<?php echo time(); ?>" />
</head>
<body class="body-play">
    <h1 id="titulo-play">MarvelType</h1>
    <h1 id="titulo-prepara">Preparat!</h1>
    <div id="contador">3</div>
    <div id="fraseContainer">
        <p><strong>Escriu la següent frase:</strong></p>
        <p id="frase"></p>
        <input type="text" id="inputOcult" autofocus autocomplete="off"/>
    </div>
    <div id="bonusMessage"></div>

    <script src="./scriptPlay.js" defer></script>
    <script>
        const fraseJuego = <?php echo json_encode($fraseAleatoria); ?>;
    </script>

    <noscript>
        <div class="no-js-warning">
            ⚠️ El juego necesita javascript para funcionar. Por favor, habilita Javascript para empezar.
        </div>
    </noscript>
</body>
</html>