<?php
    session_name("jugadorSession");
    session_start();

    require_once(__DIR__ . "/admin/log_function.php");

    if (isset($_SESSION['playerName'])) {
        registrarLog("El jugador '{$_SESSION['playerName']}' accedió al menú principal.");
    }

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
    <title>Inicio - MarvelType</title>
    <link rel="stylesheet" type="text/css" href="./styles.css?<?php echo time(); ?>" />
    <script src="script.js" defer></script>
</head>
<body class="body-index">
    <header>
        <img src="./IMG/Marvel_Logo.png" alt="Marvel Logo" class="marvel-logo">
        <div class="user-info">
            <?php
            if (isset($_SESSION['playerName'])) {
                echo '<span class="player-name">Jugador: ' . htmlspecialchars($_SESSION['playerName']) . '</span>';
                echo '<a href="destroy_session.php" class="logout-link">Cerrar sesión</a>';
            }
            ?>
        </div>
    </header>
    <div class="div-margin"></div>

    <div class="hero-container">
        <img src="./IMG/IndexImg001.png" alt="Iron Man" class="side-img left-img">

        <section>
            <h1>MarvelType</h1>
            <p id="gameDescription">En MarvelType pondrás a prueba tu velocidad y reflejos. Teclea cada frase con precisión y demuestra que tienes lo necesario para unirte a los héroes más poderosos del universo Marvel.</p>
            <div id="nameArea">
                <input type="text" id="inputName" placeholder="Inserta tu nombre" value="<?php echo isset($_SESSION['playerName']) ? htmlspecialchars($_SESSION['playerName']) : ''; ?>"/>
                <p id="infoText"></p>
            </div>
            <select id="selectDifficulty">
                <option value="facil">Fácil</option>
                <option value="medio">Medio</option>
                <option value="dificil">Difícil</option>
            </select>
            <br>
            <button type="button" id="startGameButton"><u>I</u>niciar Juego</button>
            <noscript>
                <div class="no-js-warning">
                    ⚠️ El juego necesita javascript para funcionar. Por favor, habilita Javascript para empezar.
                </div>
                <script>
                    document.getElementById('startGameButton').style.display = 'none';
                </script>
            </noscript>
        </section>

        <img src="./IMG/IndexImg002.png" alt="Thanos" class="side-img right-img">
    </div>
</body>
</html>