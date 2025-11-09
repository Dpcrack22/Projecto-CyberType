<?php
    session_name("adminSHIELD");
    session_start();

    // Si no estás logado, redirige al login
    if (empty($_SESSION['logado'])) {
        header("Location: /admin/login.php");
        exit;
    }

    $archivo = '../sentences.txt';

    include __DIR__ . '/lang/lang.php';

    $lang = isset($_GET['lang']) ? $_GET['lang'] : ($_SESSION['lang'] ?? 'es');
    $_SESSION['lang'] = $lang;

    $t = loadLanguage($lang);
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

    <h1><?= $t['h1ListSentence'] ?></h1>
    <table>
        <tr>
            <th><?= $t['th1ListSentence'] ?></th>
            <th><?= $t['th2ListSentence'] ?></th>
            <th><?= $t['th3ListSentence'] ?></th>
        </tr>
        <?php
        if (file_exists($archivo)) {
            $lineas = file($archivo, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lineas as $linea) {
                list($dificultad, $frase) = explode('|', $linea);
                $frasesIndividuales = explode(',', $frase);
                foreach ($frasesIndividuales as $fraseIndividual) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($dificultad) . "</td>";
                    echo "<td>" . htmlspecialchars($fraseIndividual) . "</td>";
                    echo "<td id='delete-link'><a href='delete_sentence.php?dificultad=" . urlencode($dificultad) . "&frase=" . urlencode($fraseIndividual) . "'>". $t['botonEliminar'] ."</a></td>";
                    echo "</tr>";
                }
            }
        } else {
            echo "<tr><td colspan='3'>". $t['noFrases'] ."</td></tr>";
        }
        ?>
    </table>
    <button id="ButtonListSentences"><a href="/admin/index.php"><?= $t['botonVolver'] ?></a></button>
    <script src="scriptListSentences.js"></script>
</body>
</html>
