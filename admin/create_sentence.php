<?php
    session_name("adminSHIELD");
    session_start();

    require_once(__DIR__ . "/log_function.php");
    
    
    // Si no estás logado, redirige al login
    if (empty($_SESSION['logado'])) {
        registrarLog("admin/create_sentence.php", "Intento de acceso no autorizado. Redirigido al login.");
        header("Location: /admin/login.php");
        exit;
    }

    $usuario = $_SESSION['usuario'] ?? 'Desconocido';
    registrarLog("admin/create_sentence.php", "El administrador '$usuario' accedió a la página de creación de frases.");
    include __DIR__ . '/../lang/lang.php';

    $lang = $_SESSION['lang_admin'] ?? 'es';
    $t = loadLanguage($lang);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $t['tituloAdminCreate'] ?></title>
    <link rel="stylesheet" type="text/css" href="/styles.css?<?php echo time(); ?>" />
</head>
<body class="body-createSentence">
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
    <div class="div-margin"></div>

    <h1><?= $t['h1Create'] ?></h1>
    <form action="create_sentence.php" method="POST" enctype="multipart/form-data">
        <section>
            <input type="text" id="inputSentence" name="inputSentence" placeholder="<?= $t['placeholderAdminCreate'] ?>"/>
            <select id="Dificulty" name="Dificulty">
                <option value="facil"><?= $t['option1AdminCreate'] ?></option>
                <option value="medio"><?= $t['option2AdminCreate'] ?></option>
                <option value="dificil"><?= $t['option3AdminCreate'] ?></option>
            </select>

            <input type="file" name="sentenceImage" accept="image/*" id="sentenceImage"/>
            <label for="sentenceImage" class="label-imageUpload"><?= $t['subirImagenAdminIndex'] ?></label>
            <span id="fileName" class="file-name"></span>
            
            <button type="submit" id="createSentence"><?= $t['botonAgregarCreate'] ?></button>
        </section>
    </form>

    <button class="btn-volverIndex"><a href="/admin/index.php"><?= $t['botonVolverCreate'] ?></a></button>

    <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nuevaFrase = trim($_POST['inputSentence'] ?? '');
            $dificultad = $_POST['Dificulty'] ?? '';

            // Nombre de imagen (si se sube)
            $nombreImagen = '';
            if (!empty($_FILES['sentenceImage']['name'])) {
                $nombreImagen = time() . '_' . basename($_FILES['sentenceImage']['name']);
                $rutaImagen = __DIR__ . '/../IMG/' . $nombreImagen;
                move_uploaded_file($_FILES['sentenceImage']['tmp_name'], $rutaImagen);
            }

            // Añadimos el separador @@ para asociar la imagen
            $fraseGuardada = $nuevaFrase . '@@' . $nombreImagen;

            if (!empty($nuevaFrase) && in_array($dificultad, ['facil', 'medio', 'dificil'])) {
                $archivo = __DIR__ . '/../sentences'.$lang.'.txt';
                $lineas = file($archivo, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                $fraseAgregada = false;

                foreach ($lineas as &$linea) {
                    list($nivelFrase, $frases) = explode('|', $linea, 2);
                    if ($nivelFrase === $dificultad) {
                        $frasesArray = array_map('trim', explode(',', $frases));
                        
                        foreach($frasesArray as $f) {
                            if (explode('@@', $f)[0] === $nuevaFrase) {
                                echo "<p class='error-message'>" . $t['parrafo2Create'] . "</p>";
                                registrarLog("admin/create_sentence.php", "El administrador '$usuario' intentó añadir una frase duplicada: '$nuevaFrase' (dificultad '$dificultad').");
                                die();
                            }
                        }

                        // Agregar
                        $frasesArray[] = $fraseGuardada;
                        $linea = $nivelFrase . '|' . implode(',', $frasesArray);
                        $fraseAgregada = true;
                        break;
                    }
                }
                unset($linea);

                if ($fraseAgregada) {
                    file_put_contents($archivo, implode(PHP_EOL, $lineas) . PHP_EOL);
                    // Guardar la frase añadida en sesión para destacarla en list_sentences.php
                    $_SESSION['frase_recien_agregada'] = $nuevaFrase;
                    $_SESSION['dificultad_recien_agregada'] = $dificultad;
                    registrarLog("admin/create_sentence.php", "El administrador '$usuario' añadió la frase '$nuevaFrase' a dificultad '$dificultad'.");
                    // Redirigir a list_sentences.php
                    header("Location: list_sentences.php");
                    exit;
                } else {
                    echo "<p class='error-message'>" . $t['parrafo2Create'] . "</p>";
                    registrarLog("admin/create_sentence.php", "El administrador '$usuario' intentó añadir una frase duplicada: '$nuevaFrase' (dificultad '$dificultad').");
                }
            } else {
                echo "<p class='error-message'>" . $t['parrafo3Create'] . "</p>";
                registrarLog("admin/create_sentence.php", "El administrador '$usuario' intentó añadir una frase vacía o con dificultad no válida.");
            }
        }
    ?>
    <script src="scriptCreateSentence.js"></script>
</body>
</html>
