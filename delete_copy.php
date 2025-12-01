<?php
require 'db.php';
if(!isset($_SESSION['role']) || $_SESSION['role']!='Master') die('No access');
$id=intval($_GET['id']);
$pdo->prepare("DELETE FROM copy WHERE CopyID=?")->execute([$id]);
header("Location: manage_copies.php");
?>