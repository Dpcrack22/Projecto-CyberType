<?php
session_name("jugadorSession");
session_start();

require_once(__DIR__ . "/admin/log_function.php");
$arch_act = "play.php";

if (isset($_POST['playerName'])) {
    $_SESSION['playerName'] = htmlspecialchars($_POST['playerName']);
    registrarLog($arch_act, "Inicio de partida del jugador '{$_SESSION['playerName']}' con dificultad '{$_POST['difficulty']}'");
}

$difficulty = $_POST['difficulty'] ?? 'normal';
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

$fraseAleatoria = $frasesFiltradas[array_rand($frasesFiltradas)] ?? 'Error al cargar frase.';
$modoPermadeath = !empty($_POST['permadeathCheckbox']); // true si está marcado
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
    <header>
        <img src="./IMG/Marvel_Logo.png" alt="Marvel Logo" class="marvel-logo">
        <div id="vidas">
            <img src="./IMG/escudo.png" class="vida" alt="vida">
            <img src="./IMG/escudo.png" class="vida" alt="vida">
            <img src="./IMG/escudo.png" class="vida" alt="vida">
            <img src="./IMG/escudo.png" class="vida" alt="vida">
            <img src="./IMG/escudo.png" class="vida" alt="vida">
        </div>
        <div id="tiempoTranscurrido"></div>
        <div class="user-info">
            <?php
            if (isset($_SESSION['playerName'])) {
                echo '<span class="player-name">Jugador: ' . htmlspecialchars($_SESSION['playerName']) . '</span>';
            }
            ?>
            <a href="destroy_session.php" class="logout-link">Cerrar sesión</a>
        </div>
    </header>
    <h1 id="titulo-play">MarvelType</h1>
    <h1 id="titulo-prepara">¡Prepárate joven vengador!</h1>
    <div id="contador">3</div>
    <div id="imageContainer" style="display:none;">
        <img id="fraseImg" src="" alt="Imagen asociada" />
    </div>
    <div id="fraseContainer">
        <div id="frase"></div>
    </div>

    <div id="bonusMessage"></div>
    <div id="progressContainer">
        <div id="progressLabel">Frase 1 / ?</div>
        <div id="progressBar">
            <div id="progressFill"></div>
        </div>
    </div>
    <?php if ($modoPermadeath): ?>
        <script src="./scriptPlayPerma.js?<?php echo time(); ?>" defer></script>
    <?php else: ?>
        <script src="./scriptPlay.js?<?php echo time(); ?>" defer></script>
    <?php endif; ?>
    <script>
        const dificultadSeleccionada = "<?php echo $difficulty; ?>";
        fetch('get_sentence.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: "difficulty=" + encodeURIComponent(dificultadSeleccionada)
            })
            .then(response => response.json())
            .then(data => {
                const allFrases = data.map(item => item.frase || "");
                const allImagenes = data.map(item => item.imagen || "");

                let n;
                if (dificultadSeleccionada === 'facil') n = 3;
                else if (dificultadSeleccionada === 'medio') n = 4;
                else if (dificultadSeleccionada === 'dificil') n = 5;
                else n = 3;

                // tomar las primeras n frases o menos si no hay suficientes
                frasesJuego = allFrases.slice(0, n);
                imagenesJuego = allImagenes.slice(0, n);
                totalFrases = frasesJuego.length;
                indiceFraseActual = 0;

                initProgressBar();
                mostrarFrase();
            })
            .catch(error => {
                console.error('Error al obtener la frase:', error);
            });
    </script>

    <noscript>
        <div class="no-js-warning">
            ⚠️ El juego necesita javascript para funcionar. Por favor, habilita Javascript para empezar.
        </div>
    </noscript>
</body>
</html>