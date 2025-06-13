<?php
session_start();
require 'config.php';

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

$query = "SELECT toilet_test.*, favorites.id AS is_favorite FROM toilet_test LEFT JOIN favorites ON toilet_test.id = favorites.toilet_id AND favorites.user_id = $1";
$result = pg_query_params($dbconn, $query, array($user_id));

$locations = [];

while ($row = pg_fetch_assoc($result)) {
    $location = explode(',', trim($row['location'], '()'));
    $locations[] = [
        'id' => $row['id'],
        'lat' => $location[0],
        'lng' => $location[1],
        'name' => $row['name'],
        'category' => $row['category'],
        'prefecture' => $row['prefecture'],
        'municipalities' => $row['municipalities'],
        'is_favorite' => isset($row['is_favorite']) && $row['is_favorite'] !== null
    ];
}

echo json_encode($locations);

// データベース接続を閉じる
pg_close($dbconn);
?>