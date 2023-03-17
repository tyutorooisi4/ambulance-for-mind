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
echo "ログインに成功しました<br>二秒後に遷移します<br>";
echo "<a href='mypage.php'>遷移しない場合はこちら</a>";
header('refresh:2;url=mypage.php');
?>