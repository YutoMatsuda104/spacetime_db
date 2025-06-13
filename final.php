<?php
// session_start();
// require 'restruct4/config.php';

// ログインチェック
if (!isset($_SESSION['ems']) || !isset($_SESSION['pws'])) {
    header('Location: ./login.html');
    exit();
}

// データベース接続
function db_connect() {
    $dbconn = pg_connect("host=localhost dbname=s2322104 user=s2322104 password=sL6LFJgg");
    if (!$dbconn) {
        die("Database connection error: " . pg_last_error());
    }
    return $dbconn;
}

// トイレ情報取得
function getToiletsFromDatabase() {
    // $dbconn = db_connect();
    $result = pg_query($dbconn, "SELECT id, location[0] AS latitude, location[1] AS longitude, name, category, prefecture, municipalities FROM toilet_test");
    if (!$result) {
        die("Query failed: " . pg_last_error());
    }
    $toilets = [];
    while ($row = pg_fetch_assoc($result)) {
        $toilets[] = $row;
    }
    pg_close($dbconn);
    return $toilets;
}

// お気に入りトイレ情報取得
function getFavoriteToilets() {
    // get_favorites.php のロジック
    $dbconn = db_connect();
    $result = pg_query($dbconn, "SELECT * FROM favorites WHERE user_id = '" . $_SESSION['user_id'] . "'");
    if (!$result) {
        die("Query failed: " . pg_last_error());
    }
    $favorites = [];
    while ($row = pg_fetch_assoc($result)) {
        $favorites[] = $row;
    }
    pg_close($dbconn);
    return $favorites;
}

// アクションの処理
if (isset($_GET['action'])) {
    header('Content-Type: application/json');
    if ($_GET['action'] === 'get_toilets') {
        echo json_encode(getToiletsFromDatabase());
    } elseif ($_GET['action'] === 'get_favorites') {
        echo json_encode(getFavoriteToilets());
    }
    exit();
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>時空間データベース - トイレ検索</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/style_2.css">
    <style>
        /* サイドナビのスタイル */
        .side-nav {
            width: 200px;
            padding-top: 20px;
            float: left;
        }

        .side-nav a {
            padding: 10px 15px;
            text-decoration: none;
            font-size: 18px;
            color: #000;
            display: block;
        }

        .side-nav a:hover {
            background-color: #ddd;
            color: black;
        }

        .side-nav a.active {
            background-color: #4CAF50;
            color: white;
        }

        .main-content {
            margin-left: 220px;
            padding: 20px;
        }

        #map {
            width: 100%;
            height: 500px;
            border: 0;
        }
    </style>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDneseIU4xfxVvjzVB_qieKqwFwIdYwdAw&callback=initMap" async defer></script>
    <script>
        let map;
        let markers = [];
        let showFavoritesOnly = false;

        function initMap() {
            map = new google.maps.Map(document.getElementById('map'), {
                center: {lat: 35.6895, lng: 139.6917}, // Tokyo coordinates
                zoom: 12
            });

            loadLocations();
        }

        function loadLocations() {
            clearMarkers();
            const url = showFavoritesOnly ? 'get_favorites.php' : 'get_locations.php';
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    data.forEach(location => {
                        const marker = new google.maps.Marker({
                            position: {lat: parseFloat(location.lat), lng: parseFloat(location.lng)},
                            map: map,
                            title: location.name,
                            icon: location.is_favorite ? 'http://maps.google.com/mapfiles/ms/icons/green-dot.png' : null
                        });

                        const infowindow = new google.maps.InfoWindow({
                            content: `<strong>${location.name}</strong><br>${location.category}<br>${location.prefecture} ${location.municipalities}
                            <?php if (isset($_SESSION['user_id'])): ?>
                                <br>
                                <button onclick="addToFavorites(${location.id})">Add to Favorites</button>
                                <button onclick="removeFromFavorites(${location.id})">Remove from Favorites</button>
                            <?php endif; ?>`
                        });

                        marker.addListener('click', () => {
                            infowindow.open(map, marker);
                        });

                        markers.push(marker);
                    });
                })
                .catch(error => console.error('Error:', error));
        }

        function clearMarkers() {
            markers.forEach(marker => marker.setMap(null));
            markers = [];
        }

        function toggleFavorites() {
            showFavoritesOnly = !showFavoritesOnly;
            document.getElementById('toggleFavoritesButton').innerText = showFavoritesOnly ? 'Show All Toilets' : 'Show Favorites Only';
            loadLocations();
        }

        function addToFavorites(toiletId) {
            fetch('favorites.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'action=add&toilet_id=' + toiletId
            })
            .then(response => response.text())
            .then(data => {
                alert(data);
                loadLocations();
            })
            .catch(error => console.error('Error:', error));
        }

        function removeFromFavorites(toiletId) {
            fetch('favorites.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'action=remove&toilet_id=' + toiletId
            })
            .then(response => response.text())
            .then(data => {
                alert(data);
                loadLocations();
            })
            .catch(error => console.error('Error:', error));
        }
    </script>
</head>
<body>
    <header>
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
    <a href="https://muds.gdl.jp/~s2322104/Lec_final/login.html">アカウント</a>
    <a href="https://muds.gdl.jp/~s2322104/introduction.html">作成者紹介</a>
</div>
    </header>

    <div class="side-nav">
        <a href="#" id="current-location-link" class="active">現在地からのトイレ</a>
        <a href="#" id="favorite-toilets-link">お気に入りトイレ</a>
        <a href="#" id="register-toilet-link">トイレ新規登録</a>
        <a href="#" id="search-link">検索</a>
        <a href="https://muds.gdl.jp/~s2322104/Lec_final/logout.php" id="search-link">ログアウト</a>
    </div>

    <div class="main-content">
        <div id="current-location-content">
            <h2>現在地からのトイレ</h2>
            <div id="map" style="height: 600px; width: 100%;"></div>
        </div>
        <div id="favorite-toilets-content" style="display: none;">
            <h2>お気に入りトイレ</h2>
            <!-- お気に入りトイレのコンテンツ -->
        </div>
        <div id="register-toilet-content" style="display: none;">
            <h2>トイレ新規登録</h2>
            <!-- トイレ新規登録のコンテンツ -->
        </div>
        <div id="search-content" style="display: none;">
            <h2>検索</h2>
            <!-- 検索のコンテンツ -->
        </div>
    </div>

    <?php if (isset($_SESSION['user_id'])): ?>
        <p>Welcome, User! <a href="logout.php">Logout</a></p>
        <button id="toggleFavoritesButton" onclick="toggleFavorites()">Show Favorites Only</button>
    <?php else: ?>
        <p><a href="login.php">Login</a> or <a href="register.php">Register</a></p>
    <?php endif; ?>
    <div id="map" style="height: 600px; width: 100%;"></div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</body>
</html>
