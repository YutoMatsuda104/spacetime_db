<html>
<head>
<title>registration</title>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
</head>
<body>
<?php
if (isset($_POST['emf'])){$emf=$_POST['emf'];}
if (isset($_POST['unf'])){$unf=$_POST['unf'];}
if (isset($_POST['pwf1'])){$pwf1=$_POST['pwf1'];}
if (isset($_POST['pwf2'])){$pwf2=$_POST['pwf2'];}
if ($pwf1 !== $pwf2){
    echo "<p>パスワードが一致しませんでした。</p>";
    echo "<a href=\"./regform.html\">戻る</a>";
} elseif (isset($emf) && isset($unf) && isset($pwf1)) {
    $dbconn = pg_connect("host=localhost dbname=s2322104 user=s2322104 password=sL6LFJgg")
    or die('Could not connect: ' . pg_last_error());

    $sql = "SELECT * FROM space_user WHERE email = $1";
    $result = pg_query_params($dbconn, $sql, array($emf)) or die('Query failed: ' . pg_last_error());

    if (pg_num_rows($result) == 0) {
        $npwh = password_hash($pwf1, PASSWORD_BCRYPT);
        $sql = "INSERT INTO space_user (uname, email, password) VALUES ($1, $2, $3)";
        $result = pg_query_params($dbconn, $sql, array($unf, $emf, $npwh)) or die('Query failed: ' . pg_last_error());
        if ($result) {
            header('Location: ./index.php');
            exit();
        } else {
            echo "<p>登録に失敗しました。</p>";
            echo "<a href=\"./regform.html\">戻る</a>";
        }
    } else {
        echo "<p>そのメールアドレスはすでに登録されています。</p>";
        echo "<a href=\"./regform.html\">戻る</a>";
    }

    pg_free_result($result);
    pg_close($dbconn);
} else {
    echo '<p>エラーが発生しました。</p>';
    echo '<a href="./regform.html">戻る</a>';
}
?>
</body>
</html>
