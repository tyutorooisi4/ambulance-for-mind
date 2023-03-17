<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>こころの救急車</title>
    <style>
        *{
            text-align:center;
        }
        table {
  margin-left: auto;
  margin-right: auto;
}
th {
  border: solid 1px black;
  border-radius: 2px;
}
td {
  text-align: left;
  padding: 5px 5px;
  border: solid 1px black;
  border-radius: 2px;
}
    </style>
</head>
</html>
<?php
//セッションを開始し、セッション変数を使える状態にしておく
session_start();
//データベースに接続する
$dsn = 'mysql:dbname=dbname;host=hostname';
        $dbuser = 'dbusername';
        $dbpassword = 'password';
    $pdo = new PDO($dsn, $dbuser, $dbpassword, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

   
//テーブルからレコードを読み取る　読み取る対象は、idで指定されたもの
$sql = 'SELECT * FROM `psychiatry_list`  WHERE id=:id ';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $id = $_SESSION['id'];
    $stmt->execute();

    
    //読み取った情報を、ページに表示させる
    $results = $stmt->fetchAll();
echo "id{$id}様の現在の登録情報はこちらです<br>";
 echo '<table>';
        echo '<tr><th>機関名</th><th>電話番号</th><th>住所</th><th>待機時間</th><th>ホームページ</th></tr>';
        echo '<tr>';
    foreach ($results as $row) {
     if ($row['priority_rank'] == 0) {
                $waitinghours = '当日診療可能';
            } elseif ($row['priority_rank'] == 1) {
                $waitinghours = '診察まで三日以内';
            } elseif ($row['priority_rank'] == 2) {
                $waitinghours = '診察まで一週間以内';
            } elseif ($row['priority_rank'] == 3) {
                $waitinghours = '診察まで一か月以内';
            }
    if($row["url"]==null){
        $url = "ホームページが登録されていません";
    }else{
        $url = $row["url"];
    }
    echo "<td> {$row['name']} </td>";
    echo "<td>{$row['tel']}</td>";
    echo "<td> {$row['address']} </td>";
    echo "<td> {$waitinghours} </td>";
    echo "<td> {$url} </td>";
    echo '</tr>';
    echo "</table>";
    echo "<br>";
}
echo "<a href='editpage.php'>更新フォームはこちら</a><br>";
echo "<a href='logout.php'>ログアウト</a>"
?>