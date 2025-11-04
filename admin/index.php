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
        <button class="botonLogout-admin" id="cerrarSesionButton">Cerrar sesión</button>
        <button class="botonListar-admin" id="listarFrasesButton">Listar Frases</button>
        <button class="botonAgregar-admin" id="agregarFraseButton">Agregar Frase</button>
    </div>
    <script src="script.js"></script>
</body>
</html>