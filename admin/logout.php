<?php
    session_name("adminSHIELD");
    session_start();

    require_once(__DIR__ . "/log_function.php");

    $usuario = $_SESSION['usuario'] ?? 'Desconocido';
    registrarLog("admin/logout.php", "El administrador '$usuario' cerró sesión.");

    // Solo destruye la sesión del admin
    unset($_SESSION['logado']); 
    session_destroy();

    header("Location: /admin/login.php");
    exit;
?>
