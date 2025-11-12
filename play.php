<?php
    session_name("jugadorSession");
    session_start();

    require_once(__DIR__ . "/admin/log_function.php");
    $arch_act = "play.php";

    // Determinar idioma primero
    $lang = $_POST['lang'] ?? ($_GET['lang'] ?? ($_SESSION['lang'] ?? 'es'));
    $_SESSION['lang'] = $lang;

    // Cargar idioma
    include __DIR__ . '/lang/lang.php';
    $t = loadLanguage($lang);

    // Registrar inicio de partida si se envió nombre
    if (isset($_POST['playerName'])) {
        $_SESSION['playerName'] = htmlspecialchars($_POST['playerName']);
        registrarLog($arch_act, "Inicio de partida del jugador '{$_SESSION['playerName']}' con dificultad '{$_POST['difficulty']}'");
    }

    $difficulty = $_POST['difficulty'] ?? 'medio';
    $modoPermadeath = !empty($_POST['permadeathCheckbox']); // true si está marcado
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $t['tituloPlay'] ?></title>
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
            <?php if (isset($_SESSION['playerName'])): ?>
                <span class="player-name"><?= $t['jugador'] ?>: <?= htmlspecialchars($_SESSION['playerName']) ?></span>
            <?php endif; ?>
            <a href="destroy_session.php" class="logout-link"><?= $t['cerrarSesion'] ?></a>
        </div>
    </header>

    <h1 id="titulo-play"><?= $t['tituloh1Play'] ?></h1>
    <h1 id="titulo-prepara"><?= $t['cuentaAtrasPlay'] ?></h1>
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
    <script>const modoPermadeath = <?php echo $modoPermadeath ? 'true' : 'false'; ?>;</script>
    <script>
        const gameTranslations = {
                yaPlay: "<?= $t['yaPlay'] ?>",
                thanosActivarPlay: "<?= $t['thanosActivarPlay'] ?>",
                thanosMensajePlay: "<?= $t['thanosMensajePlay'] ?>"
            };
    </script>
    <?php if ($modoPermadeath): ?>
        <script src="./scriptPlayPerma.js?<?php echo time(); ?>" defer></script>
    <?php else: ?>
        <script src="./scriptPlay.js?<?php echo time(); ?>" defer></script>
    <?php endif; ?>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const dificultadSeleccionada = "<?= $difficulty ?>";
            const langSeleccionado = "<?= $lang ?>";
            
            fetch('get_sentence.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: "difficulty=" + encodeURIComponent(dificultadSeleccionada) + "&lang=" + encodeURIComponent(langSeleccionado)
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
            .catch(error => console.error('Error al obtener la frase:', error));
        });
    </script>
    <noscript>
        <div class="no-js-warning"><?= $t['noJSPlay'] ?></div>
    </noscript>
</body>
</html>
