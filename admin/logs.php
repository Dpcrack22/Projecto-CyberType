<?php
session_name("adminSHIELD");
session_start();
require_once(__DIR__ . "/log_function.php");

// Si no estás logado, redirige al login
if (empty($_SESSION['logado'])) {
    registrarLog("admin/logs.php", "Intento de acceso sin sesión activa. Redirigido al login.");
    header("Location: /admin/login.php");
    exit;
}



$usuario = $_SESSION['usuario'] ?? 'Desconocido';
$archivoLogs = __DIR__ . '/logs.txt';
$paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
registrarLog("admin/logs.php", "El administrador '$usuario' accedió a los logs (página $paginaActual).");

$todosLogs = [];
if (file_exists($archivoLogs)) {
    $lineas = file($archivoLogs, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $todosLogs = array_reverse($lineas);
}

// --- PAGINACIÓN ---
$porPagina = 25;
$total = count($todosLogs);
$paginas = max(1, ceil($total / $porPagina));

if ($paginaActual < 1) $paginaActual = 1;
if ($paginaActual > $paginas) $paginaActual = $paginas;

$inicio = ($paginaActual - 1) * $porPagina;
$logsPagina = array_slice($todosLogs, $inicio, $porPagina);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Logs</title>
    <link rel="stylesheet" type="text/css" href="/styles.css?<?php echo time(); ?>" />
</head>

<body class="body-logs">
    <header>
        <img src="../IMG/shield.png" alt="SHIELD Logo" class="marvel-logo">
        <div class="user-info">
            <?php
            if (isset($_SESSION['usuario'])) {
                echo '<span class="admin-name">Administrador: ' . htmlspecialchars($_SESSION['usuario']) . '</span>';
                echo '<a href="logout.php" class="logout-link-admin">Cerrar sesión</a>';
            }
            ?>
        </div>
    </header>

    <h1>Logs del sistema</h1>

    <table>
        <tr>
            <th>Fecha</th>
            <th>Hora</th>
            <th>IP</th>
            <th>Archivo</th>
            <th>Mensaje</th>
        </tr>
        <?php if ($total > 0): ?>
            <?php foreach ($logsPagina as $log): ?>
                <?php
                $partes = explode(' | ', $log);
                $fecha = $partes[0] ?? '';
                $hora = $partes[1] ?? '';
                $ip = $partes[2] ?? '';
                $archivo = $partes[3] ?? '';
                $mensaje = $partes[4] ?? '';
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($fecha); ?></td>
                    <td><?php echo htmlspecialchars($hora); ?></td>
                    <td><?php echo htmlspecialchars($ip); ?></td>
                    <td><?php echo htmlspecialchars($archivo); ?></td>
                    <td><?php echo htmlspecialchars($mensaje); ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="5">No hay logs para mostrar.</td>
            </tr>
        <?php endif; ?>
    </table>

    <!-- PAGINADOR -->
    <div class="paginador">
        <?php if ($paginaActual > 1): ?>
            <a href="?pagina=<?php echo $paginaActual - 1; ?>">&laquo; Anterior</a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $paginas; $i++): ?>
            <a href="?pagina=<?php echo $i; ?>" class="<?php echo ($i == $paginaActual) ? 'activo' : ''; ?>">
                <?php echo $i; ?>
            </a>
        <?php endfor; ?>

        <?php if ($paginaActual < $paginas): ?>
            <a href="?pagina=<?php echo $paginaActual + 1; ?>">Siguiente &raquo;</a>
        <?php endif; ?>
    </div>

    <br>
    <button id="ButtonLogs"><a href="/admin/index.php"><u>V</u>olver al panel</a></button>
    <script src="scriptListSentences.js"></script>


</body>

</html>