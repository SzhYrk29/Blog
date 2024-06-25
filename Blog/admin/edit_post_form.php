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

require_once ('../functions/functions_for_database.php');
$database = connectToDatabase();

$post_id = isset($_POST['post_id']) ? $_POST['post_id'] : (isset($_GET['post_id']) ? $_GET['post_id'] : null);

if ($post_id === null) {
    die("Post ID is not specified.");
}

$sql = "SELECT * FROM Posts WHERE post_ID = '$post_id'";
$result = $database->query($sql);

$error = isset($_GET['error']) ? $_GET['error'] : "";
$success = isset($_GET['success']) ? $_GET['success'] : "";

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    $title = $row['title'];
    $content = $row['content'];
    $photo = $row['photo'];

    echo "
    <!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <title>Blog</title>
        <link rel='stylesheet' href='../css/styles.css'>
        <link rel='stylesheet' href='../css/author.css'>
        <link rel='icon' type='image/x-icon' href='../pictures/favicon.ico'>
    </head>
    <body>

    <header class='header-main'>
        <div class='div-1'>
            <p><a href='admin_homepage.php'>My blog</a></p>
        </div>
        <div class='div-2'>
        </div>
        <div class='div-3'>
            <p><a href='admin_panel.php'>Admin</a></p>
        <p><a href='../signup_and_login_system/logout.php'>Log out</a></p>
        </div>
    </header>

    <div id='main_div'>
        <nav class='nav_admin_panel'>
            <div class='div_admin_panel'>
                <p><a href='manage_users.php'>Users</a></p>
                <p><a href='create_new_post.php'>Add new post</a></p>
                <p><a href='admin_manage_posts.php'>Posts</a></p>
                <p><a href='manage_comments.php'>Comments</a></p>
            </div>
        </nav>

        <main>

            <h2>Edit post</h2>";

    if ($error) {
        echo "<p style='color: red;'>$error</p>";
    } elseif ($success) {
        echo "<p style='color: green;'>$success</p>";
    }

    echo "
            <form method='POST' action='edit_post_script.php' enctype='multipart/form-data'>
                <input type='hidden' name='post_id' value='$post_id'>
                <input type='hidden' name='old_photo' value='$photo'>
                Title: <input type='text' name='title' value='$title'> <br>
                Content: <textarea name='content' rows='30'>$content</textarea> <br>
                Current Image: <img src='../uploads/$photo' style='max-height: 300px; max-width: 300px;' alt='Post Image'><br>
                New Image: <input type='file' name='photo'><br><br>
                
                <input type='submit' value='Edit'>
            </form>

        </main>
    </div>

    </body>
    </html>";
} else {
    echo "Post not found.";
}
$database->close();