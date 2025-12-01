<?php
  require 'db.php';
  if(!isset($_SESSION['role']) || $_SESSION['role']!='Master') die("No access");

  $id=$_GET['id'];
  $book=$pdo->query("SELECT * FROM book WHERE BookID=$id")->fetch();

  if($_SERVER['REQUEST_METHOD']==='POST'){
    $stmt=$pdo->prepare("UPDATE book SET Title=?,Author=?,Publisher=?,PublicationYear=?,Genre=? WHERE BookID=?");
    $stmt->execute([$_POST['title'],$_POST['author'],$_POST['publisher'],$_POST['year'],$_POST['genre'],$id]);
    header("Location:index.php");
  }
?>
<html><body>
  <form method="post" class="container mt-5">
  <h3>修改書籍</h3>
  <label>書名</label><input name="title" value="<?= $book['Title'] ?>" class="form-control">
  <label>作者</label><input name="author" value="<?= $book['Author'] ?>" class="form-control">
  <label>出版社</label><input name="publisher" value="<?= $book['Publisher'] ?>" class="form-control">
  <label>年份</label><input name="year" value="<?= $book['PublicationYear'] ?>" class="form-control">
  <label>類型</label><input name="genre" value="<?= $book['Genre'] ?>" class="form-control">
  <button class="btn btn-warning mt-3">更新</button>
  </form>
</body></html>
