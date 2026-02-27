<?php
    session_name("jugadorSession");
    session_start();

    require_once(__DIR__ . "/admin/log_function.php");
    $paginaOrigen = basename($_SERVER['HTTP_REFERER'] ?? 'desconocido');
    if (isset($_SESSION['playerName'])) {
    $jugador = $_SESSION['playerName'];
    registrarLog($paginaOrigen, "El jugador '$jugador' cerró sesión.");
    } else {
        registrarLog($paginaOrigen, "Un usuario sin sesión activa intentó cerrar sesión.");
    }

    session_unset();
    session_destroy();
    header("Location: /index.php");
    exit;
?>