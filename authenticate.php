<?php
session_start();
require 'db.php';
require 'config.php';
require 'security.php';
require 'check_login.php';
require 'log.php';

$ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
$username = $_POST['username'] ?? '';
$pwd = $_POST['password'] ?? '';

if(!validAccount($username) || !validPasswordInput($pwd)){ 
    logLogin($username, $ip, 'fail', '帳號或密碼格式不正確');
    echo "<script>alert('帳號或密碼格式不正確'); window.location.href='login.php';</script>";
    exit;
}

if(isLocked($username)){ 
    logLogin($username, $ip, 'blocked', '帳號已鎖定');
    echo "<script>alert('帳號已被鎖定，請稍後再試'); window.location.href='login.php';</script>";
    exit;
}

// 查詢使用者
$stmt = $pdo->prepare("SELECT * FROM member WHERE Username = :u");
$stmt->execute([':u' => $username]);
$user = $stmt->fetch();

if(!$user){ 
    addFailureAttempt($username, $ip);
    logLogin($username, $ip, 'fail', '帳號不存在');
    echo "<script>alert('帳號或密碼錯誤'); window.location.href='login.php';</script>";
    exit;
}

// 驗證密碼 
if(password_verify($pwd, $user['Password'])){
    // 登入成功
    resetFailures($username);
    
    $_SESSION['user_id'] = $user['MemberID'];
    $_SESSION['username'] = $user['Username'];
    $_SESSION['role'] = $user['MemberType'];
    
    logLogin($username, $ip, 'success', '登入成功');
    header("Location: index.php");
    exit;
} else {
    addFailureAttempt($username, $ip);
    logLogin($username, $ip, 'fail', '密碼錯誤');
    echo "<script>alert('帳號或密碼錯誤'); window.location.href='login.php';</script>";
    exit;
}
?>