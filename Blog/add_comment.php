<?php
session_start();

require_once('functions/functions_for_database.php');
$database = connectToDatabase();

$post_id = $_POST['post_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $comment_content = $_POST['comment_content'];

    $username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';

    $sql_insert = "INSERT INTO Comments (content, publish_time, post_ID, username) VALUES (?, NOW(), ?, ?)";
    $stmt_insert = $database->prepare($sql_insert);
    $stmt_insert->bind_param("sis", $comment_content, $post_id, $username);

    /*
     metoda bind_param wiąże zmienne PHP z placeholderami ? w zapytaniu SQL.
    "sis" wskazuje typy danych dla zmiennych w kolejności, w jakiej są one podawane
     */

    if ($stmt_insert->execute()) {
        header("Location: view_more.php?post_id=$post_id&success=Comment added successfully");
    } else {
        $error_message = $stmt_insert->error;
        header("Location: view_more.php?post_id=$post_id&error=Error adding comment: " . urlencode($error_message));
    }

    $stmt_insert->close();
} else {
    header("Location: view_more.php?post_id=$post_id&error=Invalid request method");
}

$database->close();