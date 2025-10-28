<?php
    session_start();
    if (!isset($_SESSION['game_finished']) || $_SESSION['game_finished'] !== true) {
        header("Location: error403.php");
        exit;
    }

    $inputName = $_SESSION['playerName'] ?? 'Jugador desconocido';
    $score = $_SESSION['score'] ?? 0;
    $time = $_SESSION['time'] ?? 0.0;
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
    <h1>💥 ¡Fin del juego, <?= htmlspecialchars($inputName) ?>! 💥</h1>
    <p>Tu puntuación final es: <?= htmlspecialchars($score) ?></p>
    <p>Tiempo empleado: <?= htmlspecialchars($time) ?> segundos</p>
    <p>Bonus conseguidos: <?= htmlspecialchars($bonus) ?></p>
    <div class="botones-gameover">
    <form action="ranking.php" method="post">
        <input type="hidden" name="inputName" value="<?= htmlspecialchars($inputName) ?>">
        <input type="hidden" name="score" value="<?= htmlspecialchars($score) ?>">
        <input type="hidden" name="time" value="<?= htmlspecialchars($time) ?>">
        <input type="hidden" name="bonus" value="<?= htmlspecialchars($bonus) ?>">
        <button class="botonEnviarRank-gameover" type="submit">Almacenar Ranking</button>
    </form>

    <button class="botonVolverInicio-gameover"><a href="index.php">Jugar de nuevo</a></button>
    </div>
</body>
</html>