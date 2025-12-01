<?php
session_start();
if(isset($_SESSION['user_id'])){ header("Location: index.php"); exit; }
?>
<!DOCTYPE html>
<html>
<body class="container mt-5">
  <h3>登入</h3>
  <form action="authenticate.php" method="post">
    <label>帳號 (Username)</label>
    <input name="username" class="form-control" required>
    <label>密碼</label>
    <input name="password" type="password" class="form-control" required>
    <button class="btn btn-primary mt-3">登入</button>
  </form>
</body>
</html>