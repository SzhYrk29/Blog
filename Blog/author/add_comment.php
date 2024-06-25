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

$post_id = $_POST['post_id'];

require_once('../functions/functions_for_database.php');
$database = connectToDatabase();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $comment_content = $_POST['comment_content'];
    $user_id = $_SESSION['user_ID'];

    $sql_insert = "INSERT INTO Comments (content, publish_time, user_ID, post_ID) VALUES (?, NOW(), ?, ?)";
    $stmt_insert = $database->prepare($sql_insert);
    $stmt_insert->bind_param("sii", $comment_content, $user_id, $post_id);

    if ($stmt_insert->execute()) {
        header("Location: view_more.php?post_id=$post_id&success=Comment added successfully");
    } else {
        $error_message = $stmt_insert->error;
        header("Location: view_more.php?post_id=$post_id&error=Error adding comment: " . urlencode($error_message));
    }

    $stmt_insert->close();
} else {
    header("Location: view_more.php?post_id=$post_id&error=Invalid request method");
}

$database->close();
?>
