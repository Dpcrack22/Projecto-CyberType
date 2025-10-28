<?php
    session_start();

    if (!isset($_SESSION['playerName']) || !isset($_SESSION['score']) || !isset($_SESSION['time'])) {
        die("No hay datos de jugador para guardar.");
    }

    $playerName = $_SESSION['playerName'];
    $score = $_SESSION['score'];
    $time = $_SESSION['time'];

    $archivo = "ranking.txt";

    // Formato del registro
    $registro = "$playerName | $score | $time" . PHP_EOL;

    // Abrir el archivo y escribir
    if (file_put_contents($archivo, $registro, FILE_APPEND | LOCK_EX) === false) {
        die("Error al guardar el ranking.");
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
<body>
    <h1>Ranking de Jugadores - CyberType</h1>
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
            list($nombre, $puntuacion, $tiempo) = explode(" | ", $linea);
            $resaltar = ($nombre === $playerName && $puntuacion == $score && $tiempo == $time) ? 'class="resaltar"' : '';
            echo "<tr $resaltar>
                    <td>$posicion</td>
                    <td>$nombre</td>
                    <td>$puntuacion</td>
                    <td>$tiempo</td>
                  </tr>";
            $posicion++;
        }
        ?>
    </table>
    <br>
    <a href="index.php">Volver al inicio</a>
</body>
</html>