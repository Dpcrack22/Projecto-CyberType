<?php
    session_start();
    if (!isset($_SESSION['game_finished']) || $_SESSION['game_finished'] !== true) {
        header("Location: error403.php");
        exit;
    }

    $inputName = $_SESSION['playerName'] ?? 'Jugador desconocido';
    $score = $_SESSION['score'] ?? 0;
    $time = $_SESSION['time'] ?? 0.0;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GameOver - CyberType</title>
    <link rel="stylesheet" type="text/css" href="./styles.css?<?php echo time(); ?>" />
</head>
<body>
    <h1>¡Fin del juego, <?= htmlspecialchars($inputName) ?>!</h1>
    <p>Tu puntuación final es: <?= htmlspecialchars($score) ?></p>
    <p>Tiempo empleado: <?= htmlspecialchars($time) ?> segundos</p>
    <form action="ranking.php" method="post">
        <input type="hidden" name="inputName" value="<?= htmlspecialchars($inputName) ?>">
        <input type="hidden" name="score" value="<?= htmlspecialchars($score) ?>">
        <input type="hidden" name="time" value="<?= htmlspecialchars($time) ?>">
        <button type="submit">Almacenar Ranking</button>
    </form>

    <a href="index.php">Jugar de nuevo</a>
</body>
</html>