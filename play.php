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
    if (isset($_POST['playerName'])) {
        $_SESSION['playerName'] = htmlspecialchars($_POST['playerName']);
    }
    $difficulty = $_POST['difficulty'];
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

    <script src="./scriptPlay.js?<?php echo time(); ?>" defer></script>
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
            frasesJuego = data.map(item => item.frase);
            imagenesJuego = data.map(item => item.imagen);

            // Establecemos la primera frase e imagen:
            fraseAleatoria = frasesJuego[0];
            imagenJuego = imagenesJuego[0];

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