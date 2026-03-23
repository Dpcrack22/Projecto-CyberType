<?php
session_name("jugadorSession");
session_start();

header('Content-Type: application/json; charset=utf-8');

$lang = $_SESSION['lang'] ?? 'es';

echo json_encode([
    'ok' => true,
    'playerName' => $_SESSION['playerName'] ?? '',
    'lang' => $lang,
    'difficulty' => $_SESSION['difficulty'] ?? '',
    'permadeathCheckbox' => $_SESSION['permadeathCheckbox'] ?? '0',
]);
