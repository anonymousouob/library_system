<?php
  require 'db.php';
  if(!isset($_SESSION['role']) || $_SESSION['role']!='Master') die("No access");
  if($_SERVER['REQUEST_METHOD']==='POST'){
    $stmt=$pdo->prepare("INSERT INTO book (Title,Author,Publisher,PublicationYear,Genre) VALUES (?,?,?,?,?)");
    $stmt->execute([$_POST['title'],$_POST['author'],$_POST['publisher'],$_POST['year'],$_POST['genre']]);
    $bid=$pdo->lastInsertId();
    $c=$pdo->prepare("INSERT INTO copy(BookID,ShelfLocation,Status) VALUES (?, 'New-Arr','Available')");
    $c->execute([$bid]);
    header("Location:index.php");
  }
?>
<html><body class="container mt-5">
  <h3>新增書籍</h3>
  <form method="post">
  <label>書名</label><input name="title" class="form-control">
  <label>作者</label><input name="author" class="form-control">
  <label>出版社</label><input name="publisher" class="form-control">
  <label>年份</label><input name="year" class="form-control">
  <label>類型</label><input name="genre" class="form-control">
  <button class="btn btn-primary mt-3">新增</button>
  </form>
</body></html>
