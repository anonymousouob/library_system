<?php
require 'db.php';
if(!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }
if($_SERVER['REQUEST_METHOD']!=='POST'){ header("Location: index.php"); exit; }

$bookid = intval($_POST['bookid']);
$memberid = $_SESSION['user_id'];

// 找到一個可借的 copy
$stmt = $pdo->prepare("SELECT CopyID FROM copy WHERE BookID=? AND Status='Available' LIMIT 1");
$stmt->execute([$bookid]);
$copy = $stmt->fetch();

if(!$copy){
  $_SESSION['flash'] = '沒有可借副本';
  header("Location: index.php");
  exit;
}

// 將 copy 標為 On Loan，新增 loan 紀錄，設定還書期限為 14 天後
$pdo->beginTransaction();
try{
  $pdo->prepare("UPDATE copy SET Status='On Loan' WHERE CopyID=?")->execute([$copy['CopyID']]);

  $due = date('Y-m-d', strtotime('+14 days'));
  $ins = $pdo->prepare("INSERT INTO loan (CopyID, MemberID, DueDate) VALUES (?,?,?)");
  $ins->execute([$copy['CopyID'], $memberid, $due]);

  $pdo->commit();
  $_SESSION['flash'] = '借書成功，請於 '.$due.' 前歸還';
}catch(Exception $e){
  $pdo->rollBack();
  $_SESSION['flash'] = '借書失敗';
}
header("Location: my_loans.php");
exit;
?>