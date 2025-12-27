<?php
require 'db.php';
if(!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }
if($_SERVER['REQUEST_METHOD']!=='POST'){ header("Location: index.php"); exit; }

$bookid = intval($_POST['bookid']);
$memberid = $_SESSION['user_id'];

try {
    // 呼叫 Stored Procedure
    $stmt = $pdo->prepare("CALL BorrowBook(?, ?, @msg, @success)");
    $stmt->execute([$memberid, $bookid]);
    
    // 取得輸出變數
    $result = $pdo->query("SELECT @msg AS msg, @success AS success")->fetch();
    
    $_SESSION['flash'] = $result['msg'];
    
} catch(Exception $e) {
    $_SESSION['flash'] = '系統錯誤：借書失敗';
}
header("Location: my_loans.php");
exit;
?>