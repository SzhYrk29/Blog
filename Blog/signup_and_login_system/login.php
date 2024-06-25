<?php
session_start();

require_once '../functions/functions_for_database.php';
$database = connectToDatabase();

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    $stmt = $database->prepare("SELECT user_ID, role_ID FROM Users WHERE username = ? AND email = ? AND password = ?");
    if ($stmt === false) {
        die('Prepare failed: ' . $database->error);
    }

    $stmt->bind_param("sss", $username, $email, $password); // wiązanie parametrów zastępcze w przygotowanej instrukcji

    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        $_SESSION['user_ID'] = $user['user_ID'];
        $_SESSION['role_ID'] = $user['role_ID'];
        header('Location: logged_in.php');
        exit();
    } else {
        $error = "Invalid username, email or password";
    }

    $stmt->close();
}

$database->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Blog</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="icon" type="image/x-icon" href="../pictures/favicon.ico">
</head>
<body>

<header class="header-main">
    <div class="div-1">
        <p><a href="../homepage.php">My blog</a></p>
    </div>
    <div class="div-2">
        <p><a href="../contact.html">Contact me</a></p>
    </div>
    <div class="div-3">
        <p><a href="signup.php">Sign up</a></p>
        <p><a href="login.php">Log in</a></p>
    </div>
</header>

<main class="main-div">
    <div class="main-div-1"></div>
    <div class="main-div-2">
        <h1>Log in</h1>
        <?php
        if ($error) {
            echo "<p style='color: red;'>$error</p>";
        }
        ?>
        <form action="login.php" method="post">
            <input type="text" name="username" placeholder="Username">
            <input type="email" name="email" placeholder="Email">
            <input type="password" name="password" placeholder="Password">
            <span><button type="submit" name="submit">Log in</button></span>
        </form>
    </div>
    <div class="main-div-3"></div>
</main>

</body>
</html>