<?php require_once 'header.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productIds = $_POST['product_id'] ?? []; $qtys = $_POST['quantity'] ?? []; $customerId = $_POST['customer_id'] ?: null;
    $pdo->beginTransaction();
    try {
        $receipt = 'RCPT-' . date('YmdHis'); $total = 0; $profit = 0; $items = [];
        foreach ($productIds as $i => $pid) {
            if (!$pid || empty($qtys[$i])) continue; $qty = (int)$qtys[$i];
            $s = $pdo->prepare('SELECT * FROM products WHERE id=? FOR UPDATE'); $s->execute([$pid]); $p = $s->fetch();
            if (!$p || $p['quantity'] < $qty) throw new Exception('Stock haitoshi kwa ' . ($p['name'] ?? 'bidhaa'));
            $line = $qty * $p['selling_price']; $lineProfit = $qty * ($p['selling_price'] - $p['buying_price']);
            $total += $line; $profit += $lineProfit; $items[] = [$pid,$qty,$p['selling_price'],$p['buying_price'],$line];
        }
        if (!$items) throw new Exception('Chagua bidhaa angalau moja.');
        $pdo->prepare('INSERT INTO sales (receipt_no, customer_id, user_id, total_amount, profit) VALUES (?,?,?,?,?)')->execute([$receipt,$customerId,$_SESSION['user_id'],$total,$profit]);
        $saleId = $pdo->lastInsertId();
        foreach ($items as $it) { $pdo->prepare('INSERT INTO sale_items (sale_id, product_id, quantity, price, buying_price, total) VALUES (?,?,?,?,?,?)')->execute([$saleId,$it[0],$it[1],$it[2],$it[3],$it[4]]); $pdo->prepare('UPDATE products SET quantity = quantity - ? WHERE id=?')->execute([$it[1],$it[0]]); }
        $pdo->commit(); header('Location: receipt.php?id='.$saleId); exit;
    } catch (Exception $e) { $pdo->rollBack(); $error = $e->getMessage(); }
}
$products=$pdo->query('SELECT * FROM products WHERE quantity > 0 ORDER BY name')->fetchAll(); $customers=$pdo->query('SELECT * FROM customers ORDER BY name')->fetchAll();
?>
<h1>Sales Management</h1><?php if(!empty($error)): ?><div class="alert alert-error"><?= e($error) ?></div><?php endif; ?>
<form class="panel" method="post" id="saleForm"><label>Mteja</label><select name="customer_id"><option value="">Walk-in Customer</option><?php foreach($customers as $c): ?><option value="<?= e($c['id']) ?>"><?= e($c['name']) ?></option><?php endforeach; ?></select><div id="items"><div class="sale-item"><select name="product_id[]" onchange="calcTotal()"><option value="">Chagua bidhaa</option><?php foreach($products as $p): ?><option value="<?= e($p['id']) ?>" data-price="<?= e($p['selling_price']) ?>"><?= e($p['name']) ?> - <?= money($p['selling_price']) ?> (<?= e($p['quantity']) ?>)</option><?php endforeach; ?></select><input type="number" name="quantity[]" min="1" value="1" oninput="calcTotal()"><span class="line-total">0.00</span></div></div><button type="button" onclick="addSaleItem()">Ongeza Bidhaa</button><h2>Jumla: <span id="grandTotal">0.00</span></h2><button type="submit">Hifadhi Mauzo na Risiti</button></form>
<?php require_once 'footer.php'; ?>
