<?php
session_name("jugadorSession");
session_start();

require_once(__DIR__ . "/log_function.php");

if (isset($_SESSION['playerName']) && isset($_POST['typedText']) && isset($_POST['targetSentence'])) {
    $player = $_SESSION['playerName'];
    $typed = $_POST['typedText'];
    $target = $_POST['targetSentence'];
    $time = $_POST['elapsedTime'] ?? "desconocido";

    registrarLog("Jugador '$player' escribió: \"$typed\" en $time segundos (frase: \"$target\").");
}
?>
