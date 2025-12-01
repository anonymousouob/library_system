<?php
require 'db.php';
if(!isset($_SESSION['role']) || $_SESSION['role']!='Master') die('No access');
$id=intval($_GET['id']);
$row=$pdo->query("SELECT * FROM copy WHERE CopyID=$id")->fetch();
if($_SERVER['REQUEST_METHOD']==='POST'){
  $pdo->prepare("UPDATE copy SET ShelfLocation=?,Status=? WHERE CopyID=?")->execute([$_POST['shelf'],$_POST['status'],$id]);
  header("Location: manage_copies.php"); exit;
}
?>
<!DOCTYPE html><html><body class="container mt-4">
<h3>修改副本</h3>
<form method="post">
<input name="shelf" class="form-control mb-2" value="<?= htmlspecialchars($row['ShelfLocation']) ?>">
<select name="status" class="form-control mb-2">
  <option <?= $row['Status']=='Available'?'selected':'' ?>>Available</option>
  <option <?= $row['Status']=='On Loan'?'selected':'' ?>>On Loan</option>
  <option <?= $row['Status']=='Lost'?'selected':'' ?>>Lost</option>
  <option <?= $row['Status']=='Damaged'?'selected':'' ?>>Damaged</option>
</select>
<button class="btn btn-primary">更新</button>
</form>
</body></html>
