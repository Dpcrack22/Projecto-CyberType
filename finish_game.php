<?php
    session_start();
    $_SESSION['game_finished'] = true;

    if (isset($_POST['playerName'])) {
        $_SESSION['playerName'] = htmlspecialchars($_POST['playerName']);
    }

    if (isset($_POST['score'])) {
        $_SESSION['score'] = intval($_POST['score']);
    }
    if (isset($_POST['time'])) {
        $_SESSION['time'] = floatval($_POST['time']);
    }
    echo "OK";
?>
