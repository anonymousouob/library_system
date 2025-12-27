<?php
    require 'db.php';
    if($_SESSION['role']!='Master') die("No access");
    $id=$_GET['id'];
    $pdo->prepare("DELETE FROM book WHERE BookID=?")->execute([$id]);
    header("Location:index.php");
?>