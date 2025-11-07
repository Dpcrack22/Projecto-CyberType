<?php
    session_name("jugadorSession");
    session_start();

    if (!isset($_SESSION['playerName']) || !isset($_SESSION['score'])) {
        die("No hay datos de jugador para guardar.");
    }

    $playerName = $_SESSION['playerName'];
    $score = $_SESSION['score'];

    $archivo = "./ranking.txt";

    // Formato del registro
    $registro = "$playerName | $score" . PHP_EOL;

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
      <?php
    // Leer ranking
    $lineas = file($archivo, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    // Ordenar de mayor a menor puntuación
    usort($lineas, function($a, $b) {
        list(, $scoreA) = explode(" | ", $a);
        list(, $scoreB) = explode(" | ", $b);
        return $scoreB - $scoreA;
    });

    // --- PAGINACIÓN ---
    $porPagina = 25;
    $total = count($lineas);
    $paginas = ceil($total / $porPagina);

    $paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
    if ($paginaActual < 1) $paginaActual = 1;
    if ($paginaActual > $paginas) $paginaActual = $paginas;

    $inicio = ($paginaActual - 1) * $porPagina;
    $jugadoresPagina = array_slice($lineas, $inicio, $porPagina);
    ?>

    <table>
        <tr>
            <th>Posición</th>
            <th>Nombre</th>
            <th>Puntuación</th>
        </tr>
        <?php
        $posicion = $inicio + 1;
        foreach ($jugadoresPagina as $linea) {
            list($nombre, $puntuacion) = explode(" | ", $linea);
            $resaltar = ($nombre === $playerName && $puntuacion == $score) ? 'class="resaltar"' : '';
            echo "<tr $resaltar>
                    <td>$posicion</td>
                    <td>$nombre</td>
                    <td>$puntuacion</td>
                  </tr>";
            $posicion++;
        }
        ?>
    </table>

    <!-- PAGINADOR -->
    <div class="paginador">
        <?php if ($paginaActual > 1): ?>
            <a href="?pagina=<?php echo $paginaActual - 1; ?>">&laquo; Anterior</a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $paginas; $i++): ?>
            <a href="?pagina=<?php echo $i; ?>" 
               class="<?php echo ($i == $paginaActual) ? 'activo' : ''; ?>">
               <?php echo $i; ?>
            </a>
        <?php endfor; ?>

        <?php if ($paginaActual < $paginas): ?>
            <a href="?pagina=<?php echo $paginaActual + 1; ?>">Siguiente &raquo;</a>
        <?php endif; ?>
    </div>

    <br>
    <button id="RankingButton"><u>V</u>olver al inicio</button>
    <script src="scriptRanking.js"></script>
</body>
</html>