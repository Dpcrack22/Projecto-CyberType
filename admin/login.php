<?php
    session_start();

    $credentials = file(__DIR__ . '/credentials.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $usuario_correcto = $credentials[0] ?? '';
    $password_correcta = $credentials[1] ?? '';

    if ($_SERVER['REQUEST_METHOD'] === "POST") {
        $usuario = $_POST['usuario'] ?? '';
        $password = $_POST['password'] ?? '';

        if ($usuario === $usuario_correcto && $password === $password_correcta) {
            $_SESSION['logado'] = true; // Guardar Sesión
            header("Location: /admin/index.php"); // Redeirigr al panel
            exit;
        } else {
            $error = "Usuario o contraseña incorrectos.";
        }
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" type="text/css" href="/styles.css?<?php echo time(); ?>" />
</head>
<body class="body-loginAdmin">
    <h2>Login</h2>
    <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="post">
        <input type="text" name="usuario" id="userLogin" required placeholder="Usuario..."><br><br>
        <input type="password" name="password" id="passwordLogin" required placeholder="Contraseña..."><br><br>
        <button type="submit" vale="Entrar" id="loginButton">Iniciar</button>
    </form>
    <script src="scriptLogin.js"></script>
</body>
</html>