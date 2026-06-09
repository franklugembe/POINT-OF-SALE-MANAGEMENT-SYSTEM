<?php require_once 'header.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? ''; $name = trim($_POST['name'] ?? '');
    if ($name) {
        if ($id) $pdo->prepare('UPDATE categories SET name=? WHERE id=?')->execute([$name,$id]);
        else $pdo->prepare('INSERT INTO categories (name) VALUES (?)')->execute([$name]);
        flash('success','Category imehifadhiwa.'); header('Location: categories.php'); exit;
    }
}
if (isset($_GET['delete'])) { $pdo->prepare('DELETE FROM categories WHERE id=?')->execute([$_GET['delete']]); flash('success','Category imefutwa.'); header('Location: categories.php'); exit; }
$edit = null; if (isset($_GET['edit'])) { $s=$pdo->prepare('SELECT * FROM categories WHERE id=?'); $s->execute([$_GET['edit']]); $edit=$s->fetch(); }
$rows = $pdo->query('SELECT * FROM categories ORDER BY id DESC')->fetchAll();
?>
<h1>Category Management</h1>
<form class="panel form-row" method="post"><input type="hidden" name="id" value="<?= e($edit['id'] ?? '') ?>"><input name="name" placeholder="Category name" value="<?= e($edit['name'] ?? '') ?>" required><button>Save Category</button></form>
<div class="panel"><table><tr><th>ID</th><th>Name</th><th>Date</th><th>Action</th></tr><?php foreach($rows as $r): ?><tr><td><?= e($r['id']) ?></td><td><?= e($r['name']) ?></td><td><?= e($r['created_at']) ?></td><td><a href="?edit=<?= e($r['id']) ?>">Edit</a> <a class="danger" href="?delete=<?= e($r['id']) ?>" onclick="return confirm('Futa category?')">Delete</a></td></tr><?php endforeach; ?></table></div>
<?php require_once 'footer.php'; ?>
