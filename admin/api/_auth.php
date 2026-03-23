<?php
header('Content-Type: application/json; charset=utf-8');

session_name("adminSHIELD");
session_start();

require_once(__DIR__ . "/../log_function.php");

if (empty($_SESSION['logado'])) {
    registrarLog("admin/api/_auth.php", "Acceso no autorizado a API (sin sesión).");
    http_response_code(401);
    echo json_encode([
        'ok' => false,
        'error' => 'NOT_AUTHENTICATED'
    ]);
    exit;
}
