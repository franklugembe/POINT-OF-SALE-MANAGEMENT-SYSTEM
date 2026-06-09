<?php require_once 'header.php';
$userId=$_SESSION['user_id'];
if($_SERVER['REQUEST_METHOD']==='POST'){
    if(isset($_POST['business_name'])){ $pdo->prepare('UPDATE settings SET business_name=?, phone=?, address=?, low_stock_level=? WHERE id=1')->execute([$_POST['business_name'],$_POST['phone'],$_POST['address'],$_POST['low_stock_level']]); flash('success','Settings zimehifadhiwa.'); }
    if(isset($_POST['profile_name'])){ $pdo->prepare('UPDATE users SET full_name=?, phone=? WHERE id=?')->execute([$_POST['profile_name'],$_POST['profile_phone'],$userId]); $_SESSION['full_name']=$_POST['profile_name']; flash('success','Profile imebadilishwa.'); }
    if(!empty($_POST['new_password'])){ if($_POST['new_password']===$_POST['confirm_password']){ $pdo->prepare('UPDATE users SET password=? WHERE id=?')->execute([password_hash($_POST['new_password'],PASSWORD_DEFAULT),$userId]); flash('success','Password imebadilishwa.'); } else flash('error','Password hazifanani.'); }
    header('Location: settings.php'); exit;
}
$set=$pdo->query('SELECT * FROM settings WHERE id=1')->fetch(); $u=$pdo->prepare('SELECT * FROM users WHERE id=?'); $u->execute([$userId]); $me=$u->fetch();
?>
<h1>Settings</h1><div class="report-grid"><form class="panel grid-form" method="post"><h2>Biashara</h2><input name="business_name" placeholder="Jina la biashara" value="<?= e($set['business_name']) ?>"><input name="phone" placeholder="Phone" value="<?= e($set['phone']) ?>"><input name="address" placeholder="Address" value="<?= e($set['address']) ?>"><input type="number" name="low_stock_level" placeholder="Low stock level" value="<?= e($set['low_stock_level']) ?>"><button>Save Settings</button></form><form class="panel grid-form" method="post"><h2>Profile</h2><input name="profile_name" value="<?= e($me['full_name']) ?>" required><input name="profile_phone" value="<?= e($me['phone']) ?>"><button>Save Profile</button></form><form class="panel grid-form" method="post"><h2>Change Password</h2><input type="password" name="new_password" placeholder="Password mpya"><input type="password" name="confirm_password" placeholder="Thibitisha password"><button>Change Password</button></form></div>
<?php require_once 'footer.php'; ?>
