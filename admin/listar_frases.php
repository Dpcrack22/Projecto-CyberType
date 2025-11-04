<?php
session_start();

// Si no estás logado, redirige al login
if (empty($_SESSION['logado'])) {
    header("Location: /admin/login.php");
    exit;
}

$archivo = '../sentences.txt';

// 🗑️ Si se ha enviado la eliminación de una frase
if (isset($_GET['accion']) && $_GET['accion'] === 'eliminar' && isset($_GET['dificultad']) && isset($_GET['frase'])) {
    $dificultad = $_GET['dificultad'];
    $fraseAEliminar = $_GET['frase'];

    if (file_exists($archivo)) {
        $lineas = file($archivo, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $nuevasLineas = [];

        foreach ($lineas as $linea) {
            list($dif, $frases) = explode('|', $linea);
            $fraseArray = array_map('trim', explode(',', $frases));

            if ($dif === $dificultad) {
                // Eliminar la frase seleccionada
                $fraseArray = array_filter($fraseArray, function($f) use ($fraseAEliminar) {
                    return trim($f) !== trim($fraseAEliminar);
                });

                // Si aún quedan frases, volver a unir
                if (!empty($fraseArray)) {
                    $nuevasLineas[] = $dif . '|' . implode(',', $fraseArray);
                }
            } else {
                $nuevasLineas[] = $linea;
            }
        }

        // Sobrescribir el archivo con las frases restantes
        file_put_contents($archivo, implode(PHP_EOL, $nuevasLineas) . PHP_EOL);
    }

    // Recargar la página sin parámetros GET (para limpiar la URL)
    header("Location: listar_frases.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin listar frases</title>
    <link rel="stylesheet" type="text/css" href="./styles.css?<?php echo time(); ?>" />
</head>
<body class="body-listarFrases">
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
                    echo "<td><a href='?accion=eliminar&dificultad=" . urlencode($dificultad) . "&frase=" . urlencode($fraseIndividual) . "' onclick=\"return confirm('¿Estás seguro de que deseas eliminar esta frase?');\">Eliminar</a></td>";
                    echo "</tr>";
                }
            }
        } else {
            echo "<tr><td colspan='3'>No se encontraron frases.</td></tr>";
        }
        ?>
    </table>
</body>
</html>
