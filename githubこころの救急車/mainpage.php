<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>こころの救急車</title>
    <link rel="stylesheet" type="text/css" href="mainpage.css?<?php echo date('YmdHis'); ?>">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" rel="stylesheet">
    <script src="mainpage.js"></script>
</head>
<body>
    <div class="bg_onetime_popup">
  <div class="onetime_popup">
    <div class="onetime_popup_title">
      <span class="onetime_popup_title_close"></span>
      <span>どこの精神科・心療内科をお探しですか？</span>
    </div>
    <div class="onetime_popup_content">
        <form method="post" >
         <select name="prefecture" class="setOptionSelected" required="required">
            <option value="" disabled selected>--都道府県をお選びください--</option>
            <option >大阪府</option>
            <option >東京都</option>
            <option >奈良県</option>
            <input  type="submit" value="送信">
        </select>
        </form >
    </div>
  </div>
</div>
    
        <!-- ここはスマホではハンバーガーメニューにする -->
<header>
    <ul>
        <li><a href="about.php">このサイトについて</a></li>
        <li><a href="#helthcenter_list">受診相談窓口</a></li>
        <li><a href="#psychiatry_list">診療機関のリスト</a></li>
        <li><a href="#selfcare">セルフケアのために<br>できること</a></li>
        <li><a href="forMedicalInstitution.php">医療機関の皆様へ</a></li>
    </ul>
</header>
        <h2 class="tytle">こころの救急車</h2>
        <?php 
        session_start(); 
        if(!empty($_POST["prefecture"])){
            echo "現在、{$_POST["prefecture"]}の情報を表示しています<br>";
            }elseif(empty($_POST['prefecture'])&&!empty($_SESSION["prefecture"])){
                 echo "現在、{$_SESSION["prefecture"]}の情報を表示しています<br>";
                }
        ?>
    <form method="post" >
        <select name="prefecture" class="setOptionSelected" required="required">
            <option value="" disabled selected>--都道府県をお選びください--</option>
            <option>大阪府</option>
            <option>東京都</option>
            <option>奈良県</option>
        </select>
        <input type="submit" value="送信">
    </form >
    <?php
    //セッションを開始し、セッション変数を使える状態にしておく
    //セッション変数に情報が格納されている場合、その情報を使用する
    if(empty($_POST['prefecture'])&&!empty($_SESSION["prefecture"])){
        $dsn = 'mysql:dbname=dbname;host=hostname';
        $dbuser = 'dbusername';
        $dbpassword = 'password';
        $pdo = new PDO($dsn, $dbuser, $dbpassword, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
        echo "<div class='helthcenterWrapper'>";
        echo "<h3><a id='helthcenter_list'>受診相談窓口</a></h3>";
        echo "<h4>保健所や精神保健福祉センターは、こころの健康についての電話での相談や、あなたに適した受診先案内を行っています<br>";
        echo "精神科や心療内科の受診を考えている方は、ぜひご利用ください</h4>";
        //出来上がったdbを読み込んで、保健所の電話番号を表示する　プルダウンメニューで選ばれた都道府県の保健所のみが出ます
        $sql = 'SELECT * FROM helthcenter_list WHERE prefecture=:prefecture';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':prefecture', $prefecture, PDO::PARAM_STR);
        $prefecture = $_SESSION['prefecture'];
        $stmt->execute();
        $results = $stmt->fetchAll();
        //htmlで作成した表の中に、データベースから取ってきたデータを挿入する 
        echo '<table>';
        echo '<tr><th>機関名</th><th>電話番号</th></tr>';
        foreach ($results as $row) {
            echo '<tr>';
            echo "<td> {$row['name']} </td>";
            //いわゆるタップコールが可能なような形で電話番号を表示します
            echo "<td><a href='tel:{$row['tel']}'>{$row['tel']}</a></td>";
            echo '</tr>';
        }
         echo '</table>';
        echo "</div>";
        //以下精神科リストの表示のための処理
        //選ばれた都道府県の診療所のみを表示するため、データベースからソートと絞り込みを使ってデータを持ってくる
        
        $sql = 'SELECT * FROM `psychiatry_list`  WHERE prefecture=:prefecture ORDER BY `priority_rank`';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':prefecture', $prefecture, PDO::PARAM_STR);
        //$prefecture = $_POST['prefecture']; 上のプルダウンメニューで選択された時のデータをそのまま使ってます
        $stmt->execute();
        $results = $stmt->fetchAll();
        //htmlで作成した表の中に、データベースから取ってきたデータを挿入する 
        echo "<div class='psychiatryWrapper'>";
        echo "<h3><a id='psychiatry_list'>精神科・心療内科のリスト</a></h3>";
        echo "<h4>当サイトに登録された精神科・心療内科のリストです<br>";
        echo "待機時間が短いものが上にくるように並び替えておりますので、こころの健康について緊急の用がある方はぜひご活用ください</h4>";
        echo '<table>';
        echo '<tr><th>機関名</th><th>電話番号</th><th>住所</th><th>待機時間</th></tr>';
        foreach ($results as $row) {
            echo '<tr>';
            //データベースのpriority_rankの数値に合わせて、待機時間情報を割り振る　数値が小さい方が待機時間も短いです
            if ($row['priority_rank'] == 0) {
                $waitinghours = '当日診療可能';
            } elseif ($row['priority_rank'] == 1) {
                $waitinghours = '診察まで三日以内';
            } elseif ($row['priority_rank'] == 2) {
                $waitinghours = '診察まで一週間以内';
            } elseif ($row['priority_rank'] == 3) {
                $waitinghours = '診察まで一か月以内';
            }
            //urlがデータベース上に記載されていて、nullでは無い時に、病院名が公式サイトへのリンクになるようにしています
            //フォントオーサムからアイコンを持ってきています
            if($row["url"]!==null){
                echo "<td> <a href='{$row["url"]}'> {$row['name']} </a><i class='fa-solid fa-arrow-up-right-from-square fa-xs'></i> </td>";
            }else{
                echo "<td> {$row['name']} </td>";
            }
            //いわゆるタップコールが可能なような形で電話番号を表示します
            echo "<td><a href='tel:{$row['tel']}'>{$row['tel']}</a></td>";
            echo "<td> {$row['address']} </td>";
            echo "<td> {$waitinghours} </td>";
            echo '</tr>';
        }
        echo "</table>";
        echo "</div>";
    }

    //セッション変数に情報が登録されていなくて、
    //フォームから情報が送信された時、以下の処理を行う
    //まず、dbに接続する
    if (!empty($_POST['prefecture'])) {
        $dsn = 'mysql:dbname=dbname;host=hostname';
        $dbuser = 'dbusername';
        $dbpassword = 'password';
        $pdo = new PDO($dsn, $dbuser, $dbpassword, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
        echo "<div class='helthcenterWrapper'>";
        echo "<h3><a id='helthcenter_list'>受診相談窓口</a></h3>";
        echo "<h4>保健所や精神保健福祉センターは、こころの健康についての電話での相談や、あなたに適した受診先案内を行っています<br>";
        echo "精神科や心療内科の受診を考えている方は、ぜひご利用ください</h4>";
        //出来上がったdbを読み込んで、保健所の電話番号を表示する　プルダウンメニューで選ばれた都道府県の保健所のみが出ます
        $sql = 'SELECT * FROM helthcenter_list WHERE prefecture=:prefecture';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':prefecture', $prefecture, PDO::PARAM_STR);
        $prefecture = $_POST['prefecture'];
        $stmt->execute();
        $results = $stmt->fetchAll();
        //htmlで作成した表の中に、データベースから取ってきたデータを挿入する 
        echo '<table>';
        echo '<tr><th>機関名</th><th>電話番号</th></tr>';
        foreach ($results as $row) {
            echo '<tr>';
            echo "<td> {$row['name']} </td>";
            //いわゆるタップコールが可能なような形で電話番号を表示します
            echo "<td><a href='tel:{$row['tel']}'>{$row['tel']}</a></td>";
            echo '</tr>';
        }
         echo '</table>';
        echo '</div>';
        //以下精神科リストの表示のための処理
        //選ばれた都道府県の診療所のみを表示するため、データベースからソートと絞り込みを使ってデータを持ってくる
        
        $sql = 'SELECT * FROM `psychiatry_list`  WHERE prefecture=:prefecture ORDER BY `priority_rank`';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':prefecture', $prefecture, PDO::PARAM_STR);
        //$prefecture = $_POST['prefecture']; 上のプルダウンメニューで選択された時のデータをそのまま使ってます
        $stmt->execute();
        $results = $stmt->fetchAll();
        //htmlで作成した表の中に、データベースから取ってきたデータを挿入する 
        echo "<div class='psychiatryWrapper'>";
        echo "<h3><a id='psychiatry_list'>精神科・心療内科のリスト</a></h3>";
         echo "<h4>当サイトに登録された精神科・心療内科のリストです<br>";
        echo "待機時間が短いものが上にくるように並び替えておりますので、こころの健康について緊急の用がある方はぜひご活用ください</h4>";
        echo '<table>';
        echo '<tr><th>機関名</th><th>電話番号</th><th>住所</th><th>待機時間</th></tr>';
        foreach ($results as $row) {
            echo '<tr>';
            //データベースのpriority_rankの数値に合わせて、待機時間情報を割り振る　数値が小さい方が待機時間も短いです
            if ($row['priority_rank'] == 0) {
                $waitinghours = '当日診療可能';
            } elseif ($row['priority_rank'] == 1) {
                $waitinghours = '診察まで三日以内';
            } elseif ($row['priority_rank'] == 2) {
                $waitinghours = '診察まで一週間以内';
            } elseif ($row['priority_rank'] == 3) {
                $waitinghours = '診察まで一か月以内';
            }
            //urlがデータベース上に記載されていて、nullでは無い時に、病院名が公式サイトへのリンクになるようにしています
            if($row["url"]!==null&&$row["url"]!==""){
                echo "<td> <a href='{$row["url"]}'> {$row['name']} </a><i class='fa-solid fa-arrow-up-right-from-square'></i> </td>";
            }else{
                echo "<td> {$row['name']} </td>";
            }
            //いわゆるタップコールが可能なような形で電話番号を表示します
            echo "<td><a href='tel:{$row['tel']}'>{$row['tel']}</a></td>";
            echo "<td> {$row['address']} </td>";
            echo "<td> {$waitinghours} </td>";
            echo '</tr>';
        }
        echo "</table>";
        echo "</div>";
        //セッション変数に、プルダウンメニューで選ばれた情報を記憶させておきます
        $_SESSION["prefecture"]=$_POST["prefecture"];
    }?>
    <div class="selfcareWrapper">
    <h3><a id='selfcare'>セルフケアのためにできること</a></h3>
     <h4>診察を受けるまでの間のこころの痛みを少しでも和らげることができるよう、手軽に行えるセルフケアを紹介しております</h4>
    <a id="selfcareLink" href="selfcare.php">セルフケアについて</a>
    </div>
<footer>
    <ul>
        <li><a href="about.php">このサイトについて</a></li>
        <li><a href="#helthcenter_list">受診相談窓口</a></li>
        <li><a href="#psychiatry_list">診療機関のリスト</a></li>
        <li><a href="#selfcare">セルフケアのために<br>できること</a></li>
        <li><a href="forMedicalInstitution.php">医療機関の皆様へ</a></li>
    </ul>
</footer>

</body>
</html>
    