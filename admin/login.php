<?php
session_name("adminSHIELD");
session_start();

include __DIR__ . '/../lang/lang.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['lang'])) {
    $_SESSION['lang_admin'] = $_POST['lang'];
    header("Location: " . strtok($_SERVER["REQUEST_URI"], '?')); // limpia la URL
    exit;
}

$lang = $_SESSION['lang_admin'] ?? 'es';
$t = loadLanguage($lang);

$credentials = file(__DIR__ . '/credentials.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$usuario_correcto = $credentials[0] ?? '';
$password_correcta = $credentials[1] ?? '';

if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['usuario'])) {
    $usuario = $_POST['usuario'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($usuario === $usuario_correcto && $password === $password_correcta) {
        $_SESSION['usuario'] = $usuario;
        $_SESSION['logado'] = true; 
        header("Location: /admin/index.php");
        exit;
    } else {
        $error = $t['errorLogin'] ?? 'Usuario o contraseña incorrectos.';
    }
}
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $t['tituloLogin'] ?></title>
    <link rel="stylesheet" type="text/css" href="/styles.css?<?php echo time(); ?>" />
</head>
<body class="body-loginAdmin">
    <header>
        <img src="../IMG/shield.png" alt="S.H.I.E.L.D. Logo" class="marvel-logo">

        <form method="post" id="langForm" class="lang-selector">
            <select name="lang" id="langSelect" onchange="document.getElementById('langForm').submit()">
                <option value="es" <?= $lang === 'es' ? 'selected' : '' ?>>Español</option>
                <option value="ca" <?= $lang === 'ca' ? 'selected' : '' ?>>Català</option>
                <option value="en" <?= $lang === 'en' ? 'selected' : '' ?>>English</option>
            </select>
        </form>
    </header>

    <div class="div-margin"></div>

    <h2><?= $t['inicioSesionLogin'] ?></h2>

    <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>

    <form method="post">
        <input type="text" name="usuario" id="userLogin" required placeholder="<?= $t['placeholderUsuarioLogin'] ?>"><br><br>
        <input type="password" name="password" id="passwordLogin" required placeholder="<?= $t['placeholderContraseñaLogin'] ?>"><br><br>
        <button type="submit" id="loginButton"><?= $t['botonLogin'] ?></button>
    </form>

    <script src="scriptLogin.js"></script>
</body>
</html>
