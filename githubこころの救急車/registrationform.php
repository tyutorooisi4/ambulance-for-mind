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
    こちらは新規登録フォームです<br>
    事前にお配りしたidを入力し、パスワードを設定してください<br>
    <form method="post" name="registrate" >
        <input type="text" required="required" name="id" placeholder="idを入力してください" />
        <input type="text" required="required" name="password" placeholder="パスワードを入れてください" />
        <input type="submit" value="登録" />
    </form >
    <br>
     <a href="forMedicalInstitution.php">戻る</a>
    <br>
</body>
</html>
<?php
$dsn = 'mysql:dbname=dbname;host=hostname';
        $dbuser = 'dbusername';
        $dbpassword = 'password';
    $pdo = new PDO($dsn, $dbuser, $dbpassword, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

if (isset($_POST['id']) && isset($_POST['password'])) {
    //パスワード登録を行う前に、idを照合する
    $sql = 'SELECT * FROM `psychiatry_list` WHERE id = :id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $id = $_POST['id'];
    $stmt->execute();
    $results = $stmt->fetch();
    //idが既に登録されたものであれば、そのままパスワード登録を行う
    if ($results['id'] === $id) {
        $sql = 'UPDATE `psychiatry_list` SET password =:password WHERE id=:id ';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':password', $hashpass, PDO::PARAM_STR);
        $id = $_POST['id'];
        $hashpass = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $stmt->execute();
        echo '登録が完了しました';
        echo '<br><a href="loginpage.php">ログインフォームへ</a>';
    } else {
        echo '存在しないidです';
    }
}
?>
