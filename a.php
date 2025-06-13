<?php
// データベース接続情報
$dbconn = pg_connect("host=localhost dbname=s2322104 user=s2322104 password=sL6LFJgg")
    or die('Could not connect: ' . pg_last_error());

// POSTデータの取得
$name = pg_escape_string($_POST['name']);
$category = pg_escape_string($_POST['category']);
$prefecture = pg_escape_string($_POST['prefecture']);
$municipalities = pg_escape_string($_POST['pl']);

// location データはPOSTデータに基づいて作成される
$longitude = pg_escape_string($_POST['longitude']);
$latitude = pg_escape_string($_POST['latitude']);
$location = "($latitude, $longitude)";

// プリペアドステートメントの作成と実行
$sql = 'INSERT INTO toilet_test (name, category, prefecture, municipalities, location) VALUES ($1, $2, $3, $4, $5)';
$result = pg_prepare($dbconn, "insert_toilet", $sql);
$result = pg_execute($dbconn, "insert_toilet", array($name, $category, $prefecture, $municipalities, $location));

// クエリ成功の確認
if ($result) {
    echo "New record created successfully";
} else {
    echo "Error in inserting record: " . pg_last_error($dbconn);
}

// データベース接続のクローズ
pg_close($dbconn);
?>



<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>時空間データベース授業用サイト</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/style_2.css">
</head>
<body>
    <header>
        <div class="header">
            <h1>時空間データベース</h1>
        </div>
        <nav class="nav">
            <a href="https://muds.gdl.jp/~s2322104/">ホーム</a>
            <div class="dropdown">
                <a href="javascript:void(0)">授業課題</a>
                <div class="dropdown-content">
                    <a href="https://muds.gdl.jp/~s2322104/Lec01/Lec01_hw.html">第1回</a>
                    <div class="sub-dropdown">
                        <a href="javascript:void(0)">第2回</a>
                        <div class="sub-dropdown-content">
                            <a href="https://muds.gdl.jp/~s2322104/Lec02/Lec02_q1.php">2-1</a>
                            <a href="https://muds.gdl.jp/~s2322104/Lec02/Lec02_q2.php">2-2</a>
                            <a href="https://muds.gdl.jp/~s2322104/Lec02/Lec02_q3.php">2-3</a>
                            <a href="https://muds.gdl.jp/~s2322104/Lec02/Lec02_q4.php">2-4</a>
                            <a href="https://muds.gdl.jp/~s2322104/Lec02/Lec02_q5.php">2-5</a>
                        </div>
                    </div>
                </div>
            </div>
            <a href="https://muds.gdl.jp/~s2322104/Lec_final/index.php">最終課題</a>
            <!-- <a href="https://muds.gdl.jp/~s2322104/Lec_final/login.html">アカウント</a> -->
            <a href="https://muds.gdl.jp/~s2322104/introduction.html">作成者紹介</a>
        </nav>
    </header>

    <p>データが追加されました</p>
    <button onclick="window.location.href='https://muds.gdl.jp/~s2322104/Lec_final/restruct4/home.php'">マップに戻る</button>
</body>
</html>