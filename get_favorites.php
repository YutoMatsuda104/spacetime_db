<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$query = "SELECT toilet_test.*, true AS is_favorite FROM favorites JOIN toilet_test ON favorites.toilet_id = toilet_test.id WHERE favorites.user_id = $1";
$result = pg_query_params($dbconn, $query, array($user_id));

$favorites = [];

while ($row = pg_fetch_assoc($result)) {
    $location = explode(',', trim($row['location'], '()'));
    $favorites[] = [
        'id' => $row['id'],
        'lat' => $location[0],
        'lng' => $location[1],
        'name' => $row['name'],
        'category' => $row['category'],
        'prefecture' => $row['prefecture'],
        'municipalities' => $row['municipalities']
    ];
}

echo json_encode($favorites);

// データベース接続を閉じる
pg_close($dbconn);
?>