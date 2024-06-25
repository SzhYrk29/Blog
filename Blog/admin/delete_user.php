<?php

require_once ('../functions/functions_for_database.php');
$database = connectToDatabase();

if (isset($_POST['delete'])) {
    $deleteUser = "delete from Users where user_ID = {$_POST['user_id']}";
    $database->query($deleteUser);
    $database->close();
    header('Location: manage_users.php');
}