<?php

require_once ('../functions/functions_for_database.php');
$database = connectToDatabase();

if (isset($_POST['delete'])) {
    $deleteComment = "delete from Comments where comment_ID = {$_POST['comment_id']}";
    $database->query($deleteComment);
    $database->close();
    header('Location: manage_comments.php');
}