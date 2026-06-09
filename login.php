<?php
require_once 'config.php';
if (!empty($_SESSION['user_id'])) { header('Location: dashboard.php'); exit; }
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login'] ?? '');
    $password = $_POST['password'] ?? '';
    $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ? OR email = ? LIMIT 1');
    $stmt->execute([$login, $login]);
    $user = $stmt->fetch();
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id']; $_SESSION['full_name'] = $user['full_name']; $_SESSION['role'] = $user['role'];
        header('Location: dashboard.php'); exit;
    }
    $error = 'Login details si sahihi.';
}
?>
<!DOCTYPE html><html lang="sw"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Login</title><link rel="stylesheet" href="assets/style.css"></head><body class="auth-page">
<form class="auth-card" method="post"><h1>Login</h1><?php show_flash(); ?><?php if (!empty($error)): ?><div class="alert alert-error"><?= e($error) ?></div><?php endif; ?><input name="login" placeholder="Username au Email" required><input type="password" name="password" placeholder="Password" required><button type="submit">Ingia</button><p><a href="forgot_password.php">Forgot Password?</a> | <a href="register.php">Create Account</a></p></form>
</body></html>
