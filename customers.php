<?php require_once 'header.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') { $data=[trim($_POST['name']), trim($_POST['phone']), trim($_POST['address'])]; if(!empty($_POST['id'])) $pdo->prepare('UPDATE customers SET name=?, phone=?, address=? WHERE id=?')->execute(array_merge($data, [$_POST['id']])); else $pdo->prepare('INSERT INTO customers (name, phone, address) VALUES (?,?,?)')->execute($data); flash('success','Mteja amehifadhiwa.'); header('Location: customers.php'); exit; }
if(isset($_GET['delete'])){$pdo->prepare('DELETE FROM customers WHERE id=?')->execute([$_GET['delete']]); flash('success','Mteja amefutwa.'); header('Location: customers.php'); exit;}
$edit=null; if(isset($_GET['edit'])){$s=$pdo->prepare('SELECT * FROM customers WHERE id=?');$s->execute([$_GET['edit']]);$edit=$s->fetch();}
$q=trim($_GET['q']??''); $s=$pdo->prepare('SELECT * FROM customers WHERE name LIKE ? OR phone LIKE ? ORDER BY id DESC'); $s->execute(["%$q%","%$q%"]); $rows=$s->fetchAll();
?>
<h1>Customer Management</h1>
<form class="panel grid-form" method="post"><input type="hidden" name="id" value="<?= e($edit['id'] ?? '') ?>"><input name="name" placeholder="Name" value="<?= e($edit['name'] ?? '') ?>" required><input name="phone" placeholder="Phone" value="<?= e($edit['phone'] ?? '') ?>"><input name="address" placeholder="Address" value="<?= e($edit['address'] ?? '') ?>"><button>Save Customer</button></form>
<form class="search" method="get"><input name="q" placeholder="Tafuta mteja" value="<?= e($q) ?>"><button>Search</button></form>
<div class="panel"><table><tr><th>Customer ID</th><th>Name</th><th>Phone</th><th>Address</th><th>Action</th></tr><?php foreach($rows as $r): ?><tr><td><?= e($r['id']) ?></td><td><?= e($r['name']) ?></td><td><?= e($r['phone']) ?></td><td><?= e($r['address']) ?></td><td><a href="?edit=<?= e($r['id']) ?>">Edit</a> <a class="danger" href="?delete=<?= e($r['id']) ?>" onclick="return confirm('Futa mteja?')">Delete</a></td></tr><?php endforeach; ?></table></div>
<?php require_once 'footer.php'; ?>
