<?php
require 'db.php';

$kw=trim($_GET['keyword'] ?? '');
$sql="SELECT * FROM book";
$params=[];

if($kw){
  $sql.=" WHERE Title LIKE ? OR Author LIKE ?";
  $params=["%$kw%","%$kw%"];
}
$stmt=$pdo->prepare($sql);
$stmt->execute($params);
$books=$stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>館藏查詢系統</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="assets/styles.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
  <div class="container">
    <a class="navbar-brand" href="index.php">圖書館</a>
    <div class="ms-auto">
    <?php if(isset($_SESSION['user_id'])): ?>
      <a class="btn btn-outline-secondary me-2" href="my_loans.php">我的借閱</a>
      <?php if($_SESSION['role']=='Master'): ?>
        <a class="btn btn-outline-primary me-2" href="admin_dashboard.php">後台管理</a>
      <?php endif; ?>
      <a class="btn btn-danger" href="logout.php">登出</a>
    <?php else: ?>
      <a class="btn btn-outline-primary me-2" href="login.php">登入</a>
      <a class="btn btn-primary" href="register.php">註冊</a>
    <?php endif; ?>
    </div>
  </div>
</nav>

<div class="container container-main">
  <div class="py-4 text-center">
    <h1>館藏查詢系統</h1>
    <p class="text-muted">輸入書名或作者搜尋館藏</p>
    <form class="d-flex justify-content-center" method="get" action="index.php">
      <input class="form-control me-2 w-50" type="search" name="keyword" placeholder="請輸入書名或作者" value="<?= htmlspecialchars($kw) ?>">
      <button class="btn btn-success">搜尋</button>
    </form>
  </div>

  <div class="row">
  <?php foreach($books as $b): 
    $s1=$pdo->prepare("SELECT COUNT(*) FROM copy WHERE BookID=? AND Status='Available'");
    $s1->execute([$b['BookID']]);
    $avail=$s1->fetchColumn();

    $s2=$pdo->prepare("SELECT COUNT(*) FROM copy WHERE BookID=?");
    $s2->execute([$b['BookID']]);
    $total=$s2->fetchColumn();
  ?>
    <div class="col-md-6 mb-3">
      <div class="card p-3">
        <div class="d-flex justify-content-between">
          <div>
            <h5><?= htmlspecialchars($b['Title']) ?></h5>
            <p class="mb-1">作者：<?= htmlspecialchars($b['Author']) ?></p>
            <small class="text-muted">出版社：<?= htmlspecialchars($b['Publisher']) ?> | 年份：<?= $b['PublicationYear'] ?> | 類型：<?= htmlspecialchars($b['Genre']) ?></small>
          </div>
          <div class="text-end">
            <p class="mb-1">總館藏 <span class="badge bg-secondary"><?= $total ?></span></p>
            <p class="mb-1">可借閱 <span class="badge bg-success"><?= $avail ?></span></p>
            <?php if(isset($_SESSION['role']) && $_SESSION['role']=='Master'): ?>
              <div class="mt-2">
                <a href="edit_book.php?id=<?= $b['BookID'] ?>" class="btn btn-warning btn-sm">修改</a>
                <a href="delete_book.php?id=<?= $b['BookID'] ?>" onclick="return confirm('確定刪除?')" class="btn btn-danger btn-sm">刪除</a>
              </div>
            <?php else: ?>
              <?php if($avail>0 && isset($_SESSION['user_id'])): ?>
                <form method="post" action="borrow.php">
                  <input type="hidden" name="bookid" value="<?= $b['BookID'] ?>">
                  <button class="btn btn-primary btn-sm mt-2">借閱此書</button>
                </form>
              <?php elseif(!isset($_SESSION['user_id'])): ?>
                <a href="login.php" class="btn btn-primary btn-sm mt-2">登入後借書</a>
              <?php else: ?>
                <button class="btn btn-secondary btn-sm mt-2" disabled>暫無可借副本</button>
              <?php endif; ?>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
  </div>
</div>

<footer class="footer">
  <div class="container">© 圖書館系統</div>
</footer>
</body>
</html>
