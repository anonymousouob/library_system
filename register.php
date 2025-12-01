<?php
  require 'db.php';
  require_once 'security.php'; 

  $err = '';

  if($_SERVER['REQUEST_METHOD']==='POST'){
    try{
      $u = $_POST['username'];
      $p = $_POST['password'];
      $rp = $_POST['repassword'];
      $acc = $_POST['account'];

      if($p !== $rp) {
          throw new Exception("兩次密碼輸入不一致");
      }

      if(!validAccount($u) || !validPasswordInput($p)) {
          throw new Exception("帳號或密碼格式不符安全規定 (帳號限英數)");
      }

      $stmt=$pdo->prepare("INSERT INTO member (Username, Password, Account, MemberType) VALUES (?, ?, ?, 'Reader')");
      $stmt->execute([$u, $p, $acc]);
      
      echo "<script>alert('註冊成功，請登入'); window.location.href='login.php';</script>";
      exit;

    } catch(Exception $e){ 
        if(strpos($e->getMessage(), 'Duplicate entry') !== false){
            $err = "註冊失敗：使用者名稱或帳號已被使用";
        } else {
            $err = $e->getMessage();
        }
    }
  }
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>註冊會員</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
  </head>
  <body class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <h3>註冊會員</h3>
        
        <?php if($err): ?>
          <div class="alert alert-danger"><?= htmlspecialchars($err) ?></div>
        <?php endif; ?>

        <form action="" method="post">
          
          <div class="mb-3">
              <label class="form-label">使用者名稱 (Username)</label>
              <input name="username" class="form-control" required placeholder="登入用帳號 (限英數)" value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '' ?>">
          </div>

          <div class="mb-3">
              <label class="form-label">帳號識別碼 (Account ID)</label>
              <input name="account" class="form-control" value="<?= isset($_POST['account']) ? htmlspecialchars($_POST['account']) : '' ?>">
          </div>

          <div class="mb-3">
              <label class="form-label">設定密碼</label>
              <input name="password" type="password" class="form-control" required>
          </div>

          <div class="mb-3">
              <label class="form-label">確認新密碼</label>
              <input name="repassword" type="password" class="form-control" required>
          </div>

          <button class="btn btn-primary mt-3">註冊</button>
          <a href="login.php" class="btn btn-link mt-3">已有帳號？登入</a>
        </form>
      </div>
    </div>
  </body>
</html>