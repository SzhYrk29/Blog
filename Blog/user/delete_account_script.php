<?php

session_start();

$user_ID = $_SESSION['user_ID'];

require_once ('../functions/functions_for_database.php');
$database = connectToDatabase();

if (isset($_POST['delete'])) {
    $deleteUser = "delete from Users where user_ID = $user_ID";
    $database->query($deleteUser);
    $database->close();
    header('Location: ../signup_and_login_system/signup.php');
}