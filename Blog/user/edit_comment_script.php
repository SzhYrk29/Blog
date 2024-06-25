<?php
require_once('../functions/functions_for_database.php');
$database = connectToDatabase();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $comment_id = isset($_POST['comment_id']) ? $_POST['comment_id'] : null;
    $content = isset($_POST['content']) ? $_POST['content'] : null;

    if ($comment_id === null || $content === null) {
        die("Comment ID or content is not specified.");
    }

    $sql = "UPDATE Comments SET content = ? WHERE comment_ID = ?";
    $stmt = $database->prepare($sql);
    $stmt->bind_param("si", $content, $comment_id);

    if ($stmt->execute()) {
        $success = "Comment updated successfully.";
        header("Location: edit_comment_form.php?comment_id=$comment_id&success=" . urlencode($success));
    } else {
        $error = "Error updating comment.";
        header("Location: edit_comment_form.php?comment_id=$comment_id&error=" . urlencode($error));
    }

    $stmt->close();
} else {
    $error = "Invalid request method.";
    header("Location: edit_comment_form.php?error=" . urlencode($error));
}

$database->close();
?>
