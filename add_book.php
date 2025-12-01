<?php
  require 'db.php';
  if(!isset($_SESSION['role']) || $_SESSION['role']!='Master') die("No access");

  if($_SERVER['REQUEST_METHOD']==='POST'){
    $title = $_POST['title'];
    $author = $_POST['author'];
    $publisher = $_POST['publisher'];
    $year = $_POST['year'];
    $genre = $_POST['genre'];
    $imagePath = null;

    if(isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK){
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        if(in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif'])){
            $new_name = uniqid('book_') . '.' . $ext;
            $dest = 'images/' . $new_name;
            if(move_uploaded_file($_FILES['image']['tmp_name'], $dest)){
                $imagePath = $dest;
            }
        }
    }

    $stmt=$pdo->prepare("INSERT INTO book (Title,Author,Publisher,PublicationYear,Genre,ImagePath) VALUES (?,?,?,?,?,?)");
    $stmt->execute([$title, $author, $publisher, $year, $genre, $imagePath]);
    
    $bid=$pdo->lastInsertId();
    $c=$pdo->prepare("INSERT INTO copy(BookID,ShelfLocation,Status) VALUES (?, 'New-Arr','Available')");
    $c->execute([$bid]);
    header("Location:index.php");
    exit;
  }
?>
<html><body class="container mt-5">
  <h3>新增書籍</h3>
  <form method="post" enctype="multipart/form-data">
  <label>書名</label><input name="title" class="form-control" required>
  <label>作者</label><input name="author" class="form-control" required>
  <label>出版社</label><input name="publisher" class="form-control" required>
  <label>年份</label><input name="year" type="number" class="form-control">
  <label>類型</label><input name="genre" class="form-control" required>
  <label>書本封面圖</label>
  <input type="file" name="image" class="form-control" accept="image/*">
  
  <button class="btn btn-primary mt-3">新增</button>
  </form>
</body></html>