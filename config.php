<?php
session_start();
$host = 'localhost';
$dbname = 'frank_pos';
$dbuser = 'root';
$dbpass = '';
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $dbuser, $dbpass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    die('Database connection failed: ' . $e->getMessage());
}
function e($value) { return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8'); }
function money($amount) { return number_format((float)$amount, 2); }
function setting($key, $default = '') {
    global $pdo;
    static $settings = null;
    if ($settings === null) $settings = $pdo->query('SELECT * FROM settings WHERE id = 1')->fetch() ?: [];
    return $settings[$key] ?? $default;
}
function require_login() { if (empty($_SESSION['user_id'])) { header('Location: login.php'); exit; } }
function require_admin() {
    require_login();
    if (($_SESSION['role'] ?? '') !== 'Admin') { http_response_code(403); die('Access denied. Admin only.'); }
}
function flash($type, $message) { $_SESSION['flash'] = ['type' => $type, 'message' => $message]; }
function show_flash() {
    if (!empty($_SESSION['flash'])) {
        $f = $_SESSION['flash'];
        echo '<div class="alert alert-' . e($f['type']) . '">' . e($f['message']) . '</div>';
        unset($_SESSION['flash']);
    }
}
?>
