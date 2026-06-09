<?php require_once 'config.php'; require_login(); ?>
<!DOCTYPE html>
<html lang="sw">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= e(setting('business_name', 'FRANK POS')) ?></title>
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div class="app">
  <aside class="sidebar">
    <div class="brand"><?= e(setting('business_name', 'FRANK POS')) ?></div>
    <nav>
      <a href="dashboard.php">Dashboard</a><a href="products.php">Products</a><a href="categories.php">Categories</a>
      <a href="customers.php">Customers</a><a href="sales.php">Sales</a><a href="stock.php">Stock</a><a href="reports.php">Reports</a>
      <?php if (($_SESSION['role'] ?? '') === 'Admin'): ?><a href="users.php">Users</a><?php endif; ?>
      <a href="settings.php">Settings</a><a href="logout.php">Logout</a>
    </nav>
  </aside>
  <main class="main">
    <header class="topbar"><div><?= e($_SESSION['full_name'] ?? 'User') ?> <span><?= e($_SESSION['role'] ?? '') ?></span></div></header>
    <?php show_flash(); ?>
