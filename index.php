<?php
    session_name("jugadorSession");
    session_start();

    if (isset($_SESSION['game_finished']) || isset($_SESSION['score']) || isset($_SESSION['bonus'])) {
        unset($_SESSION['game_finished']);
        unset($_SESSION['score']);
        unset($_SESSION['bonus']);
    }

    include __DIR__ . '/lang/lang.php';

    $lang = isset($_GET['lang']) ? $_GET['lang'] : ($_SESSION['lang'] ?? 'es');
    $_SESSION['lang'] = $lang;

    $t = loadLanguage($lang);
?>

<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $t['tituloIndex'] ?></title>
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
                echo '<a href="destroy_session.php" class="logout-link">'. $t['cerrarSesion'] .'</a>';
            }
            ?>
        </div>
    </header>
    <div class="div-margin"></div>

    <div class="hero-container">
        <img src="./IMG/IndexImg001.png" alt="Iron Man" class="side-img left-img">

        <section>
            <h1><?= $t['tituloh1Index'] ?></h1>
            <p id="gameDescription"><?= $t['descripcionIndex'] ?></p>
            <div id="nameArea">
                <input type="text" id="inputName" placeholder="<?= $t['placeholderIndex'] ?>" value="<?php echo isset($_SESSION['playerName']) ? htmlspecialchars($_SESSION['playerName']) : ''; ?>"/>
                <p id="infoText"></p>
            </div>
            <select id="selectDifficulty">
                <option value="facil"><?= $t['option1Index'] ?></option>
                <option value="medio"><?= $t['option2Index'] ?></option>
                <option value="dificil"><?= $t['option3Index'] ?></option>
            </select>
            <br>
            <button type="button" id="startGameButton"><?= $t['botonIndex'] ?></button>
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