<?php

session_start();
session_unset();
session_destroy();
session_start();

$default_role = 4;

if (isset($_SESSION['user_ID'])) {
    $user_role = $_SESSION['role_ID'];
} else {
    $user_role = $default_role;
}

require_once('functions/functions_for_database.php');
$database = connectToDatabase();

$sql = "SELECT * FROM Posts ORDER BY publish_time DESC";
$result = $database->query($sql); // wywołanie metody 'query' na obiekcie '$database'

// sprawdzenie, czy w adresie URL znajdują się parametry 'error' i 'success', przypisywanie odpowiedni wartości do zmiennych '$error' i '$success'
$error = isset($_GET['error']) ? $_GET['error'] : "";
$success = isset($_GET['success']) ? $_GET['success'] : "";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Blog</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel='stylesheet' href='css/show_posts.css'>
    <link rel="icon" type="image/x-icon" href="pictures/favicon.ico">
</head>
<body>

<header class="header-main">
    <div class="div-1">
        <p><a href="homepage.php">My blog</a></p>
    </div>
    <div class="div-2">
        <p><a href="contact.html">Contact me</a></p>
    </div>
    <div class="div-3">
        <p><a href="signup_and_login_system/signup.php">Sign up</a></p>
        <p><a href="signup_and_login_system/login.php">Log in</a></p>
    </div>
</header>

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
            while($row = $result->fetch_assoc()) { // pobieranie następnego wierszu z zestawu wyników jako asocjacyjna tablica
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
