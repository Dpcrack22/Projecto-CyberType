<?php
    session_name("jugadorSession");
    session_start();
    $_SESSION['game_finished'] = true;

    if (isset($_POST['playerName'])) {
        $_SESSION['playerName'] = htmlspecialchars($_POST['playerName']);
    }

    if (isset($_POST['score'])) {
        $_SESSION['score'] = intval($_POST['score']);
    }

    if (isset($_POST['tiempo'])) {
        $_SESSION['tiempo'] = floatval($_POST['tiempo']);
    }
    
    if (isset($_POST['bonus'])) {
        $_SESSION['bonus'] = intval($_POST['bonus']);
    }
    if (isset($_POST['multiplicador'])) {
        $_SESSION['multiplicador'] = intval($_POST['multiplicador']);
    }
    echo "OK";
?>
