<?php
header('Content-Type: text/html; charset=utf-8');
session_name("jugadorSession");
session_start();

require_once(__DIR__ . "/log_function.php");

if (!isset($_SESSION['playerName'])) {
    exit;
}

// Leemos el JSON enviado por fetch
$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (isset($data['key'])) {
    // Aseguramos codificación correcta
    $tecla = $data['key'];
    $tecla = htmlspecialchars($tecla, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');

    $jugador = $_SESSION['playerName'];
    registrarLog("log_keypress.php", "El jugador '$jugador' pulsó la tecla '$tecla'");
}
?>
