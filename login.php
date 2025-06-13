<?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $username = $_POST['username'];
    $query = "SELECT * FROM space_user WHERE uname = $1";
    $result = pg_query_params($dbconn, $query, array($username));
    $user = pg_fetch_assoc($result);

    if ($user && password_verify($_POST['password'], $user['password'])) {
        $_SESSION['user_id'] = $user['user_id'];
        header("Location: restruct4/home.php");
        exit();
    } else {
        echo "Invalid username or password.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $username = $_POST['u'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $query = "INSERT INTO space_user (uname, password) VALUES ($1, $2)";
    $result = pg_query_params($dbconn, $query, array($username, $password));

    if ($result) {
        echo "Registration successful! Please login.";
    } else {
        echo "Error: Could not register.";
    }
}
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

    <main class="content-wrapper">
        <h1>ログイン</h1>
        <div class="content-section">
            <form method="POST" action="login.php">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary" name="login">ログイン</button>
            </form>
            <p>はじめての方は<a href="https://muds.gdl.jp/~s2322104/Lec_final/regform.html">こちら</a>から登録してください。</p>
        </div>
    </main>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</body>
</html>
