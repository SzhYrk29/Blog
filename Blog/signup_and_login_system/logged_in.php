<?php

session_start();

if (isset($_SESSION['role_ID'])) {
    $roleID = $_SESSION['role_ID'];

    if ($roleID == 1) {
        header("Location: ../admin/admin_homepage.php");
    } else if ($roleID == 2) {
        header("Location: ../author/author_homepage.php");
    } else if ($roleID == 3) {
        header("Location: ../user/user_logged_in.php");
    }
} else {
    header("Location: login.php");
}