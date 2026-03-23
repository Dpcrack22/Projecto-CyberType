<?php
session_name("jugadorSession");
session_start();

header('Content-Type: application/json; charset=utf-8');

require_once(__DIR__ . "/../admin/log_function.php");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'METHOD_NOT_ALLOWED']);
    exit;
}

$playerName = trim($_POST['playerName'] ?? '');
$difficulty = trim($_POST['difficulty'] ?? 'medio');
$lang = trim($_POST['lang'] ?? ($_SESSION['lang'] ?? 'es'));

$permadeathCheckbox = $_POST['permadeathCheckbox'] ?? '0';
$permadeathCheckbox = ($permadeathCheckbox === '1' || $permadeathCheckbox === 1 || $permadeathCheckbox === true) ? '1' : '0';

if ($playerName === '') {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'BAD_REQUEST', 'message' => 'playerName is required']);
    exit;
}

$_SESSION['lang'] = $lang;
$_SESSION['playerName'] = htmlspecialchars($playerName, ENT_QUOTES, 'UTF-8');
$_SESSION['difficulty'] = $difficulty;
$_SESSION['permadeathCheckbox'] = $permadeathCheckbox;

// Limpiar flags de fin de partida si existían
unset($_SESSION['game_finished'], $_SESSION['score'], $_SESSION['bonus']);

registrarLog("api/start_game.php", "Inicio (React) del jugador '{$_SESSION['playerName']}' con dificultad '$difficulty' (permadeath=$permadeathCheckbox)");

echo json_encode([
    'ok' => true,
    'playerName' => $_SESSION['playerName'],
    'difficulty' => $difficulty,
    'lang' => $lang,
    'permadeathCheckbox' => $permadeathCheckbox,
]);
