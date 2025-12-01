<?php
require_once 'db.php';
require_once 'config.php';

function addFailureAttempt($account, $ip){
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO failed_attempts (account, ip) VALUES (:acc, :ip)");
    $stmt->execute([':acc'=>$account, ':ip'=>$ip]);
}

function resetFailures($account){
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM failed_attempts WHERE account=:acc");
    $stmt->execute([':acc'=>$account]);
}

function isLocked($account){
    global $pdo;
    $window = date('Y-m-d H:i:s', time() - LOCK_WINDOW_SECONDS);
    // 計算過去一段時間內的失敗次數
    $stmt = $pdo->prepare("SELECT COUNT(*) c FROM failed_attempts WHERE account=:acc AND created_at >= :w");
    $stmt->execute([':acc'=>$account, ':w'=>$window]);
    $r = $stmt->fetch();
    return $r['c'] >= LOCK_THRESHOLD;
}
?>