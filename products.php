<?php require_once 'header.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [trim($_POST['name']), $_POST['category_id'] ?: null, $_POST['buying_price'], $_POST['selling_price'], $_POST['quantity']];
    if (!empty($_POST['id'])) $pdo->prepare('UPDATE products SET name=?, category_id=?, buying_price=?, selling_price=?, quantity=? WHERE id=?')->execute(array_merge($data, [$_POST['id']]));
    else $pdo->prepare('INSERT INTO products (name, category_id, buying_price, selling_price, quantity) VALUES (?,?,?,?,?)')->execute($data);
    flash('success','Bidhaa imehifadhiwa.'); header('Location: products.php'); exit;
}
if (isset($_GET['delete'])) { $pdo->prepare('DELETE FROM products WHERE id=?')->execute([$_GET['delete']]); flash('success','Bidhaa imefutwa.'); header('Location: products.php'); exit; }
$edit=null; if(isset($_GET['edit'])){$s=$pdo->prepare('SELECT * FROM products WHERE id=?');$s->execute([$_GET['edit']]);$edit=$s->fetch();}
$categories=$pdo->query('SELECT * FROM categories ORDER BY name')->fetchAll();
$q=trim($_GET['q'] ?? '');
$stmt=$pdo->prepare('SELECT p.*, c.name category FROM products p LEFT JOIN categories c ON c.id=p.category_id WHERE p.name LIKE ? OR c.name LIKE ? ORDER BY p.id DESC');
$stmt->execute(["%$q%","%$q%"]); $rows=$stmt->fetchAll();
?>
<h1>Product Management</h1>
<form class="panel grid-form" method="post"><input type="hidden" name="id" value="<?= e($edit['id'] ?? '') ?>"><input name="name" placeholder="Product Name" value="<?= e($edit['name'] ?? '') ?>" required><select name="category_id"><option value="">Category</option><?php foreach($categories as $c): ?><option value="<?= e($c['id']) ?>" <?= (($edit['category_id'] ?? '')==$c['id'])?'selected':'' ?>><?= e($c['name']) ?></option><?php endforeach; ?></select><input type="number" step="0.01" name="buying_price" placeholder="Buying Price" value="<?= e($edit['buying_price'] ?? '') ?>" required><input type="number" step="0.01" name="selling_price" placeholder="Selling Price" value="<?= e($edit['selling_price'] ?? '') ?>" required><input type="number" name="quantity" placeholder="Quantity" value="<?= e($edit['quantity'] ?? '') ?>" required><button>Save Product</button></form>
<form class="search" method="get"><input name="q" placeholder="Tafuta bidhaa" value="<?= e($q) ?>"><button>Search</button></form>
<div class="panel"><table><tr><th>Product ID</th><th>Name</th><th>Category</th><th>Buying</th><th>Selling</th><th>Quantity</th><th>Date Added</th><th>Action</th></tr><?php foreach($rows as $r): ?><tr><td><?= e($r['id']) ?></td><td><?= e($r['name']) ?></td><td><?= e($r['category']) ?></td><td><?= money($r['buying_price']) ?></td><td><?= money($r['selling_price']) ?></td><td><?= e($r['quantity']) ?></td><td><?= e($r['date_added']) ?></td><td><a href="?edit=<?= e($r['id']) ?>">Edit</a> <a class="danger" href="?delete=<?= e($r['id']) ?>" onclick="return confirm('Futa bidhaa?')">Delete</a></td></tr><?php endforeach; ?></table></div>
<?php require_once 'footer.php'; ?>
