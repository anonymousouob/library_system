<?php
require 'db.php';
if(!isset($_SESSION['role']) || $_SESSION['role']!='Master') { header("Location: index.php"); exit; }

$books = $pdo->query("SELECT * FROM book ORDER BY BookID DESC")->fetchAll();
?>
<!DOCTYPE html><html><head>
<meta charset="utf-8"><title>管理書籍</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="assets/styles.css" rel="stylesheet">
</head><body>
<div class="container container-main mt-4">
  <h3>管理書籍</h3>
  <a href="add_book.php" class="btn btn-success mb-3">新增書籍</a>
  <table class="table">
    <thead><tr><th>ID</th><th>書名</th><th>作者</th><th>出版社</th><th>年份</th><th>操作</th></tr></thead>
    <tbody>
      <?php foreach($books as $b): ?>
        <tr>
          <td><?= $b['BookID'] ?></td>
          <td><?= htmlspecialchars($b['Title']) ?></td>
          <td><?= htmlspecialchars($b['Author']) ?></td>
          <td><?= htmlspecialchars($b['Publisher']) ?></td>
          <td><?= $b['PublicationYear'] ?></td>
          <td class="table-actions">
            <a href="edit_book.php?id=<?= $b['BookID'] ?>" class="btn btn-sm btn-warning">修改</a>
            <a href="delete_book.php?id=<?= $b['BookID'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('確定刪除此書及副本？')">刪除</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <a href="admin_dashboard.php" class="btn btn-secondary">回後台</a>
</div>
</body></html>
