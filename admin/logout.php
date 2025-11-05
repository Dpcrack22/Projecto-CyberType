<?php
    session_name("adminSHIELD");
    session_start();

    // Solo destruye la sesión del admin
    unset($_SESSION['logado']); 
    session_destroy();

    header("Location: /admin/login.php");
    exit;
?>
