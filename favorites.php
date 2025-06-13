<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $toilet_id = $_POST['toilet_id'];
    $action = $_POST['action'];

    if ($action === 'add') {
        // Check if the toilet is already in favorites
        $check_query = "SELECT * FROM favorites WHERE user_id = $1 AND toilet_id = $2";
        $check_result = pg_query_params($dbconn, $check_query, array($user_id, $toilet_id));

        if (pg_num_rows($check_result) > 0) {
            echo "Already in favorites.";
        } else {
            $query = "INSERT INTO favorites (user_id, toilet_id) VALUES ($1, $2)";
            $result = pg_query_params($dbconn, $query, array($user_id, $toilet_id));

            if ($result) {
                echo "Added to favorites!";
            } else {
                echo "Error: Could not add to favorites.";
            }
        }
    } elseif ($action === 'remove') {
        $query = "DELETE FROM favorites WHERE user_id = $1 AND toilet_id = $2";
        $result = pg_query_params($dbconn, $query, array($user_id, $toilet_id));

        if ($result) {
            echo "Removed from favorites!";
        } else {
            echo "Error: Could not remove from favorites.";
        }
    }
}
?>