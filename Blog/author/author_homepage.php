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

require_once('../functions/functions_for_database.php');
$database = connectToDatabase();

$sql = "SELECT * FROM Posts ORDER BY publish_time DESC";
$result = $database->query($sql);

$error = isset($_GET['error']) ? $_GET['error'] : "";
$success = isset($_GET['success']) ? $_GET['success'] : "";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Blog</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel='stylesheet' href='../css/show_posts.css'>
    <link rel="icon" type="image/x-icon" href="../pictures/favicon.ico">
</head>
<body>

<?php
include_once ('author_header.php');
?>

<div id='main_div'>
    <main>
        <h1>All Posts</h1>
        <?php
        if ($error) {
            echo "<p style='color: red;'>$error</p>";
        } elseif ($success) {
            echo "<p style='color: green;'>$success</p>";
        }

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<div class='post'>";
                echo "<h2>" . htmlspecialchars($row['title']) . "</h2>";
                echo "<p>" . nl2br(htmlspecialchars($row['content'])) . "</p>";
                if ($row['photo']) {
                    echo "<img src='../uploads/" . htmlspecialchars($row['photo']) . "' alt='Post Image'>";
                }
                echo "<p class='publish_date'>" . htmlspecialchars($row['publish_time']) . "</p>";
                echo "<form method='GET' action='view_more.php'>";
                echo "<input type='hidden' name='post_id' value='" . $row['post_ID'] . "'>";
                echo "<input type='submit' value='View more' class='view_more_button'>";
                echo "</form>";
                echo "</div>";
            }
        } else {
            echo "<p>No posts found.</p>";
        }

        $database->close();
        ?>
    </main>
</div>
</body>
</html>

