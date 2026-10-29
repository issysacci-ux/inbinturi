<?php
date_default_timezone_set('Asia/Manila');
session_start();
header('Content-Type: application/json');

$conn = @new mysqli('localhost', 'root', '', 'boostcafe_db');
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => 'Database connection failed. Is XAMPP MySQL running?']);
    exit;
}
$conn->set_charset('utf8mb4');

function current_user()
{
    return $_SESSION['user'] ?? null;
}

function require_login()
{
    if (!isset($_SESSION['user'])) {
        echo json_encode(['ok' => false, 'error' => 'Not logged in', 'auth' => false]);
        exit;
    }
}

function respond($data)
{
    echo json_encode($data);
    exit;
}
