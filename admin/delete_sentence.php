<?php
session_start();

// Si no estás logado, redirige al login
if (empty($_SESSION['logado'])) {
    header("Location: /admin/login.php");
    exit;
}

$archivo = '../sentences.txt';
$mensaje = '';

if (isset($_GET['dificultad']) && isset($_GET['frase'])) {
    $dificultad = $_GET['dificultad'];
    $fraseAEliminar = $_GET['frase'];

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
            $mensaje = "La frase:\n\n“" . $fraseAEliminar . "”\n\nha sido eliminada correctamente.";
        } else {
            $mensaje = "No se encontró la frase a eliminar.";
        }
    } else {
        $mensaje = "No se encontró el archivo de frases.";
    }
} else {
    $mensaje = "Parámetros inválidos o incompletos.";
}

echo "<script>
    alert(" . json_encode($mensaje) . ");
    window.location.href = 'listar_frases.php';
</script>";
exit;
?>
