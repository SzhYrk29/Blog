<?php

require_once ('../functions/functions_for_database.php');
$database = connectToDatabase();

if (isset($_POST['delete'])) {
    $deletePost = "delete from Posts where post_ID = {$_POST['post_id']}";
    $database->query($deletePost);
    $database->close();
    header('Location: manage_posts.php');
}