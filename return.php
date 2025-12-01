<?php
require 'db.php';
if(!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }

$id = intval($_GET['id']);
$uid = $_SESSION['user_id'];

// 檢查借閱紀錄是否屬於該使用者且尚未還書
$stmt = $pdo->prepare("SELECT * FROM loan WHERE LoanID=? AND MemberID=? AND Status='On Loan'");
$stmt->execute([$id, $uid]);
$loan = $stmt->fetch();

if(!$loan){ $_SESSION['flash']='無此借閱或已還書'; header("Location: my_loans.php"); exit; }

$pdo->beginTransaction();
try{
  $pdo->prepare("UPDATE loan SET ReturnDate=?, Status='Returned' WHERE LoanID=?")->execute([date('Y-m-d'), $id]);
  $pdo->prepare("UPDATE copy SET Status='Available' WHERE CopyID=?")->execute([$loan['CopyID']]);
  $pdo->commit();
  $_SESSION['flash']='還書完成，謝謝';
}catch(Exception $e){
  $pdo->rollBack();
  $_SESSION['flash']='還書失敗';
}
header("Location: my_loans.php");
exit;
?>