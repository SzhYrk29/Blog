<?php

session_start();

$default_role = 4;

if (isset($_SESSION['role_ID'])) {
    $user_role = $_SESSION['role_ID'];
} else {
    $user_role = $default_role;
}

$user_id = isset($_SESSION['user_ID']) ? $_SESSION['user_ID'] : null;

if ($user_role !== 3) {
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
    <link rel="stylesheet" href="../css/delete_account.css">
    <link rel="icon" type="image/x-icon" href="../pictures/favicon.ico">
</head>
<body>

<?php
include_once ('user_header.php')
?>

<div id="main_div">
    <nav class="nav_admin_panel">
        <div class="div_admin_panel">
            <p><a href="account_info.php">Account info</a></p>
            <p><a href="edit_account_form.php">Edit account</a></p>
            <p><a href="delete_account.php">Delete account</a></p>
            <p><a href="manage_comments.php">My comments</a></p>
        </div>
    </nav>

    <main>
        <h1>Delete account</h1>
        <p>Here you can delete your account from our website, but there's no coming back, so think twice before clicking button below.</p>
        <h3>Are you sure you want to delete your account?</h3>
        <form method="POST" action="delete_account_script.php">
            <input type="submit" name="delete" value="Yes, delete">
        </form>
    </main>
</div>

</body>
</html>