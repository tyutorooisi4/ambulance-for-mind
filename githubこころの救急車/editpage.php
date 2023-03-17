<!DOCTYPE html>


<?php
//セッションを開始し、セッション変数を使える状態にしておく
session_start();
//データベースに接続する
$dsn = 'mysql:dbname=dbname;host=hostname';
        $dbuser = 'dbusername';
        $dbpassword = 'password';
    $pdo = new PDO($dsn, $dbuser, $dbpassword, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
//編集フォームから情報が入力された時、それに基づき、レコードを更新する
if(isset($_POST["name"])&&isset($_POST["tel"])&&isset($_POST["address"])&&(isset($_POST["waitinghours"]))){
    $sql = 'UPDATE `psychiatry_list` SET name=:name,tel=:tel,address=:address,priority_rank=:priority_rank,url=:url WHERE id=:id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':tel', $tel, PDO::PARAM_STR);
    $stmt->bindParam(':address', $address, PDO::PARAM_STR);
    $stmt->bindParam(':priority_rank', $priority_rank, PDO::PARAM_INT);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->bindParam(':url', $url, PDO::PARAM_STR);
    $name = $_POST["name"];
    $tel = $_POST["tel"];
    $address = $_POST["address"];
    $waitinghours=explode(":",$_POST["waitinghours"]);
    $priority_rank=$waitinghours[0];
    $id = $_SESSION['id'];
    if(!empty($_POST["url"])&&$_POST["url"]!==""){
        $url=$_POST["url"];
    }else{
        $url = null;
    }
    $stmt->execute();
    echo "登録情報が更新されました<br>";
    echo "<a href='mypage.php'>マイページへ</a>";
    echo "<br><a href='logout.php'>ログアウト</a>";
}
//テーブルからレコードを読み取る　読み取る対象は、idで指定されたもの
$sql = 'SELECT * FROM `psychiatry_list`  WHERE id=:id ';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    //$stmt->bindParam(':password', $password, PDO::PARAM_INT);
 $id = $_SESSION['id'];
//echo $password = $_SESSION['password'];
    $stmt->execute();

    
    //読み取った情報を、フォームに表示させる
    $results = $stmt->fetchAll();
foreach ($results as $row) {
    $name = $row['name'];
    $tel = $row['tel'];
    $address = $row['address'];
    $url = $row['url'];
    $priority_rank=$row['priority_rank'];
}
?>
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
input{
            text-align:left;
        }
    </style>
</head>
<body>
    <form method="post" name="edit" >
        <table>
            <tr><th>機関名</th><th>電話番号</th><th>住所</th><th>待機時間</th><th>ホームページ</th></tr>
        <td><input type="text" name="name" required="required" value= <?php echo $name;
            ?> ></td>
        <td><input type="text"  name="tel" required="required" value= <?php echo $tel;
            ?> ></td>
        <td><input type="text" name="address" required="required" value= <?php echo $address;
            ?> ></td>
        <td><select name="waitinghours" required="required" >
            <?php
             if ($row['priority_rank'] == 0) {
                $waitinghours = '当日診療可能';
            } elseif ($row['priority_rank'] == 1) {
                $waitinghours = '診察まで三日以内';
            } elseif ($row['priority_rank'] == 2) {
                $waitinghours = '診察まで一週間以内';
            } elseif ($row['priority_rank'] == 3) {
                $waitinghours = '診察まで一か月以内';
            }
            //現在選択されている待機時間を表示する
            echo "<option disabled selected value=''>{$priority_rank}:{$waitinghours}</option>";
            ?>
            <option>0:当日診療可能</option>
            <option>1:診察まで三日以内</option>
            <option>2:診察まで一週間以内</option>
            <option>3:診察まで一か月以内</option>
        </select></td>
        <td><input type="text" name="url" value= <?php if($url!==null){echo $url;}?> ></td>
        </table>
        <input type="submit" value="更新" >
    </form >
    <br>
</body>
</html>

