<?php
require 'db.php';
if(!isset($_SESSION['role']) || $_SESSION['role']!='Master') die('No access');

if($_SERVER['REQUEST_METHOD']==='POST'){
  $pdo->prepare("INSERT INTO copy (BookID,ShelfLocation,Status) VALUES (?,?,?)")->execute([$_POST['bookid'],$_POST['shelf'],$_POST['status']]);
  header("Location: manage_copies.php");
  exit;
}
$books=$pdo->query("SELECT BookID,Title FROM book")->fetchAll();
?>
<!DOCTYPE html><html><body class="container mt-4">
<h3>新增副本</h3>
<form method="post">
<select name="bookid" class="form-control mb-2">
<?php foreach($books as $b): ?>
  <option value="<?= $b['BookID'] ?>"><?= htmlspecialchars($b['Title']) ?></option>
<?php endforeach; ?>
</select>
<input name="shelf" placeholder="ShelfLocation" class="form-control mb-2">
<select name="status" class="form-control mb-2">
  <option>Available</option><option>On Loan</option><option>Lost</option><option>Damaged</option>
</select>
<button class="btn btn-primary">新增</button>
</form>
</body></html>
