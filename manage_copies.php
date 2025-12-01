<?php
require 'db.php';
if(!isset($_SESSION['role']) || $_SESSION['role']!='Master') { header("Location: index.php"); exit; }

$c = $pdo->query("SELECT c.*, b.Title FROM copy c JOIN book b ON c.BookID=b.BookID ORDER BY c.CopyID DESC")->fetchAll();
?>
<!DOCTYPE html><html><head>
<meta charset="utf-8"><title>管理副本</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="assets/styles.css" rel="stylesheet">
</head><body>
<div class="container container-main mt-4">
  <h3>管理副本</h3>
  <a href="add_copy.php" class="btn btn-success mb-3">新增副本</a>
  <table class="table">
    <thead><tr><th>CopyID</th><th>書名</th><th>位置</th><th>狀態</th><th>操作</th></tr></thead>
    <tbody>
      <?php foreach($c as $row): ?>
        <tr>
          <td><?= $row['CopyID'] ?></td>
          <td><?= htmlspecialchars($row['Title']) ?></td>
          <td><?= htmlspecialchars($row['ShelfLocation']) ?></td>
          <td><?= $row['Status'] ?></td>
          <td>
            <a href="edit_copy.php?id=<?= $row['CopyID'] ?>" class="btn btn-sm btn-warning">修改</a>
            <a href="delete_copy.php?id=<?= $row['CopyID'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('刪除?')">刪除</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <a href="admin_dashboard.php" class="btn btn-secondary">回後台</a>
</div>
</body></html>
