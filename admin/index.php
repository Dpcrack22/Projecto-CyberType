<?php
    session_name("adminSHIELD");
    session_start();
    
    // Si no estás logado, redirige al login
    if (empty($_SESSION['logado'])) {
        header("Location: /admin/login.php");
        exit;
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" type="text/css" href="/styles.css?<?php echo time(); ?>" />
</head>
<body class="body-adminIndex">
    <header>
        <img src="../IMG/shield.png" alt="Marvel Logo" class="marvel-logo">
        <div class="user-info">
            <?php
            if (isset($_SESSION['usuario'])) {
                echo '<span class="admin-name">Administrador: ' . htmlspecialchars($_SESSION['usuario']) . '</span>';
                echo '<a href="logout.php" class="logout-link-admin">Cerrar sesión</a>';
            }
            ?>
        </div>
    </header>
    <div class="div-margin"></div>
    <h1>Bienvenido a S.H.I.E.L.D</h1>
    <div class="botonesIndex">
        <button class="botonLogout-admin" id="cerrarSesionButton"><a href="/admin/logout.php">Cerrar sesión</a></button>
        <button class="botonListar-admin" id="listarFrasesButton"><a href="/admin/list_sentences.php">Listar Frases</a></button>
        <button class="botonAgregar-admin" id="agregarFraseButton"><a href="/admin/create_sentence.php">Agregar Frase</a></button>
    </div>
    <script src="/admin/script.js?<?php echo time(); ?>"></script>
</body>
</html>