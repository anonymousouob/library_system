<?php
// 驗證帳號格式
function validAccount($acc){ 
    return preg_match('/^[A-Za-z0-9]+$/', $acc); 
}

// 驗證密碼輸入
function validPasswordInput($pwd){ 
    return !preg_match("/['\"]/", $pwd); 
}
?>