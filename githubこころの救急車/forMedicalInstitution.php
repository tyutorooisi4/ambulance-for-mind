 <meta charset="UTF-8">
  <title>こころの救急車</title>
  <style>
        *{
            text-align:center;
            color: rgb(26, 26, 26);
        }
       body{
         width: 1200px;
  background-image: url("wool-white2.jpg");
  background-blend-mode: multiply;
        display:flex;
        justify-content: center;
      }
      .wrapper{
        display:flex;
        justify-content: center;
        flex-direction: column;
        width:60%;
      }
        ul{
          display: flex;
  justify-content: center;
        }
          li{
          list-style:none;
          margin:0 5%;
          width:25%;
        }
       ul a{
          display: flex;
          background-color: white;
  border: 1px solid white;
  border-radius: 50px;
  text-decoration: none;
  padding: 15px;
  align-items: center;
  justify-content: center;
  flex-grow: 1;
        }
        .gomainpage {
          display:block;
          width:25%;
          background-color: white;
  border: 1px solid white;
  border-radius: 50px;
  text-decoration: none;
  padding: 15px;
  margin:10% auto 2% auto;
        }
        a:hover{
          opacity:0.8;
        }
    </style>
</head>
<body> 
  <div class="wrapper">
  <h2>医療機関の皆様へ</h2>
  <p>当サイト内のリストの情報更新は、マイページへログインなさった後、更新専用フォームより行って頂きますようお願い申し上げます。<br> 
        また、マイページにログインする際に必要となるパスワードの設定は、新規登録フォームより行ってください。<br></p>
  <ul>
    <li><a href="registrationform.php">新規登録フォーム</a></li>
    <li><a href="loginpage.php">ログインフォーム</a></li>
  </ul> 
  <a class="gomainpage" href="mainpage.php">サイトトップへ</a> 
      </div>
</body>
</html>