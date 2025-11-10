<?php
    session_name("adminSHIELD");
    session_start();

    require_once(__DIR__ . "/log_function.php");
    registrarLog("admin/login.php", "Alguien accedio al login.php.");


    $credentials = file(__DIR__ . '/credentials.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $usuario_correcto = $credentials[0] ?? '';
    $password_correcta = $credentials[1] ?? '';

    if ($_SERVER['REQUEST_METHOD'] === "POST") {
        $usuario = $_POST['usuario'] ?? '';
        $password = $_POST['password'] ?? '';

        if ($usuario === $usuario_correcto && $password === $password_correcta) {
            $_SESSION['usuario'] = $usuario;
            $_SESSION['logado'] = true; // Guardar Sesión

            registrarLog("admin/login.php", "El administrador '$usuario' inició sesión correctamente.");

            header("Location: /admin/index.php"); // Redeirigr al panel
            exit;
        } else {
            registrarLog("admin/login.php", "Intento de inicio de sesión fallido con usuario '$usuario'.");
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
    <header>
        <img src="../IMG/shield.png" alt="Marvel Logo" class="marvel-logo">
    </header>
    <div class="div-margin"></div>

    <h2>Login</h2>
    <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="post">
        <input type="text" name="usuario" id="userLogin" required placeholder="Usuario..."><br><br>
        <input type="password" name="password" id="passwordLogin" required placeholder="Contraseña..."><br><br>
        <button type="submit" id="loginButton"><u>I</u>niciar</button>
    </form>
    <script src="scriptLogin.js"></script>
</body>
</html>