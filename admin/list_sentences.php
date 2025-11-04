<?php
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
                    echo "<td><a href='delete_sentence.php?dificultad=" . urlencode($dificultad) . "&frase=" . urlencode($fraseIndividual) . "'>Eliminar</a></td>";
                    echo "</tr>";
                }
            }
        } else {
            echo "<tr><td colspan='3'>No se encontraron frases.</td></tr>";
        }
        ?>
    </table>
    <button><a href="/admin/index.php">Volver atras</a></button>
</body>
</html>
