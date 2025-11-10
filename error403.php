<?php
    session_name("jugadorSession");
    session_start();

    include __DIR__ . '/lang/lang.php';

    $lang = isset($_GET['lang']) ? $_GET['lang'] : ($_SESSION['lang'] ?? 'es');
    $_SESSION['lang'] = $lang;

    $t = loadLanguage($lang);
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $t['tituloError403'] ?></title>
    <link rel="stylesheet" type="text/css" href="/styles.css?<?php echo time(); ?>" />
</head>
<body class="body-403">
    <div>
    <div class="emoji-403">🚫</div>
    <h1><?= $t['h1Error403'] ?></h1>
    <p><?= $t['parrafoError403'] ?></p>
    <button id="Button403"><?= $t['botonError403'] ?></button>
    </div>
    <div class="image-div-403">
    <img src="/IMG/Marvel403.png" alt="Image Nick furry" class="image-403">
    </div>
    <script src="/script403.js"></script>
</body>
</html>
