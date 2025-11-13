<?php
    session_name("jugadorSession");
    session_start();
    $_SESSION['game_finished'] = true;

    if (isset($_POST['playerName'])) {
        $_SESSION['playerName'] = htmlspecialchars($_POST['playerName']);
    }

    // Store permadeath as a normalized flag ('1' = activated, '0' = deactivated)
    if (isset($_POST['permadeath'])) {
        // Expecting '1' or truthy value from client
        $_SESSION['permadeathCheckbox'] = ($_POST['permadeath'] === '1' || $_POST['permadeath'] === 1 || $_POST['permadeath'] === true) ? '1' : '0';
    } else {
        $_SESSION['permadeathCheckbox'] = '0';
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
