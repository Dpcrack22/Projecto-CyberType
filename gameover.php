<?php
    session_name("jugadorSession");
    session_start();
    
    if (!isset($_SESSION['game_finished']) || $_SESSION['game_finished'] !== true || !isset($_SESSION['playerName'])) {
        header("Location: error403.php");
        exit;
    }

    $inputName = $_SESSION['playerName'] ?? 'Jugador desconocido';
    $score = $_SESSION['score'] ?? 0;
    $bonus = $_SESSION['bonus'] ?? 0;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GameOver - MarvelType</title>
    <link rel="stylesheet" type="text/css" href="./styles.css?<?php echo time(); ?>" />
</head>
<body class="body-gameover">
    <header>
        <img src="./IMG/Marvel_Logo.png" alt="Marvel Logo" class="marvel-logo">
        <div class="user-info">
            <?php
            if (isset($_SESSION['playerName'])) {
                echo '<span class="player-name">Jugador: ' . htmlspecialchars($_SESSION['playerName']) . '</span>';
            }
            ?>
            <a href="destroy_session.php" class="logout-link">Cerrar sesión</a>
        </div>
    </header>
    <h1>💥 ¡Fin del juego, <?= htmlspecialchars($inputName) ?>! 💥</h1>
    <p>Tu puntuación final es: <?= htmlspecialchars($score) ?></p>
    <p>Bonus conseguidos: <?= htmlspecialchars($bonus) ?></p>
    <div class="botones-gameover">
    <form action="ranking.php" method="post">
        <input type="hidden" name="inputName" value="<?= htmlspecialchars($inputName) ?>">
        <input type="hidden" name="score" value="<?= htmlspecialchars($score) ?>">
        <input type="hidden" name="bonus" value="<?= htmlspecialchars($bonus) ?>">
        <button class="botonEnviarRank-gameover" id="almacenarRankingButton" type="submit">Almacenar Ranking</button>
    </form>

    <button class="botonVolverInicio-gameover" id="jugarDeNuevoButton" >Jugar de nuevo</button>
    </div>
    <script src="scriptGameover.js"></script>
</body>
</html>