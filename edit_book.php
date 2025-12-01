<?php
  require 'db.php';
  if(!isset($_SESSION['role']) || $_SESSION['role']!='Master') die("No access");

  $id=$_GET['id'];
  $book=$pdo->query("SELECT * FROM book WHERE BookID=$id")->fetch();

  if(!$book){ die("Book not found"); }

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $title = $_POST['title'];
    $author = $_POST['author'];
    $publisher = $_POST['publisher'];
    $year = $_POST['year'];
    $genre = $_POST['genre'];
    
    $imagePath = $book['ImagePath'];

    if(isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK){
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        if(in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif'])){
            $new_name = uniqid('book_') . '.' . $ext;
            $dest = 'images/' . $new_name;
            
            if(move_uploaded_file($_FILES['image']['tmp_name'], $dest)){
                if(!empty($imagePath) && file_exists($imagePath)){
                    unlink($imagePath); 
                }
                $imagePath = $dest;
            }
        }
    }

    $stmt = $pdo->prepare("UPDATE book SET Title=?, Author=?, Publisher=?, PublicationYear=?, Genre=?, ImagePath=? WHERE BookID=?");
    $stmt->execute([$title, $author, $publisher, $year, $genre, $imagePath, $id]);
    
    header("Location: index.php");
    exit;
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
  <div class="mb-3">
      <label class="form-label">更換圖片</label>
      <input type="file" name="image" class="form-control" accept="image/*">
  </div>
  <button class="btn btn-warning mt-3">更新</button>
  </form>
</body></html>
