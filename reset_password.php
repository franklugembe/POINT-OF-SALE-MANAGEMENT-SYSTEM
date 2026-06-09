<?php
require_once 'config.php';
$token = $_GET['token'] ?? $_POST['token'] ?? '';
$stmt = $pdo->prepare('SELECT id FROM users WHERE reset_token = ? AND reset_expires > NOW()'); $stmt->execute([$token]); $user = $stmt->fetch();
if (!$user) die('Reset token si sahihi au ime-expire.');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (($_POST['password'] ?? '') !== ($_POST['confirm_password'] ?? '')) $error = 'Password hazifanani.';
    else { $pdo->prepare('UPDATE users SET password = ?, reset_token = NULL, reset_expires = NULL WHERE id = ?')->execute([password_hash($_POST['password'], PASSWORD_DEFAULT), $user['id']]); flash('success', 'Password imebadilishwa. Ingia sasa.'); header('Location: login.php'); exit; }
}
?>
<!DOCTYPE html><html lang="sw"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Reset Password</title><link rel="stylesheet" href="assets/style.css"></head><body class="auth-page"><form class="auth-card" method="post"><h1>Reset Password</h1><?php if (!empty($error)): ?><div class="alert alert-error"><?= e($error) ?></div><?php endif; ?><input type="hidden" name="token" value="<?= e($token) ?>"><input type="password" name="password" placeholder="Password mpya" required><input type="password" name="confirm_password" placeholder="Thibitisha password" required><button>Badilisha Password</button></form></body></html>
