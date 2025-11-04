<?php
    session_start();
    
    // Si no estás logado, redirige al login
    if (empty($_SESSION['logado'])) {
        header("Location: /admin/login.php");
        exit;
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir Frases - Admin</title>
    <link rel="stylesheet" type="text/css" href="/styles.css?<?php echo time(); ?>" />
</head>
<body class="body-createSentence">
    <h1>Añadir Frases</h1>
    <form action="create_sentence.php" method="POST">
        <section>
            <input type="text" id="inputSentence" name="inputSentence" placeholder="Introduce una frase..."/>
            <select id="Dificulty" name="Dificulty">
                <option value="facil" name="Dificulty">Fácil</option>
                <option value="medio" name="Dificulty">Medio</option>
                <option value="dificil" name="Dificulty">Difícil</option>
            </select>
            <button type="submit" id="createSentence">Agregar Frase</button>
        </section>
    </form>

    <button><a href="/admin/index.php">Volver atras</a></button>

    <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nuevaFrase = trim($_POST['inputSentence'] ?? '');
            $dificultad = $_POST['Dificulty'] ?? '';
            if (!empty($nuevaFrase) && in_array($dificultad, ['facil', 'medio', 'dificil'])) {
                $archivo = __DIR__ . '/../sentences.txt';
                $lineas = file($archivo, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                $fraseAgregada = false;

                foreach ($lineas as &$linea) {
                    list($nivelFrase, $frases) = explode('|', $linea, 2);
                    if ($nivelFrase === $dificultad) {
                        $frasesArray = array_map('trim', explode(',', $frases));
                        if (!in_array($nuevaFrase, $frasesArray)) {
                            $frasesArray[] = $nuevaFrase;
                            $linea = $nivelFrase . '|' . implode(',', $frasesArray);
                            $fraseAgregada = true;
                        }
                        break;
                    }
                }
                unset($linea);

                if ($fraseAgregada) {
                    file_put_contents($archivo, implode(PHP_EOL, $lineas) . PHP_EOL);
                    echo "<p class='success-message'>Frase agregada exitosamente.</p>";
                } else {
                    echo "<p class='error-message'>La frase ya existe en esta dificultad.</p>";
                }
            } else {
                echo "<p class='error-message'>Por favor, introduce una frase válida y selecciona una dificultad.</p>";
            }
            $nuevaFrase = '';
            $dificultad = '';
        }
    ?>
    <script src="scriptCreateSentence.js"></script>
</body>
</html>