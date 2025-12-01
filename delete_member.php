<?php
require 'db.php';
if(!isset($_SESSION['role']) || $_SESSION['role']!='Master') die('No access');
$id=intval($_GET['id']);
$pdo->prepare("DELETE FROM member WHERE MemberID=?")->execute([$id]);
header("Location: manage_members.php");
?>