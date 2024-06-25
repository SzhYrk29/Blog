<?php

session_start();

$default_role = 4;

if (isset($_SESSION['role_ID'])) {
    $user_role = $_SESSION['role_ID'];
} else {
    $user_role = $default_role;
}

$user_id = isset($_SESSION['user_ID']) ? $_SESSION['user_ID'] : null;

if ($user_role !== 1) {
    echo "You do not have sufficient rights to access this page. <br>";
    echo "Go to <a href='../signup_and_login_system/login.php'>login page</a>.";
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Blog</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/admin_panel.css">
    <link rel="icon" type="image/x-icon" href="../pictures/favicon.ico">
</head>
<body>

<?php
include_once ('admin_header.php');
?>

<div id="main_div">
    <nav class="nav_admin_panel">
        <div class="div_admin_panel">
            <p><a href='manage_users.php'>Users</a></p>
            <p><a href='create_new_post.php'>Add new post</a></p>
            <p><a href='admin_manage_posts.php'>Posts</a></p>
            <p><a href='manage_comments.php'>Comments</a></p>
        </div>
    </nav>

    <main>
    </main>
</div>

</body>
</html>