<?php
require 'db.php';
if(!isset($_SESSION['role']) || $_SESSION['role']!='Master') { header("Location: index.php"); exit; }

$members = $pdo->query("SELECT * FROM member ORDER BY MemberID DESC")->fetchAll();
?>
<!DOCTYPE html><html><head>
<meta charset="utf-8"><title>管理會員</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="assets/styles.css" rel="stylesheet">
</head><body>
<div class="container container-main mt-4">
  <h3>管理會員</h3>
  <table class="table">
    <thead><tr><th>ID</th><th>Username</th><th>Account</th><th>Type</th><th>Registered</th><th>操作</th></tr></thead>
    <tbody>
      <?php foreach($members as $m): ?>
        <tr>
          <td><?= $m['MemberID'] ?></td>
          <td><?= htmlspecialchars($m['Username']) ?></td>
          <td><?= htmlspecialchars($m['Account']) ?></td>
          <td><?= $m['MemberType'] ?></td>
          <td><?= $m['RegistrationDate'] ?></td>
          <td>
            <a href="edit_member.php?id=<?= $m['MemberID'] ?>" class="btn btn-sm btn-warning">修改</a>
            <a href="delete_member.php?id=<?= $m['MemberID'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('刪除?')">刪除</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <a href="admin_dashboard.php" class="btn btn-secondary">回後台</a>
</div>
</body></html>
