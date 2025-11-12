<?php
    session_name("jugadorSession");
    session_start();

    require_once(__DIR__ . "/admin/log_function.php");
    $arch_act = "ranking.php";
    $archivo = __DIR__ . "/ranking.txt";

    // Cargar idioma
    include __DIR__ . '/lang/lang.php';
    $lang = isset($_GET['lang']) ? $_GET['lang'] : ($_SESSION['lang'] ?? 'es');
    $_SESSION['lang'] = $lang;
    $t = loadLanguage($lang);
    
    // Almacenar Datos
    $playerName = $_POST['inputName'] ?? ($_SESSION['playerName'] ?? 'Invitado');
    $score = $_POST['score'] ?? ($_SESSION['score'] ?? 0);
    $time = $_POST['tiempo'] ?? ($_SESSION['tiempo'] ?? 0.0);
    $perma = $_POST['perma'] ?? ($_SESSION['permadeathCheckbox'] ?? 'No Activado');

    // --- GUARDAR SOLO SI EXISTEN DATOS DE PARTIDA ---
    if (!empty($playerName) && isset($score) && isset($time)) {
        $registro = "$playerName | $score | $time | $perma" . PHP_EOL;
        file_put_contents($archivo, $registro, FILE_APPEND | LOCK_EX);
        registrarLog($arch_act,"El jugador '$playerName' guardó su puntuación de $score puntos en el ranking.");

        // Limpiar solo las variables de partida de la sesión, no el nombre del jugador
        unset($_SESSION['score']);
        unset($_SESSION['game_finished']);
        unset($_SESSION['bonus']);
        unset($_SESSION['tiempo']);
        unset($_SESSION['permadeathCheckbox']);
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $t['tituloRanking'] ?></title>
    <link rel="stylesheet" type="text/css" href="./styles.css?<?php echo time(); ?>" />
</head>
<body class="body-ranking">
    <header>
        <img src="./IMG/Marvel_Logo.png" alt="Marvel Logo" class="marvel-logo">
        <div class="user-info">
            <?php if (isset($_SESSION['playerName'])): ?>
                <span class="player-name"><?= $t['jugador'] ?>: <?= htmlspecialchars($_SESSION['playerName']) ?></span>
            <?php endif; ?>
            <a href="destroy_session.php" class="logout-link"><?= $t['cerrarSesion'] ?></a>
        </div>
    </header>
    
    <h1><?= $t['h1Ranking'] ?></h1>
    <table>
        <tr>
            <th><?= $t['th1Ranking'] ?></th>
            <th><?= $t['th2Ranking'] ?></th>
            <th><?= $t['th3Ranking'] ?></th>
            <th>Tiempo (s)</th>
            <th>Permadeath</th>
        </tr>
        <?php
        $lineas = [];
        if (file_exists($archivo)) {
            $lineas = file($archivo, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

            // Ordenar de mayor a menor puntuación
            usort($lineas, function($a, $b) {
                list(, $scoreA) = explode(" | ", $a);
                list(, $scoreB) = explode(" | ", $b);
                return (int)$scoreB - (int)$scoreA; // <-- cast a int
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

            $posicion = $inicio + 1;
            foreach ($jugadoresPagina as $linea) {
                $datos = array_pad(explode(" | ", $linea), 4, '');
                list($nombre, $puntuacion, $tiempo, $permadeath) = $datos;
                $resaltar = ($nombre === $playerName && (int)$puntuacion === (int)$score && (float)$tiempo === (float)$time && $permadeath === $perma) ? 'class="resaltar"' : '';
                echo "<tr $resaltar>
                        <td>$posicion</td>
                        <td>$nombre</td>
                        <td>$puntuacion</td>
                        <td>$tiempo s</td>
                        <td>$permadeath</td>
                    </tr>";
                $posicion++;
            }
        ?>
    </table>
    
   <!-- PAGINADOR -->
    <div class="paginador">
        <?php if ($paginaActual > 1): ?>
            <a href="?pagina=<?= $paginaActual - 1 ?>">&laquo; Anterior</a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $paginas; $i++): ?>
            <a href="?pagina=<?= $i ?>&lang=<?= $lang ?>" class="<?= ($i == $paginaActual) ? 'activo' : '' ?>">
                <?= $i ?>
            </a>
        <?php endfor; ?>

        <?php if ($paginaActual < $paginas): ?>
            <a href="?pagina=<?= $paginaActual + 1 ?>&lang=<?= $lang ?>"><?= $t['siguiente'] ?? 'Siguiente' ?> &raquo;</a>
        <?php endif; ?>
    </div>
    <?php
    } else {
        echo "<p>" . ($t['sinRegistros'] ?? 'No hay registros aún.') . "</p>";
    }
    ?>

    <br>
    <button id="RankingButton"><?= $t['botonRanking'] ?></button>
    <script src="scriptRanking.js"></script>
</body>
</html>