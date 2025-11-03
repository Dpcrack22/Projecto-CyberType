<?php
    session_start();

    // Si no estás logado, redirige al login
    if (empty($_SESSION['logado'])) {
        header("Location: /login.php");
        exit;
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <title>Admin Panel</title>
    <link rel="stylesheet" type="text/css" href="./styles.css?<?php echo time(); ?>" />
</head>
<body class="body-adminIndex">
    <h1>Bienvenido a S.H.I.E.L.D</h1>
    <div class="botonesIndex">
        <button class="botonLogout-admin"><a href="logout.php">Cerrar sesión</a></button>
        <button class="botonListar-admin"><a href="listar_frases.php">Listar Frases</a></button>
        <button class="botonAgregar-admin"><a href="agregar_frase.php">Agregar Frase</a></button>
    </div>
</body>
</html>