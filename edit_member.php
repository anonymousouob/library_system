<?php
require 'db.php';
if(!isset($_SESSION['role']) || $_SESSION['role']!='Master') die('No access');
$id=intval($_GET['id']);
$m=$pdo->query("SELECT * FROM member WHERE MemberID=$id")->fetch();
if($_SERVER['REQUEST_METHOD']==='POST'){
  $pdo->prepare("UPDATE member SET Account=?,MemberType=? WHERE MemberID=?")->execute([$_POST['account'],$_POST['type'],$id]);
  header("Location: manage_members.php"); exit;
}
?>
<!DOCTYPE html><html><body class="container mt-4">
<h3>修改會員</h3>
<form method="post">
<input name="account" class="form-control mb-2" value="<?= htmlspecialchars($m['Account']) ?>">
<select name="type" class="form-control mb-2">
  <option <?= $m['MemberType']=='Reader'?'selected':'' ?>>Reader</option>
  <option <?= $m['MemberType']=='Master'?'selected':'' ?>>Master</option>
</select>
<button class="btn btn-primary">更新</button>
</form>
</body></html>
