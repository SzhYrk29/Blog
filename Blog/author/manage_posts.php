<?php

session_start();

$default_role = 4;

if (isset($_SESSION['role_ID'])) {
    $user_role = $_SESSION['role_ID'];
} else {
    $user_role = $default_role;
}

$user_id = isset($_SESSION['user_ID']) ? $_SESSION['user_ID'] : null;

if ($user_role !== 2) {
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
    <link rel="stylesheet" href="../css/manage_post.css">
    <link rel="icon" type="image/x-icon" href="../pictures/favicon.ico">
</head>
<body>

<?php
include_once ('author_header.php');
?>

<div id="main_div">
    <nav class="nav_admin_panel">
        <div class="div_admin_panel">
            <p><a href="create_new_post.php">Add new post</a></p>
            <p><a href="manage_posts.php">Manage posts</a></p>
        </div>
    </nav>

    <main>

        <h2>Manage posts</h2>

        <table id="users_table">
            <thead>
            <tr>
                <th>Post ID</th>
                <th>Title</th>
                <th>Content</th>
                <th>Photo</th>
                <th>Publish date</th>
                <th>Edit/Delete</th>
            </tr>
            </thead>
            <tbody>
            <?php
            require_once ('../functions/functions_for_database.php');
            $database = connectToDatabase();

            $posts = $database->query("SELECT * FROM Posts");
            while ($post = $posts->fetch_assoc()) {
                $photo = htmlspecialchars($post['photo'], ENT_QUOTES, 'UTF-8');
                $photoPath = "../uploads/" . $photo;

                echo "<tr>
                    <td>{$post['post_ID']}</td>
                    <td>{$post['title']}</td>
                    <td>{$post['content']}</td>
                    <td>";
                if (!empty($photo) && file_exists($photoPath)) {
                    echo "<img src='{$photoPath}' style='max-width: 150px; max-height: 150px;'>";
                } else {
                    echo "No image uploaded";
                }
                echo "</td>
                    <td>{$post['publish_time']}</td>
                    <td>
                        <form method='POST' action='edit_post_form.php' style='display: inline'>
                            <input type='hidden' name='post_id' value='{$post['post_ID']}'>
                            <input type='hidden' name='title' value='{$post['title']}'>
                            <input type='hidden' name='content' value='{$post['content']}'>
                            <input type='hidden' name='photo' value='{$photo}'>
                            <input type='hidden' name='publish_time' value='{$post['publish_time']}'>
                            <input type='submit' name='edit' value='Edit'>
                        </form>
                        <form method='POST' action='delete_post.php' style='display: inline' onsubmit=\"return confirm('Are you sure you want to delete this post?');\">
                            <input type='hidden' name='post_id' value='{$post['post_ID']}'>
                            <input type='submit' name='delete' value='Delete'>
                        </form>
                    </td>
                </tr>";
            }
            $database->close();
            ?>
            </tbody>
        </table>
    </main>
</div>

</body>
</html>
