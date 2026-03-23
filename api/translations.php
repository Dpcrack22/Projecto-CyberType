<?php
session_name("jugadorSession");
session_start();

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'METHOD_NOT_ALLOWED']);
    exit;
}

$lang = $_POST['lang'] ?? $_SESSION['lang'] ?? 'es';
$_SESSION['lang'] = $lang;

require_once(__DIR__ . '/../lang/lang.php');

$t = loadLanguage($lang);

echo json_encode($t);
