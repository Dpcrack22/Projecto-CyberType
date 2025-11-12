<?php
    session_name("adminSHIELD");
    session_start();
    
    require_once(__DIR__ . "/log_function.php");

    // Si no estás logado, redirige al login
    if (empty($_SESSION['logado'])) {
        header("Location: /admin/login.php");
        exit;
    }

    $usuario = $_SESSION['usuario'] ?? 'Desconocido';
    
    include __DIR__ . '/../lang/lang.php';
    $lang = $_SESSION['lang_admin'] ?? 'es';
    $t = loadLanguage($lang);

    registrarLog("admin/add_image.php", "El administrador '$usuario' accedió a la página de subida de imágenes.");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $t['tituloAdminAddImage'] ?></title>
    <link rel="stylesheet" type="text/css" href="/styles.css?<?php echo time(); ?>" />
</head>
<body class="body-addImage">
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
    <h1><?= $t['h1AdminAddImage'] ?></h1>
    <form action="add_image.php" method="POST" enctype="multipart/form-data">
        <label for="dificultad"><?= $t['labelDificultadAddImage'] ?></label>
        <select id="dificultad" name="dificultad" required>
            <option value="facil"><?= $t['option1AdminCreate'] ?></option>
            <option value="medio"><?= $t['option2AdminCreate'] ?></option>
            <option value="dificil"><?= $t['option3AdminCreate'] ?></option>
        </select>
        <br><br>
        <label for="frase"><?= $t['labelFraseAddImage'] ?></label>
        <?php
            $archivo = '../sentences'.$lang.'.txt';
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
        <label for="newImage"><?= $t['labelImagenAddImage'] ?></label>
        <input type="file" name="sentenceImage" accept="image/*" id="sentenceImage" required/>
        <label for="sentenceImage" class="label-imageUpload"><?= $t['subirImagenAdminIndex'] ?></label>
        <br><br>
        <button type="submit" id="uploadImageButton"><?= $t['botonActualizarAddImage'] ?></button>
    </form>
    <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $fraseSeleccionada = trim($_POST['frase']);
            $dificultad = trim($_POST['dificultad']);
            $imagen = $_FILES['sentenceImage'];

            if ($imagen['error'] === UPLOAD_ERR_OK) {
                $nombreImagen = time() . '_' . basename($imagen['name']);
                $rutaDestino = '../IMG/' . $nombreImagen;

                if (move_uploaded_file($imagen['tmp_name'], $rutaDestino)) {
                    registrarLog("admin/add_image.php", "Imagen '$nombreImagen' subida correctamente al servidor. Tamaño: " . round(filesize($rutaDestino) / 1024, 2) . " KB.");
                    
                    $archivo = '../sentences'.$lang.'.txt';
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
                        echo "<p class='success-message'>" . $t['mensajeExitoAddImage'] . "</p>";
                        registrarLog("admin/add_image.php", "El administrador '$usuario' asoció exitosamente la imagen '$nombreImagen' a la frase '$fraseSeleccionada' (dificultad '$dificultad'). Archivo de frases actualizado correctamente.");
                    } else {
                        echo "<p class='error-message'>" . $t['mensajeErrorFraseAddImage'] . "</p>";
                        registrarLog("admin/add_image.php", "El administrador '$usuario' intentó asociar una imagen a una frase no encontrada: '$fraseSeleccionada'.");
                    }
                } else {
                    echo "<p class='error-message'>" . $t['mensajeErrorMoverAddImage'] . "</p>";
                    registrarLog("admin/add_image.php", "El administrador '$usuario' intentó subir una imagen pero falló al moverla: '$nombreImagen'.");
                }
            } else {
                echo "<p class='error-message'>" . $t['mensajeErrorSubidaAddImage'] . "</p>";
                registrarLog("admin/add_image.php", "El administrador '$usuario' intentó subir una imagen pero la subida falló (código de error: {$imagen['error']}).");
            }
        }
    ?>

    <button id="btn-volverIndex"><a href="/admin/index.php"><?= $t['botonVolver'] ?></a></button>
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