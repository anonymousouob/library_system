<?php
    require 'db.php';

    $users_data = [
        ['1', 'admin', 'Abcd1234', 'Administrator', 'Master'] 
    ];

    foreach ($users_data as $user) {
        $username = $user[1];
        $password = $user[2];
        $account  = $user[3];
        $type     = $user[4];
        $hash = password_hash($password, PASSWORD_DEFAULT);

        try {
            $pdo->prepare("DELETE FROM member WHERE Username = ?")->execute([$username]);
            $stmt = $pdo->prepare("INSERT INTO member (Username, Password, Account, MemberType) VALUES (?, ?, ?, ?)");
            $stmt->execute([$username, $hash, $account, $type]);

            echo "帳號建立成功！<br>";
            echo "使用者名稱: $username<br>";
            echo "明文密碼: $password<br>";
            echo "雜湊密碼: $hash<br><br>";

        } catch (PDOException $e) {
            echo "帳號 <strong>$username</strong> 建立失敗: " . $e->getMessage() . "<br>";
        }
    }
?>