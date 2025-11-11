<?php
    session_name("adminSHIELD");
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
    <title>Subir Imagen - Admin</title>
    <link rel="stylesheet" type="text/css" href="/styles.css?<?php echo time(); ?>" />
</head>
<body class="body-addImage">
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
    <h1>Subir Imagen a Frase Existente</h1>
    <form action="add_image.php" method="POST" enctype="multipart/form-data">
        <label for="dificultad">Selecciona la dificultad de la frase:</label>
        <select id="dificultad" name="dificultad" required>
            <option value="facil">Fácil</option>
            <option value="medio">Medio</option>
            <option value="dificil">Difícil</option>
        </select>
        <br><br>
        <label for="frase">Frase:</label>
        <?php
            $archivo = '../sentences.txt';
            $frasesOptions = [];

            if (file_exists($archivo)) {
                $lineas = file($archivo, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                foreach ($lineas as $linea) {
                    list($dificultad, $frase) = explode('|', $linea);
                    if (!isset($frasesOptions[$dificultad])) {
                        $frasesOptions[$dificultad] = [];
                    }
                    $frasesIndividuales = explode(',', $frase);
                    foreach ($frasesIndividuales as $fraseIndividual) {
                        list($soloFrase, ) = explode('@@', $fraseIndividual . '@@');
                        $frasesOptions[$dificultad][] = $soloFrase;
                    }
                }
            }
        ?>
        <select id="frase" name="frase" required>
            <script>
                const frasesPorDificultad = <?php echo json_encode($frasesOptions, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;
            </script>
        </select>
        <br><br>
        <label for="newImage">Selecciona una imagen:</label>
        <input type="file" name="sentenceImage" accept="image/*" id="sentenceImage" required/>
        <label for="sentenceImage" class="label-imageUpload">Subir imagen</label>
        <br><br>
        <button type="submit" id="uploadImageButton">Actualizar Frase</button>
    </form>
    <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $fraseSeleccionada = trim($_POST['frase']);
            $dificultad = trim($_POST['dificultad']); // Ahora viene del hidden input
            $imagen = $_FILES['sentenceImage'];

            if ($imagen['error'] === UPLOAD_ERR_OK) {
                $nombreImagen = basename($imagen['name']);
                $rutaDestino = '../IMG/' . $nombreImagen;

                if (move_uploaded_file($imagen['tmp_name'], $rutaDestino)) {
                    $archivo = '../sentences.txt';
                    $lineas = file($archivo, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                    $encontrado = false;

                    foreach ($lineas as &$linea) {
                        list($dificultadLinea, $fraseLinea) = explode('|', $linea, 2);

                        if (trim($dificultadLinea) === $dificultad) {
                            $frasesIndividuales = explode(',', $fraseLinea);
                            foreach ($frasesIndividuales as &$fraseIndividual) {
                                $fraseIndividual = trim($fraseIndividual);
                                list($soloFrase, $imagenActual) = explode('@@', $fraseIndividual . '@@', 2);

                                if (trim($soloFrase) === $fraseSeleccionada) {
                                    $fraseIndividual = $soloFrase . '@@' . $nombreImagen;
                                    $encontrado = true;
                                    break;
                                }
                            }
                            $linea = $dificultadLinea . '|' . implode(',', $frasesIndividuales);
                            break;
                        }
                    }

                    if ($encontrado) {
                        file_put_contents($archivo, implode(PHP_EOL, $lineas) . PHP_EOL);
                        echo "<p>Imagen subida y asociada correctamente a la frase.</p>";
                    } else {
                        echo "<p>No se encontró la frase seleccionada en el archivo.</p>";
                    }
                } else {
                    echo "<p>Error al mover la imagen subida.</p>";
                }
            } else {
                echo "<p>Error en la subida de la imagen.</p>";
            }
        }
    ?>

    <button id="btn-volverIndex"><a href="/admin/index.php"><u>V</u>olver atras</a></button>
    <script src="scriptUploadImage.js"></script>
    <script>
        const selectDificultad = document.getElementById('dificultad');
        const selectFrase = document.getElementById('frase');

        function actualizarFrases() {
            const dificultadSeleccionada = selectDificultad.value;

            selectFrase.innerHTML = '';

            if (frasesPorDificultad[dificultadSeleccionada]) {
                frasesPorDificultad[dificultadSeleccionada].forEach(frase => {
                    const option = document.createElement('option');
                    option.value = frase;
                    option.textContent = frase;
                    selectFrase.appendChild(option);
                });
            } else {
                const option = document.createElement('option');
                option.value = '';
                option.textContent = 'No hay frases para esta dificultad';
                selectFrase.appendChild(option);
            }
        }

        selectDificultad.addEventListener('change', actualizarFrases);

        actualizarFrases();
    </script>

</body>
</html>