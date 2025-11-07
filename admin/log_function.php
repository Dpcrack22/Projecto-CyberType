<?php
function registrar_log($archivoOrigen, $accion) {
    // Ruta absoluta al archivo de logs
    $rutaLogs = __DIR__ . "/logs.txt";

    // Info básica
    $fecha = date("Y-m-d");
    $hora = date("H:i:s");
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'IP desconocida';

    // Texto a guardar
    $registro = "$fecha | $hora | $ip | $archivoOrigen | $accion" . PHP_EOL;

    // Escribir en el archivo de logs (modo append)
    file_put_contents($rutaLogs, $registro, FILE_APPEND | LOCK_EX);
}
?>
