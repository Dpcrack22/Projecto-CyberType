<?php
    session_name("adminSHIELD");
    session_start();

    // Si no estás logado, redirige al login
    if (empty($_SESSION['logado'])) {
        header("Location: /admin/login.php");
        exit;
    }

    $archivo = '../sentences.txt';
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

    <h1>Listado de frases</h1>
    <table>
        <tr>
            <th>Dificultad</th>
            <th>Frase</th>
            <th>Eliminación</th>
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
                    // Quiero que la frase y la dificultad se pasen por POST en vez de GET
                    echo "<td id='delete-link'><form action='delete_sentence.php' method='POST'><input type='hidden' name='dificultad' value='" . htmlspecialchars($dificultad) . "'><input type='hidden' name='frase' value='" . htmlspecialchars($fraseIndividual) . "'><input type='submit' value='Eliminar'></form></td>";
                    echo "</tr>";
                }
            }
        } else {
            echo "<tr><td colspan='3'>No se encontraron frases.</td></tr>";
        }
        ?>
    </table>
    <button id="ButtonListSentences"><a href="/admin/index.php"><u>V</u>olver atras</a></button>
    <script src="scriptListSentences.js"></script>
</body>
</html>
