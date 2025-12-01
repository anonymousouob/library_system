<?php
require_once 'db.php';

function logLogin($account, $ip, $result, $message=null){
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO login_logs (account, ip, result, message) VALUES (:acc, :ip, :res, :msg)");
    $stmt->execute([':acc'=>$account, ':ip'=>$ip, ':res'=>$result, ':msg'=>$message]);
}
?>