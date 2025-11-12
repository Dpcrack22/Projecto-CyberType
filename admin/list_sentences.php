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
    
    include __DIR__ . '/../lang/lang.php';
    
    $lang = $_SESSION['lang_admin'] ?? 'es';
    $t = loadLanguage($lang);

    $traducciones_dificultad = [
    'es' => ['facil' => 'Fácil', 'medio' => 'Medio', 'dificil' => 'Difícil'],
    'ca' => ['facil' => 'Fàcil', 'medio' => 'Mitjà', 'dificil' => 'Difícil'],
    'en' => ['facil' => 'Easy', 'medio' => 'Medium', 'dificil' => 'Hard']
];

    $archivo = '../sentences'.$lang.'.txt';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $t['tituloListSentence'] ?></title>
    <link rel="stylesheet" type="text/css" href="/styles.css?<?php echo time(); ?>" />
</head>
<body class="body-listarFrases">
    <header>
        <img src="../IMG/shield.png" alt="SHIELD Logo" class="marvel-logo">
        <div class="user-info">
            <?php
            if (isset($_SESSION['usuario'])) {
                echo '<span class="admin-name">'. $t['administrador'] .': ' . htmlspecialchars($_SESSION['usuario']) . '</span>';
                echo '<a href="logout.php" class="logout-link-admin">'. $t['cerrarSesion'] .'</a>';
            }
            ?>
        </div>
    </header>

    

    <h1><?= $t['h1ListSentence'] ?></h1>

    <div class="mensaje-alerta">
        <?php
        if (isset($_SESSION['mensaje'])) {
            echo htmlspecialchars($_SESSION['mensaje']);
            unset($_SESSION['mensaje']);
            unset($_SESSION['tipo_mensaje']);
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
            <th><?= $t['th1ListSentence'] ?></th>
            <th><?= $t['th2ListSentence'] ?></th>
            <th><?= $t['th3ListSentence'] ?></th>
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
                            <button type="submit"><?= $t['botonEliminar'] ?></button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="3"><?= $t['noFrases'] ?></td></tr>
        <?php endif; ?>
    </table>

    <!-- PAGINADOR -->
    <div class="paginador">
        <?php if ($paginaActual > 1): ?>
            <a href="?pagina=<?php echo $paginaActual - 1; ?>&lang=<?= $lang ?>">&laquo; <?= $t['anterior'] ?></a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $paginas; $i++): ?>
            <a href="?pagina=<?php echo $i; ?>&lang=<?= $lang ?>" 
               class="<?php echo ($i == $paginaActual) ? 'activo' : ''; ?>">
               <?php echo $i; ?>
            </a>
        <?php endfor; ?>

        <?php if ($paginaActual < $paginas): ?>
            <a href="?pagina=<?php echo $paginaActual + 1; ?>&lang=<?= $lang ?>"><?= $t['siguiente'] ?> &raquo;</a>
        <?php endif; ?>
    </div>

    <br>
    <button id="ButtonListSentences"><a href="/admin/index.php"><?= $t['botonVolver'] ?></a></button>
    <script src="scriptListSentences.js"></script>
</body>
</html>
