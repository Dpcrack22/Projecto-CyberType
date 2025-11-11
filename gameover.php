<?php
    session_name("jugadorSession");
    session_start();

    require_once(__DIR__ . "/admin/log_function.php");
    $arch_act = "gameover.php";
    
    if (isset($_SESSION['game_finished']) && $_SESSION['game_finished'] === true && isset($_SESSION['playerName'])) {
        $nombreJugador = htmlspecialchars($_SESSION['playerName']);
        $puntuacion = $_SESSION['score'] ?? 0;
        $bonus = $_SESSION['bonus'] ?? 0;

        registrarLog($arch_act,"El jugador '$nombreJugador' terminó la partida con $puntuacion puntos y $bonus bonus.");
    }
    
    if (!isset($_SESSION['game_finished']) || !isset($_SESSION['playerName']) || !isset($_SESSION['score']) || !isset($_SESSION['tiempo'])) {
        header("Location: error403.php");
        exit;
    }

    $inputName = $_SESSION['playerName'] ?? 'Jugador desconocido';
    $score = $_SESSION['score'] ?? 0;
    $bonus = $_SESSION['bonus'] ?? 0;
    $tiempo = $_SESSION['tiempo'] ?? 0.0;

    include __DIR__ . '/lang/lang.php';

    $lang = isset($_GET['lang']) ? $_GET['lang'] : ($_SESSION['lang'] ?? 'es');
    $_SESSION['lang'] = $lang;

    $t = loadLanguage($lang);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $t['tituloGameOver'] ?></title>
    <link rel="stylesheet" type="text/css" href="./styles.css?<?php echo time(); ?>" />
</head>
<body class="body-gameover">
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
    <h1>💥 ¡Fin del juego, <?= htmlspecialchars($inputName) ?>! 💥</h1>
    <p>Tu puntuación final es: <?= htmlspecialchars($score) ?></p>
    <p>Bonus conseguidos: <?= htmlspecialchars($bonus) ?></p>
    <p>Tiempo empleado: <?= htmlspecialchars($tiempo) ?> segundos</p>
    <h1><?= $t['h1GameOver'] ?>, <?= htmlspecialchars($inputName) ?>! 💥</h1>
    <p><?= $t['puntuacionGameOver'] ?>: <?= htmlspecialchars($score) ?></p>
    <p><?= $t['bonusGameOver'] ?>: <?= htmlspecialchars($bonus) ?></p>
    <div class="botones-gameover">
    <form action="ranking.php" method="post">
        <input type="hidden" name="inputName" value="<?= htmlspecialchars($inputName) ?>">
        <input type="hidden" name="score" value="<?= htmlspecialchars($score) ?>">
        <input type="hidden" name="bonus" value="<?= htmlspecialchars($bonus) ?>">
        <input type="hidden" name="tiempo" value="<?= htmlspecialchars($tiempo) ?>">
        <button class="botonEnviarRank-gameover" id="almacenarRankingButton" type="submit"><u>A</u>lmacenar Ranking</button>
        <button class="botonEnviarRank-gameover" id="almacenarRankingButton" type="submit"><?= $t['almacenarGameOver'] ?></button>
    </form>
    <button class="botonVolverInicio-gameover" id="jugarDeNuevoButton" ><?= $t['jugarGameOver'] ?></button>
    </div>
    <!--
    <div class="imagenes-gameover">
        <img src="./IMG/IndexImg001.png" alt="imagen de Capitán América" class="foto">
        <img src="./IMG/IndexImg002.png" alt="imagen de Thor" class="foto">
        <img src="./IMG/Marvel404.png" alt="imagen de Iron Man" class="foto">
    </div>
    -->
    <script src="scriptGameover.js"></script>
</body>
</html>