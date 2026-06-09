<?php
require_once 'config.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';
    if (!$full_name || !$username || !$email || !$password) $error = 'Jaza taarifa zote muhimu.';
    elseif ($password !== $confirm) $error = 'Password hazifanani.';
    else {
        try {
            $stmt = $pdo->prepare('INSERT INTO users (full_name, username, email, phone, password, role) VALUES (?, ?, ?, ?, ?, "Cashier")');
            $stmt->execute([$full_name, $username, $email, $phone, password_hash($password, PASSWORD_DEFAULT)]);
            flash('success', 'Akaunti imesajiliwa. Ingia sasa.'); header('Location: login.php'); exit;
        } catch (PDOException $e) { $error = 'Username au email tayari ipo.'; }
    }
}
?>
<!DOCTYPE html><html lang="sw"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Register</title><link rel="stylesheet" href="assets/style.css"></head><body class="auth-page">
<form class="auth-card" method="post"><h1>Create Account</h1><?php if (!empty($error)): ?><div class="alert alert-error"><?= e($error) ?></div><?php endif; ?><input name="full_name" placeholder="Jina kamili" required><input name="username" placeholder="Username" required><input type="email" name="email" placeholder="Email" required><input name="phone" placeholder="Namba ya simu"><input type="password" name="password" placeholder="Password" required><input type="password" name="confirm_password" placeholder="Thibitisha password" required><button type="submit">Sajili Akaunti</button><p>Tayari una akaunti? <a href="login.php">Login</a></p></form>
</body></html>
