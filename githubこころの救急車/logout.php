<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>こころの救急車</title>
    <style>
        *{
            text-align:center;
        }
    </style>
</head>
</html>
<?php
    session_start();
    unset($_SESSION["id"],$_SESSION["password"]);
    echo "ログアウトに成功しました<br>二秒後に遷移します<br>";
    echo "<a href='mainpage.php'>遷移しない場合はこちら</a>";
    header('refresh:2;url=mainpage.php');
    
?>