<?php
    session_name("adminSHIELD");
    session_start();

    require_once(__DIR__ . "/log_function.php");

    // Si no estás logado, redirige al login
    if (empty($_SESSION['logado'])) {
        registrarLog("admin/delete_sentence.php", "Intento de acceso no autorizado. Redirigido al login.");
        header("Location: /admin/login.php");
        exit;
    }

    // Cargar idioma y traducciones
    include __DIR__ . '/../lang/lang.php';
    $lang = $_SESSION['lang_admin'] ?? 'es';
    $_SESSION['lang_admin'] = $lang;  // Asegurar que el idioma se guarda en sesión
    $t = loadLanguage($lang);

    $archivo = __DIR__ . '/../sentences' . $lang . '.txt';
    $mensaje = '';
    $usuario = $_SESSION['usuario'] ?? 'Desconocido';
    $fraseEncontrada = false;

    if (isset($_POST['dificultad']) && isset($_POST['frase'])) {
        $dificultad = $_POST['dificultad'];
        $fraseAEliminar = $_POST['frase'];

        if (file_exists($archivo)) {
            $lineas = file($archivo, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            $nuevasLineas = [];
            $fraseEncontrada = false;

            foreach ($lineas as $linea) {
                $parts = explode('|', $linea, 2);
                $dif = isset($parts[0]) ? trim($parts[0]) : '';
                $frases = isset($parts[1]) ? $parts[1] : '';

                $fraseArray = array_map('trim', explode(',', $frases));
                $originalCount = count($fraseArray);

                list($soloFrase, $soloImagen) = explode('@@', $fraseAEliminar . '@@');

                if ($dif === $dificultad) {
                    $fraseArray = array_filter($fraseArray, function($f) use ($fraseAEliminar) {
                        return trim($f) !== trim($fraseAEliminar);
                    });

                    if (count($fraseArray) < $originalCount) {
                        $fraseEncontrada = true;
                    }

                    if (!empty($fraseArray)) {
                        $nuevasLineas[] = $dif . '|' . implode(',', $fraseArray);
                    }
                } else {
                    $nuevasLineas[] = $linea;
                }
            }

            if ($fraseEncontrada) {
                file_put_contents($archivo, implode(PHP_EOL, $nuevasLineas) . PHP_EOL, LOCK_EX);
                $mensaje = $t['mensajeEliminacionExito'];
                registrarLog("admin/delete_sentence.php", "El administrador '$usuario' eliminó la frase '$soloFrase' de dificultad '$dificultad'.");
            } else {
                $mensaje = $t['mensajeEliminacionNoEncontrada'];
                registrarLog("admin/delete_sentence.php", "El administrador '$usuario' intentó eliminar una frase inexistente: '$fraseAEliminar' (dificultad '$dificultad').");
            }
        } else {
            $mensaje = $t['mensajeEliminacionArchivoNoEncontrado'];
            registrarLog("admin/delete_sentence.php", "El administrador '$usuario' intentó eliminar una frase pero no existe el archivo de frases.");
        }
    } else {
        $mensaje = $t['mensajeEliminacionParametrosInvalidos'];
        registrarLog("admin/delete_sentence.php", "El administrador '$usuario' envió parámetros inválidos en la eliminación de frases.");
    }
    
    $_SESSION['mensaje'] = $mensaje;
    $_SESSION['tipo_mensaje'] = $fraseEncontrada ? 'exito' : 'error';
    header("Location: list_sentences.php");
    exit;
?>
