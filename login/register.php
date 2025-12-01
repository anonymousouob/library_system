<?php
  require 'db.php';
  require_once 'security.php'; 

  if($_SERVER['REQUEST_METHOD']==='POST'){
    try{
      $u = $_POST['username'];
      $p = $_POST['password'];
      $acc = $_POST['account'];

      if(!validAccount($u) || !validPasswordInput($p)) {
          die("帳號或密碼格式不符安全規定");
      }

      $stmt=$pdo->prepare("INSERT INTO member (Username, Password, Account, MemberType) VALUES (?, ?, ?, 'Reader')");
      $stmt->execute([$u, $p, $acc]);
      
      header("Location:login.php");
      exit;
    }catch(Exception $e){ 
        $err="註冊失敗 (可能是使用者名稱重複)"; 
    }
  }
?>