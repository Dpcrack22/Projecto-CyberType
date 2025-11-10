<?php
    session_name("jugadorSession");
    session_start();

    if (isset($_POST['playerName'])) {
        $_SESSION['playerName'] = htmlspecialchars($_POST['playerName']);
    }

    include __DIR__ . '/lang/lang.php';

    $lang = isset($_GET['lang']) ? $_GET['lang'] : ($_SESSION['lang'] ?? 'es');
    $_SESSION['lang'] = $lang;

    $t = loadLanguage($lang);

    $difficulty = $_POST['difficulty'];

    $archivo = './sentences'.$lang.'.txt';

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
    <title><?= $t['tituloPlay'] ?></title>
    <link rel="stylesheet" type="text/css" href="./styles.css?<?php echo time(); ?>" />
</head>
<body class="body-play">
    <header>
        <img src="./IMG/Marvel_Logo.png" alt="Marvel Logo" class="marvel-logo">
        <div class="user-info">
            <?php
            if (isset($_SESSION['playerName'])) {
                echo '<span class="player-name">'. $t['jugador'] .': ' . htmlspecialchars($_SESSION['playerName']) . '</span>';
            }
            ?>
            <a href="destroy_session.php" class="logout-link"><?= $t['cerrarSesion'] ?></a>
        </div>
    </header>
    <h1 id="titulo-play"><?= $t['tituloh1Play'] ?></h1>
    <h1 id="titulo-prepara"><?= $t['cuentaAtrasPlay'] ?></h1>
    <div id="contador">3</div>
    <div id="fraseContainer">
        <div id="frase"></div>
    </div>
    <div id="bonusMessage"></div>

    <script src="./scriptPlay.js?<?php echo time(); ?>" defer></script>
    <script>
        const fraseJuego = <?php echo json_encode($fraseAleatoria, JSON_UNESCAPED_UNICODE); ?>;
    </script>

    <noscript>
        <div class="no-js-warning">
            <?= $t['noJSPlay'] ?>
        </div>
    </noscript>
</body>
</html>