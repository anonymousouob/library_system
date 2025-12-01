<?php
require 'db.php';
if(!isset($_SESSION['role']) || $_SESSION['role']!='Master') { header("Location: index.php"); exit; }

// summary stats
$total_books = $pdo->query("SELECT COUNT(*) FROM book")->fetchColumn();
$total_copies = $pdo->query("SELECT COUNT(*) FROM copy")->fetchColumn();
$total_members = $pdo->query("SELECT COUNT(*) FROM member")->fetchColumn();
$onloan = $pdo->query("SELECT COUNT(*) FROM loan WHERE Status='On Loan'")->fetchColumn();

// fetch recent loans
$recent = $pdo->query("SELECT l.*, m.Username, b.Title FROM loan l JOIN member m ON l.MemberID=m.MemberID JOIN copy c ON l.CopyID=c.CopyID JOIN book b ON c.BookID=b.BookID ORDER BY l.LoanDate DESC LIMIT 10")->fetchAll();

?>
<!DOCTYPE html><html><head>
<meta charset="utf-8"><title>管理後台</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="assets/styles.css" rel="stylesheet">
</head><body>
<div class="container container-main mt-4">
  <h3>管理後台</h3>
  <div class="row">
    <div class="col-md-3"><div class="card p-3"><h5>書籍數</h5><p><?= $total_books ?></p></div></div>
    <div class="col-md-3"><div class="card p-3"><h5>副本數</h5><p><?= $total_copies ?></p></div></div>
    <div class="col-md-3"><div class="card p-3"><h5>會員數</h5><p><?= $total_members ?></p></div></div>
    <div class="col-md-3"><div class="card p-3"><h5>目前借出</h5><p><?= $onloan ?></p></div></div>
  </div>

  <div class="mt-4">
    <h5>最近借閱</h5>
    <table class="table">
      <thead><tr><th>借閱ID</th><th>使用者</th><th>書名</th><th>借出日</th><th>應還日</th><th>狀態</th></tr></thead>
      <tbody>
        <?php foreach($recent as $r): ?>
          <tr>
            <td><?= $r['LoanID'] ?></td>
            <td><?= htmlspecialchars($r['Username']) ?></td>
            <td><?= htmlspecialchars($r['Title']) ?></td>
            <td><?= $r['LoanDate'] ?></td>
            <td><?= $r['DueDate'] ?></td>
            <td><?= $r['Status'] ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <div class="mt-4">
    <a class="btn btn-primary" href="manage_books.php">管理書籍</a>
    <a class="btn btn-secondary" href="manage_copies.php">管理副本</a>
    <a class="btn btn-info" href="manage_members.php">管理會員</a>
    <a class="btn btn-outline-dark" href="index.php">回前台</a>
  </div>
</div>
</body></html>
