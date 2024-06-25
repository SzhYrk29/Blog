<?php
session_start();

require_once ('../functions/functions_for_database.php');
$database = connectToDatabase();

$user_id = $_POST['user_id'];
$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];

$error = '';
if (empty($username) || empty($email) || empty($password)) {
    $error = 'All fields are required.';
}

if (empty($error)) {
    $sql = "UPDATE Users SET username = ?, email = ?, password = ? WHERE user_ID = ?";
    $stmt = $database->prepare($sql);
    $stmt->bind_param('sssi', $username, $email, $password, $user_id);

    if ($stmt->execute()) {
        $success = 'Account updated successfully';
        header("Location: edit_account_form.php?success=" . urlencode($success));
    } else {
        $error = 'Failed to update account. Please try again.';
        header("Location: edit_account_form.php?error=" . urlencode($error));
    }

    $stmt->close();
} else {
    header("Location: edit_account_form.php?error=" . urlencode($error));
}

$database->close();
?>
