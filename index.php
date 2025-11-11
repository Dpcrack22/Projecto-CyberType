<?php
    session_name("jugadorSession");
    session_start();

    require_once(__DIR__ . "/admin/log_function.php");
    $arch_act="index.php";

    if (isset($_SESSION['playerName'])) {
        registrarLog($arch_act,"El jugador '{$_SESSION['playerName']}' accedió al menú principal.");
    }else{
        registrarLog($arch_act,"El jugador 'Unknown' accedió al menú principal.");
    }
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['lang'])) {
        $_SESSION['lang'] = $_POST['lang'];
        header("Location: " . strtok($_SERVER["REQUEST_URI"], '?')); 
        exit;
    }

    if (isset($_SESSION['game_finished']) || isset($_SESSION['score']) || isset($_SESSION['bonus'])) {
        unset($_SESSION['game_finished'], $_SESSION['score'], $_SESSION['bonus']);
    }

    include __DIR__ . '/lang/lang.php';

    $lang = $_SESSION['lang'] ?? 'es';
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
                echo '<span class="player-name">'. $t['jugador'] .': ' . htmlspecialchars($_SESSION['playerName']) . '</span>';
                echo '<a href="destroy_session.php" class="logout-link">'. $t['cerrarSesion'] .'</a>';
            }
            ?>
        </div>

        <form method="post" id="langForm" class="lang-selector">
            <select name="lang" id="langSelect" onchange="document.getElementById('langForm').submit()">
                <option value="es" <?= $lang === 'es' ? 'selected' : '' ?>><?= $t['idioma1Index']  ?></option>
                <option value="ca" <?= $lang === 'ca' ? 'selected' : '' ?>><?= $t['idioma2Index']  ?></option>
                <option value="en" <?= $lang === 'en' ? 'selected' : '' ?>><?= $t['idioma3Index']  ?></option>
            </select>
        </form>

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
                    <?= $t['noJSPlay'] ?>
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