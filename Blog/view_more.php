<?php
session_start();

$default_role = 4;

if (isset($_SESSION['user_ID'])) {
    $user_role = $_SESSION['role_ID'];
} else {
    $user_role = $default_role;
}

$user_id = isset($_SESSION['user_ID']) ? $_SESSION['user_ID'] : null; // sprawdzenie, czy w sesji istnieje zmienna 'user_ID'

require_once('functions/functions_for_database.php');
$database = connectToDatabase();

if (isset($_GET['post_id'])) {
    $post_id = $_GET['post_id'];

    $sql_post = "SELECT * FROM Posts WHERE post_ID = ?";
    $stmt_post = $database->prepare($sql_post); // przygotowywanie zapytania SQL na obiekcie połączenia z bazą danych
    $stmt_post->bind_param("i", $post_id); // wiązanie parametru 'post_ID' jako liczbę całkowitą do zapytania
    $stmt_post->execute();
    $result_post = $stmt_post->get_result();

    if ($result_post->num_rows > 0) { //sprawdzenie, czy wynik zapytania do bazy danych zawiera co najmniej jeden wiersz
        $row_post = $result_post->fetch_assoc(); //pobieranie pierwszego wierszu jako tablice asocjacyjną
        $title = htmlspecialchars($row_post['title']); // 'htmlspecialchars' przekształca specjalne znaki HTML w encje HTML, aby zapobiec atakom typu XSS
        $content = nl2br(htmlspecialchars($row_post['content'])); // 'nl2br' zamienia wszystkie znaki nowej linii na <br>
        $publish_time = htmlspecialchars($row_post['publish_time']);
        $photo = htmlspecialchars($row_post['photo']);

        $sql_comments = "SELECT c.content, c.publish_time, 
                        IFNULL(u.username, c.username) AS username 
                        FROM Comments c 
                        LEFT JOIN Users u ON c.user_ID = u.user_ID 
                        WHERE c.post_ID = ? 
                        ORDER BY c.publish_time DESC";

        $stmt_comments = $database->prepare($sql_comments);
        $stmt_comments->bind_param("i", $post_id);
        $stmt_comments->execute();
        $result_comments = $stmt_comments->get_result();

        $success_message = isset($_GET['success']) ? htmlspecialchars($_GET['success']) : '';
        $error_message = isset($_GET['error']) ? htmlspecialchars($_GET['error']) : '';
        ?>

        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title><?php echo $title; ?></title>
            <link rel="stylesheet" href="../css/styles.css">
            <link rel='stylesheet' href='../css/show_posts.css'>
            <link rel='stylesheet' href='../css/view_more.css'>
            <link rel="icon" type="image/x-icon" href=".pictures/favicon.ico">
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

        <div id="main_div">
            <main>
                <article class="selected-post">
                    <h1><?php echo $title; ?></h1>
                    <p><?php echo $content; ?></p>
                    <?php if ($photo): ?>
                        <img src="../uploads/<?php echo $photo; ?>" alt="Post Image">
                    <?php endif; ?>
                    <p class="publish_date"><?php echo $publish_time; ?></p>
                </article>

                <section class="comments-section">
                    <h2 style="color: white;">Comments</h2>

                    <?php if ($success_message): ?>
                        <p style="color: green;"><?php echo $success_message; ?></p>
                    <?php endif; ?>

                    <?php if ($error_message): ?>
                        <p style="color: red;"><?php echo $error_message; ?></p>
                    <?php endif; ?>

                    <form method="post" action="add_comment.php">
                        <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
                        <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                        <textarea name="comment_content" placeholder="Write your comment here..." required></textarea>
                        <br>
                        <input type="submit" value="Add Comment">
                    </form>

                    <?php
                    if ($result_comments->num_rows > 0) {
                        while ($row_comment = $result_comments->fetch_assoc()) {
                            $comment_content = nl2br(htmlspecialchars($row_comment['content']));
                            $comment_publish_time = htmlspecialchars($row_comment['publish_time']);
                            $comment_username = htmlspecialchars($row_comment['username']);
                            ?>
                            <div class="comment">
                                <p style="color: #555555;"><strong style="color: white"><?php echo $comment_username; ?></strong> - <?php echo $comment_publish_time; ?></p>
                                <p style="color: #cccccc;"><?php echo $comment_content; ?></p>
                            </div>
                            <?php
                        }
                    } else {
                        echo "<p>No comments yet.</p>";
                    }
                    ?>
                </section>
            </main>
        </div>

        </body>
        </html>
        <?php
    } else {
        echo "<p>Post not found.</p>";
    }
} else {
    echo "<p>Post ID not specified.</p>";
}

$database->close();
?>
