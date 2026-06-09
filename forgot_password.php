<?php
require_once 'config.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?'); $stmt->execute([$email]);
    if ($stmt->fetch()) {
        $token = bin2hex(random_bytes(16));
        $pdo->prepare('UPDATE users SET reset_token = ?, reset_expires = DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE email = ?')->execute([$token, $email]);
        $message = 'Reset link yako: reset_password.php?token=' . $token;
    } else $message = 'Kama email ipo, reset link itatengenezwa.';
}
?>
<!DOCTYPE html><html lang="sw"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Forgot Password</title><link rel="stylesheet" href="assets/style.css"></head><body class="auth-page"><form class="auth-card" method="post"><h1>Forgot Password</h1><?php if (!empty($message)): ?><div class="alert alert-success"><?= e($message) ?></div><?php endif; ?><input type="email" name="email" placeholder="Email yako" required><button>Tengeneza Reset Link</button><p><a href="login.php">Back to login</a></p></form></body></html>
