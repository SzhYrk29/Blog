<?php

session_unset();

require_once ('../functions/functions_for_database.php');

$database = connectToDatabase();

$error = "";
$success = "";

function isUniqueUsername($database, $username) {
    $stmt = $database->prepare("SELECT COUNT(*) as count FROM Users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    return $result["count"] == 0;
}

function isUniqueEmail($database, $email) {
    $stmt = $database->prepare("SELECT COUNT(*) as count FROM Users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    return $result["count"] == 0;
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (!isUniqueUsername($database, $username)) {
        $error = "Username already exists!";
    } elseif (!isUniqueEmail($database, $email)) {
        $error = "Email already exists!";
    } else {
        $stmt = $database->prepare("INSERT INTO Users (username, email, password, role_ID) VALUES (?, ?, ?, 3)");
        if ($stmt === false) {
            $error = 'Prepare failed: ' . $database->error;
        } else {
            $stmt->bind_param("sss", $username, $email, $password);
            if ($stmt->execute()) {
                $success = "User registered successfully!";
            } else {
                $error = "Error: " . $stmt->error;
            }
            $stmt->close();
        }
    }
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
        <h1>Sign up</h1>
        <?php
        if ($error) {
            echo "<p style='color: red;'>$error</p>";
        } elseif ($success) {
            echo "<p style='color: green;'>$success</p>";
        }
        ?>
        <form action="signup.php" method="post">
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <span><button type="submit" name="submit">Sign up</button></span>
        </form>
    </div>
    <div class="main-div-3"></div>
</main>

</body>
</html>
