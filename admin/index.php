<?php
    session_name("adminSHIELD");
    session_start();
    
    // Si no estás logado, redirige al login
    if (empty($_SESSION['logado'])) {
        header("Location: /admin/login.php");
        exit;
    }

    include __DIR__ . '/../lang/lang.php';

    $lang = $_SESSION['lang_admin'] ?? 'es';
    $t = loadLanguage($lang);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $t['tituloAdminIndex'] ?></title>
    <link rel="stylesheet" type="text/css" href="/styles.css?<?php echo time(); ?>" />
</head>
<body class="body-adminIndex">
    <header>
        <img src="../IMG/shield.png" alt="Marvel Logo" class="marvel-logo">
        <div class="user-info">
            <?php
            if (isset($_SESSION['usuario'])) {
                echo '<span class="admin-name">'. $t['administrador'] .': ' . htmlspecialchars($_SESSION['usuario']) . '</span>';
                echo '<a href="logout.php" class="logout-link-admin">'. $t['cerrarSesion'] .'</a>';
            }
            ?>
        </div>
    </header>
    <div class="div-margin"></div>
    <h1><?= $t['h1AdminIndex'] ?></h1>
    <div class="botonesIndex">
        <button class="botonLogout-admin" id="cerrarSesionButton"><a href="/admin/logout.php"><?= $t['cerrarSesionAdminIndex'] ?></a></button>
        <button class="botonListar-admin" id="listarFrasesButton"><a href="/admin/list_sentences.php"><?= $t['listarFraseAdminIndex'] ?></a></button>
        <button class="botonAgregar-admin" id="agregarFraseButton"><a href="/admin/create_sentence.php"><?= $t['agregarFraseAdminIndex'] ?></a></button>
    </div>
    <script src="/admin/script.js?<?php echo time(); ?>"></script>
</body>
</html>