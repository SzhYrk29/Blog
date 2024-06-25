<?php

session_start();

$default_role = 4;

if (isset($_SESSION['role_ID'])) {
    $user_role = $_SESSION['role_ID'];
} else {
    $user_role = $default_role;
}

$user_id = isset($_SESSION['user_ID']) ? $_SESSION['user_ID'] : null;

if ($user_role !== 3) {
    echo "You do not have sufficient rights to access this page. <br>";
    echo "Go to <a href='../signup_and_login_system/login.php'>login page</a>.";
    exit();
}

require_once('../functions/functions_for_database.php');
$database = connectToDatabase();

// przypisywanie wartośći zmiennej $comment_id na podstawie wartości przekazanych metodą POST lub GET
$comment_id = isset($_POST['comment_id']) ? $_POST['comment_id'] : (isset($_GET['comment_id']) ? $_GET['comment_id'] : null);

if ($comment_id === null) {
    die("Comment ID is not specified.");
}

$sql = "SELECT * FROM Comments WHERE comment_ID = ?";
$stmt = $database->prepare($sql);
$stmt->bind_param("i", $comment_id);
$stmt->execute();
$result = $stmt->get_result();

$error = isset($_GET['error']) ? htmlspecialchars($_GET['error']) : "";
$success = isset($_GET['success']) ? htmlspecialchars($_GET['success']) : "";

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $content = htmlspecialchars($row['content'], ENT_QUOTES, 'UTF-8');
    /*
     parametr ENT_QUOTES mówi funkcji, aby zamieniała zarówno cudzysłowy (") jak i apostrofy (') na encje HTML
     parametr 'UTF-8' określa kodowanie tekstu, w którym jest przetwarzany
     */
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Edit Comment</title>
        <link rel="stylesheet" href="../css/styles.css">
        <link rel='stylesheet' href='../css/author.css'>
        <link rel="icon" type="image/x-icon" href="../pictures/favicon.ico">
    </head>
    <body>

    <?php include_once('user_header.php'); ?>

    <div id="main_div">
        <nav class="nav_admin_panel">
            <div class="div_admin_panel">
                <p><a href="account_info.php">Account info</a></p>
                <p><a href="edit_account_form.php">Edit account</a></p>
                <p><a href="delete_account.php">Delete account</a></p>
                <p><a href="manage_comments.php">My comments</a></p>
            </div>
        </nav>

        <main>
            <h2>Edit Comment</h2>
            <?php if ($error): ?>
                <p style="color: red;"><?php echo $error; ?></p>
            <?php elseif ($success): ?>
                <p style="color: green;"><?php echo $success; ?></p>
            <?php endif; ?>

            <form method="POST" action="edit_comment_script.php">
                <input type="hidden" name="comment_id" value="<?php echo $comment_id; ?>">
                Type new comment: <textarea name="content" rows="10" cols="50"><?php echo $content; ?></textarea> <br><br>
                <input type="submit" value="Edit">
            </form>
        </main>
    </div>

    </body>
    </html>
    <?php
} else {
    echo "Comment not found.";
}

$stmt->close();
$database->close();
?>
