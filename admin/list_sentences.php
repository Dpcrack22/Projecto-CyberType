<?php
session_name("adminSHIELD");
session_start();

require_once(__DIR__ . "/log_function.php");

// Si no estás logado, redirige al login
if (empty($_SESSION['logado'])) {
    registrarLog("admin/list_sentences.php", "Intento de acceso sin sesión activa. Redirigido al login.");
    header("Location: /admin/login.php");
    exit;
}
$usuario = $_SESSION['usuario'] ?? 'Desconocido';
$archivo = '../sentences.txt';

$paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
registrarLog("admin/list_sentences.php", "El administrador '$usuario' accedió al listado de frases (página $paginaActual).");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin listar frases</title>
    <link rel="stylesheet" type="text/css" href="/styles.css?<?php echo time(); ?>" />
</head>
<body class="body-listarFrases">
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

    <h1>Listado de frases</h1>
    <div class="mensaje-alerta">
        <?php
        if (isset($_SESSION['mensaje'])) {
            echo nl2br(htmlspecialchars($_SESSION['mensaje']));
            unset($_SESSION['mensaje']);
        }
        ?>
    </div>

    <?php
    $todasFrases = [];

    if (file_exists($archivo)) {
        $lineas = file($archivo, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lineas as $linea) {
            list($dificultad, $fraseStr) = explode('|', $linea);
            $frases = explode(',', $fraseStr);

            foreach ($frases as $f) {
                $todasFrases[] = [
                    'dificultad' => trim($dificultad),
                    'frase' => trim($f)
                ];
            }
        }
    }

    // --- PAGINACIÓN ---
    $porPagina = 25;
    $total = count($todasFrases);
    $paginas = max(1, ceil($total / $porPagina));

    $paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
    if ($paginaActual < 1) $paginaActual = 1;
    if ($paginaActual > $paginas) $paginaActual = $paginas;

    $inicio = ($paginaActual - 1) * $porPagina;
    $frasesPagina = array_slice($todasFrases, $inicio, $porPagina);
    ?>

    <table>
        <tr>
            <th>Dificultad</th>
            <th>Frase</th>
            <th>Eliminación</th>
        </tr>
        <?php if ($total > 0): ?>
            <?php foreach ($frasesPagina as $dato): ?>
                <tr>
                    <td><?php echo htmlspecialchars($dato['dificultad']); ?></td>
                    <td>
                        <?php 
                            $mostrarFrase = explode('@@', $dato['frase'])[0]; 
                            echo htmlspecialchars($mostrarFrase);
                        ?>
                    </td>
                    <td id="delete-link">
                        <form action="delete_sentence.php" method="POST">
                            <input type="hidden" name="dificultad" value="<?php echo htmlspecialchars($dato['dificultad']); ?>">
                            <input type="hidden" name="frase" value="<?php echo htmlspecialchars($dato['frase']); ?>">
                            <button type="submit">Eliminar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="3">No se encontraron frases.</td></tr>
        <?php endif; ?>
    </table>

    <!-- PAGINADOR -->
    <div class="paginador">
        <?php if ($paginaActual > 1): ?>
            <a href="?pagina=<?php echo $paginaActual - 1; ?>">&laquo; Anterior</a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $paginas; $i++): ?>
            <a href="?pagina=<?php echo $i; ?>" 
               class="<?php echo ($i == $paginaActual) ? 'activo' : ''; ?>">
               <?php echo $i; ?>
            </a>
        <?php endfor; ?>

        <?php if ($paginaActual < $paginas): ?>
            <a href="?pagina=<?php echo $paginaActual + 1; ?>">Siguiente &raquo;</a>
        <?php endif; ?>
    </div>

    <br>
    <button id="ButtonListSentences"><a href="/admin/index.php"><u>V</u>olver atrás</a></button>
    <script src="scriptListSentences.js"></script>
</body>
</html>
