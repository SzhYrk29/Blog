<?php

session_start();

$default_role = 4;

if (isset($_SESSION['role_ID'])) {
    $user_role = $_SESSION['role_ID'];
} else {
    $user_role = $default_role;
}

$user_ID = isset($_SESSION['user_ID']) ? $_SESSION['user_ID'] : null;

if ($user_role !== 2) {
    echo "You do not have sufficient rights to access this page. <br>";
    echo "Go to <a href='../signup_and_login_system/login.php'>login page</a>.";
    exit();
}


require_once ('../functions/functions_for_database.php');
$database = connectToDatabase();

$sql = "SELECT * FROM Users WHERE user_ID = '$user_ID'";
$result = $database->query($sql);

$error = isset($_GET['error']) ? $_GET['error'] : "";
$success = isset($_GET['success']) ? $_GET['success'] : "";

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    $username = $row['username'];
    $email = $row['email'];
    $password = $row['password'];
}
?>

<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <title>Blog</title>
    <link rel='stylesheet' href='../css/styles.css'>
    <link rel='stylesheet' href='../css/edit_account.css'>
    <link rel='icon' type='image/x-icon' href='../pictures/favicon.ico'>
</head>
<body>

<?php
include_once ('author_header.php');
?>

<div id='main_div'>
    <nav class='nav_admin_panel'>
        <div class='div_admin_panel'>
            <p><a href="account_info.php">Account info</a></p>
            <p><a href="edit_account_form.php">Edit account</a></p>
            <p><a href="manage_comments.php">My comments</a></p>
        </div>
    </nav>

    <main>
        <h2>Edit account</h2>

        <form action='edit_account_script.php' method='post'>
            User ID: <?php echo $user_ID; ?> <br><br>
            <input type='hidden' name='user_id' value='<?php echo $user_ID; ?>'>
            Username: <input type='text' name='username' value='<?php echo $username; ?>'> <br>
            Email: <input type='email' name='email' value='<?php echo $email; ?>'> <br>
            Password: <input type='password' name='password' value='<?php echo $password; ?>'> <br>

            <?php
            if (!empty($error)) {
                echo "<p style='color: red;'>$error</p>";
            } elseif (!empty($success)) {
                echo "<p style='color: green;'>$success</p>";
            }
            ?>

            <input type='submit' value='Edit'>
        </form>

    </main>
</div>
</body>
</html>

<?php
$database->close();
?>
