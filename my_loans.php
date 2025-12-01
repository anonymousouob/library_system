<?php
require 'db.php';
if(!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }
$uid = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT l.*, b.Title, c.ShelfLocation FROM loan l JOIN copy c ON l.CopyID=c.CopyID JOIN book b ON c.BookID=b.BookID WHERE l.MemberID=? ORDER BY l.LoanDate DESC");
$stmt->execute([$uid]);
$loans = $stmt->fetchAll();
?>
<!DOCTYPE html><html><head>
<meta charset="utf-8"><title>我的借閱</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="assets/styles.css" rel="stylesheet">
</head><body>
<div class="container container-main mt-4">
  <h3>我的借閱</h3>
  <?php if(isset($_SESSION['flash'])){ echo '<div class="alert alert-info">'.$_SESSION['flash'].'</div>'; unset($_SESSION['flash']); } ?>
  <table class="table">
    <thead><tr><th>書名</th><th>借出日</th><th>應還日</th><th>實際還日</th><th>狀態</th><th>操作</th></tr></thead>
    <tbody>
    <?php foreach($loans as $l): ?>
      <tr>
        <td><?= htmlspecialchars($l['Title']) ?></td>
        <td><?= $l['LoanDate'] ?></td>
        <td><?= $l['DueDate'] ?></td>
        <td><?= $l['ReturnDate'] ?? '-' ?></td>
        <td><?= $l['Status'] ?></td>
        <td>
          <?php if($l['Status']=='On Loan'): ?>
            <a href="return.php?id=<?= $l['LoanID'] ?>" class="btn btn-sm btn-success">還書</a>
          <?php else: ?>
            -
          <?php endif; ?>
        </td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
  <a href="index.php" class="btn btn-secondary">回首頁</a>
</div>
</body></html>
