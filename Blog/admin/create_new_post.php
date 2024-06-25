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

$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once '../functions/functions_for_database.php';
    $database = connectToDatabase();

    $title = $_POST['title'];
    $content = $_POST['content'];
    $publish_time = date('Y-m-d H:i:s');

    $photo = "";
    if (!empty($_FILES['photo']['tmp_name'])) {
        $photo = basename($_FILES['photo']['name']); // funkcja basename() zwraca bazową nazwę pliku, usuwając dowolne ścieżki lub lokalizacje pliku
        $target_directory = "../uploads/";
        $target_file = $target_directory . $photo;

        if (move_uploaded_file($_FILES['photo']['tmp_name'], $target_file)) {
        } else {
            $error = "Error uploading photo.";
        }
    }

    if (empty($error)) {
        $stmt = $database->prepare("INSERT INTO Posts (title, content, photo, publish_time) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $title, $content, $photo, $publish_time);

        if ($stmt->execute()) {
            $success = "Post added successfully.";
        } else {
            $error = "Error: " . $stmt->error;
        }
        $stmt->close();
    }

    $database->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Blog</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/author.css">
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

        <h2>New post</h2>

        <form method="POST" action="create_new_post.php" enctype="multipart/form-data">
            <input type="text" name="title" placeholder="Title" required>
            <textarea name="content" rows="30" placeholder="Type some text..." required></textarea>
            <input type="file" name="photo"><br><br>
            <?php
            if ($error) {
                echo "<p style='color: red; '>$error</p>";
            } elseif ($success) {
                echo "<p style='color: green;'>$success</p>";
            }
            ?>
            <input type="submit" name="new_post" value="Add new post">
        </form>

    </main>
</div>

</body>
</html>
