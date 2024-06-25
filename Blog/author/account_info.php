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

require_once ('../functions/functions_for_database.php');
$database = connectToDatabase();

$sql = "SELECT * FROM Users WHERE user_ID = '$user_id'";
$result = $database->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    $username = $row['username'];
    $email = $row['email'];
    $password = $row['password'];
    $role_id = $row['role_ID'];

    $sql_role = "SELECT role FROM Roles WHERE role_ID = '$role_id'";
    $result_role = $database->query($sql_role);

    if ($result_role->num_rows > 0) {
        $role_row = $result_role->fetch_assoc();
        $role = $role_row['role'];
    } else {
        $role = 'Unknown';
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Blog</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/account_info.css">
    <link rel="icon" type="image/x-icon" href="../pictures/favicon.ico">
</head>
<body>

<?php
include_once ('author_header.php');
?>

<div id="main_div">
    <nav class="nav_admin_panel">
        <div class="div_admin_panel">
            <p><a href="account_info.php">Account info</a></p>
            <p><a href="edit_account_form.php">Edit account</a></p>
            <p><a href="manage_comments.php">My comments</a></p>
        </div>
    </nav>

    <main>
        <h1>Account info:</h1>
        <p>Username: <?php echo htmlspecialchars($username); ?> </p>
        <p>Email: <?php echo htmlspecialchars($email); ?> </p>
        <p>Password: <?php echo htmlspecialchars($password); ?> </p>
        <p>Role: <?php echo htmlspecialchars($role); ?> </p>
    </main>
</div>

</body>
</html>

<?php
$database->close();
?>
