<?php
session_start();
if(isset($_SESSION['user_id'])){ header("Location: index.php"); exit; }
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>登入會員</title>
    <form action="authenticate.php" method="post">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
  </head>
  <body class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <h3>登入會員</h3>

        <form action="" method="post">
          
          <div class="mb-3">
              <label class="form-label">使用者名稱 (Username)</label>
              <input name="username" class="form-control" required placeholder="登入用帳號 (限英數)" value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '' ?>">
          </div>

          <div class="mb-3">
              <label class="form-label">密碼</label>
              <input name="password" type="password" class="form-control" required>
          </div>
          <button class="btn btn-primary mt-3">登入</button>
        </form>
      </div>
    </div>
  </body>
</html>