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
    <a class="navbar-brand" href="index.php">zzz圖書館</a>
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
    <div class="col-md-6 mb-4"> <div class="card h-100 shadow-sm"> 
        <div class="row g-0 h-100">
          
          <div class="col-md-4" style="min-height: 200px;"> <?php if(!empty($b['ImagePath']) && file_exists($b['ImagePath'])): ?>
              <img src="<?= htmlspecialchars($b['ImagePath']) ?>" 
                   class="img-fluid rounded-start h-100 w-100" 
                   alt="<?= htmlspecialchars($b['Title']) ?>" 
                   style="object-fit: cover;">
          <?php else: ?>
              <div class="bg-light d-flex align-items-center justify-content-center h-100 w-100">
                  <span class="text-muted">無圖片</span>
              </div>
          <?php endif; ?>
          </div>

          <div class="col-md-8">
            <div class="card-body d-flex flex-column h-100">
              
              <div class="mb-auto">
                <div class="d-flex justify-content-between align-items-start">
                    <h5 class="card-title fw-bold text-truncate" title="<?= htmlspecialchars($b['Title']) ?>">
                        <?= htmlspecialchars($b['Title']) ?>
                    </h5>
                </div>
                <p class="card-text mb-1">作者：<?= htmlspecialchars($b['Author']) ?></p>
                <small class="text-muted d-block">
                    出版社：<?= htmlspecialchars($b['Publisher']) ?> <br>
                    年份：<?= $b['PublicationYear'] ?> | 類型：<?= htmlspecialchars($b['Genre']) ?>
                </small>
              </div>

              <div class="mt-3 border-top pt-2">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="badge bg-secondary me-1">總藏: <?= $total ?></span>
                        <span class="badge bg-success">可借: <?= $avail ?></span>
                    </div>

                    <div class="text-end">
                        <?php if(isset($_SESSION['role']) && $_SESSION['role']=='Master'): ?>
                            <a href="edit_book.php?id=<?= $b['BookID'] ?>" class="btn btn-warning btn-sm">修改</a>
                            <a href="delete_book.php?id=<?= $b['BookID'] ?>" onclick="return confirm('確定刪除?')" class="btn btn-danger btn-sm">刪除</a>
                        <?php else: ?>
                            <?php if($avail>0 && isset($_SESSION['user_id'])): ?>
                                <form method="post" action="borrow.php" class="d-inline">
                                    <input type="hidden" name="bookid" value="<?= $b['BookID'] ?>">
                                    <button class="btn btn-primary btn-sm">借閱</button>
                                </form>
                            <?php elseif(!isset($_SESSION['user_id'])): ?>
                                <a href="login.php" class="btn btn-primary btn-sm">登入</a>
                            <?php else: ?>
                                <button class="btn btn-secondary btn-sm" disabled>缺書</button>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
              </div>

            </div>
          </div> </div> </div> </div>
  <?php endforeach; ?>
  </div>
</div>

<footer class="footer">
  <div class="container">© 圖書館系統</div>
</footer>
</body>
</html>
