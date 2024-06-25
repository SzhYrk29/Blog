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
    <link rel="stylesheet" href="../css/manage_comments_admin.css">
    <link rel="icon" type="image/x-icon" href="../pictures/favicon.ico">
</head>
<body>

<?php
include_once ('admin_header.php');
?>

<div id="main_div">
    <nav class="nav_admin_panel">
        <div class="div_admin_panel">
            <p><a href="manage_users.php">Users</a></p>
            <p><a href="create_new_post.php">Add new post</a></p>
            <p><a href="admin_manage_posts.php">Posts</a></p>
            <p><a href="manage_comments.php">Comments</a></p>
        </div>
    </nav>

    <main>

        <h2>Manage comments</h2>

        <table id="users_table">
            <thead>
            <tr>
                <th>Comment ID</th>
                <th>Content</th>
                <th>Publish time</th>
                <th>User ID</th>
                <th>Post ID</th>
                <th>Guest</th>
                <th>Delete</th>
            </tr>
            </thead>
            <tbody>
            <?php
            require_once ('../functions/functions_for_database.php');
            $database = connectToDatabase();

            $comments = $database->query("SELECT * FROM Comments");
            while ($comment = $comments->fetch_assoc()) {
                echo "<tr>
            <td>{$comment['comment_ID']}</td>
            <td>{$comment['content']}</td>
            <td>{$comment['publish_time']}</td>
            <td>{$comment['user_ID']}</td>
            <td>{$comment['post_ID']}</td>
            <td>{$comment['username']}</td>
            <td>
            <form method='POST' action='delete_comment.php' style='display: inline' onsubmit=\"return confirm('Are you sure you want to delete this user?');\">
                <input type='hidden' name='comment_id' value='{$comment['comment_ID']}'>
                <input type=submit name='delete' value='Delete'>
            </form>
            </td>
            </tr>";
            }
            ?>
            </tbody>
        </table>

    </main>
</div>

</body>
</html>