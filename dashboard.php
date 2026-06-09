<?php require_once 'header.php';
$totalProducts = $pdo->query('SELECT COUNT(*) c FROM products')->fetch()['c'];
$todaySales = $pdo->query('SELECT COALESCE(SUM(total_amount),0) t FROM sales WHERE DATE(sale_date)=CURDATE()')->fetch()['t'];
$totalCustomers = $pdo->query('SELECT COUNT(*) c FROM customers')->fetch()['c'];
$totalStock = $pdo->query('SELECT COALESCE(SUM(quantity),0) q FROM products')->fetch()['q'];
$lowStock = $pdo->query('SELECT COUNT(*) c FROM products WHERE quantity <= (SELECT low_stock_level FROM settings WHERE id=1)')->fetch()['c'];
?>
<h1>Dashboard</h1>
<div class="cards"><div class="card"><span>Jumla ya Bidhaa</span><strong><?= e($totalProducts) ?></strong></div><div class="card"><span>Mauzo ya Leo</span><strong><?= money($todaySales) ?></strong></div><div class="card"><span>Wateja</span><strong><?= e($totalCustomers) ?></strong></div><div class="card"><span>Stock Iliyobaki</span><strong><?= e($totalStock) ?></strong></div><div class="card warning"><span>Bidhaa Zinazoisha</span><strong><?= e($lowStock) ?></strong></div></div>
<?php require_once 'footer.php'; ?>
