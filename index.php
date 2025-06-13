<?php
session_start();

// 変数の初期化
$ems = null;
$pws = null;

if (isset($_SESSION['ems'])) {
    $ems = $_SESSION['ems'];
}
if (isset($_SESSION['pws'])) {
    $pws = $_SESSION['pws'];
}
if (isset($_POST['emf'])) {
    $ems = $_POST['emf'];
}
if (isset($_POST['pwf'])) {
    $pws = $_POST['pwf'];
}

$aflag = 0;

if (isset($ems) && isset($pws)) {
    $dbconn = pg_connect("host=localhost dbname=s2322104 user=s2322104 password=sL6LFJgg")
        or die('Could not connect: ' . pg_last_error());

    $sql = "SELECT * FROM space_user WHERE email = $1";
    $result = pg_query_params($dbconn, $sql, array($ems)) or die('Query failed: ' . pg_last_error());

    if (pg_num_rows($result) == 1) {
        $row = pg_fetch_assoc($result);
        if (password_verify($pws, $row['password'])) {
            $_SESSION['ems'] = $ems;
            $_SESSION['pws'] = $pws;
            $aflag = 1;
        }
    }

    pg_free_result($result);
    pg_close($dbconn);
}

if ($aflag == 0) {
    header('Location: ./login.php');
    exit();
} else {
    header('Location: ./restruct4/home.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>時空間データベース授業用サイト</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<div class="header">
    <h1>時空間データベース</h1>
</div>

<div class="nav">
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
    <a href="https://muds.gdl.jp/~s2322104/Lec_final/index.php">アカウント</a>
    <a href="https://muds.gdl.jp/~s2322104/introduction.html">作成者紹介</a>
</div>

<!--コンテンツエリア  -->
<?php
echo "<p>LOGIN SUCCEED: " . htmlspecialchars($ems, ENT_QUOTES, 'UTF-8') . "</p>\n";
?>


    <!-- BootstrapのJavaScriptをCDNから読み込む -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</body>
</html>
