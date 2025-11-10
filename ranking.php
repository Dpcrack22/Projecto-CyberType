<?php
    session_name("jugadorSession");
    session_start();

    if (!isset($_SESSION['playerName']) || !isset($_SESSION['score'])) {
        die("No hay datos de jugador para guardar.");
    }

    $playerName = $_SESSION['playerName'];
    $score = $_SESSION['score'];
    $tiempo = $_SESSION['tiempo'];

    $archivo = "./ranking.txt";

    // Formato del registro
    $registro = "$playerName | $score | $tiempo" . PHP_EOL;

    // Abrir el archivo y escribir
    if (file_put_contents($archivo, $registro, FILE_APPEND | LOCK_EX) === false) {
        die("Error al guardar el ranking.");
    }
?>

<?php
    if (isset($_SESSION['game_finished']) || isset($_SESSION['score']) || isset($_SESSION['bonus'])) {
        unset($_SESSION['game_finished']);
        unset($_SESSION['score']);
        unset($_SESSION['bonus']);
        unset($_SESSION['tiempo']);
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ranking - MarvelType</title>
    <link rel="stylesheet" type="text/css" href="./styles.css?<?php echo time(); ?>" />
</head>
<body class="body-ranking">
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
    
    <h1>Ranking de Jugadores - MarvelType</h1>
    <table>
        <tr>
            <th>Posición</th>
            <th>Nombre</th>
            <th>Puntuación</th>
            <th>Tiempo (s)</th>
        </tr>
        <?php
        $lineas = file($archivo, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        usort($lineas, function($a, $b) {
            list(, $scoreA,) = explode(" | ", $a);
            list(, $scoreB,) = explode(" | ", $b);
            return $scoreB - $scoreA;
        });
        $posicion = 1;
        foreach ($lineas as $linea) {
            list($nombre, $puntuacion) = explode(" | ", $linea);
            $resaltar = ($nombre === $playerName && $puntuacion == $score) ? 'class="resaltar"' : '';
            echo "<tr $resaltar>
                    <td>$posicion</td>
                    <td>$nombre</td>
                    <td>$puntuacion</td>
                    <td>$tiempo s</td>
                  </tr>";
            $posicion++;
        }
        ?>
    </table>
    <br>
    <button id="RankingButton"><u>V</u>olver al inicio</button>
    <script src="scriptRanking.js"></script>
</body>
</html>