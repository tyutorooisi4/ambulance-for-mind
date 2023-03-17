<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>こころの救急車</title>
    <style>
        *{
            text-align:center;
        }
        input{
            text-align:left;
        }
    </style>
</head>
<body>
    こちらはログインフォームです<br>
    idとパスワードを入力してください<br>
    <form action="" method="post" name="login">
        <input type="text" name="id" placeholder= 'idを入力してください' required="required"> 
        <input type="text" name="password" placeholder= 'パスワードを入力してください' required="required">
        <input type="submit" value="ログイン">
    </form>
    <br>
    <a href="forMedicalInstitution.php">戻る</a>
    <br>
</body>
</html>

<?php
    //セッションとやらを使う
    session_start();
    //データベースに接続する
   $dsn = 'mysql:dbname=dbname;host=hostname';
        $dbuser = 'dbusername';
        $dbpassword = 'password';
    $pdo = new PDO($dsn, $dbuser, $dbpassword, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

    if (isset($_POST['id']) && isset($_POST['password'])) {
        $sql = 'SELECT * FROM `psychiatry_list` WHERE id=:id ';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $id = $_POST['id'];
        $stmt->execute();
         //passwordの値を取得して、入力された内容と照合する
        $results = $stmt->fetchAll();
        foreach ($results as $row) {
            $id = $row['id'];
            $password = $row['password'];
            if (password_verify($_POST['password'], $password)) {
                //情報をセッション変数に登録
                echo $_SESSION['id'] = $id;
                echo $_SESSION['password'] = $password;
                header('Location:./successfullyLoggedIn.php');
            } else {
                //パスワードが間違っている場合
        echo 'idまたはパスワードが間違っています';
            }
        }
    }
?>