<?php
require_once ('../functions/functions_for_database.php');
$database = connectToDatabase();

$post_id = $_POST['post_id'];
$title = $_POST['title'];
$content = $_POST['content'];
$old_photo = $_POST['old_photo'];

if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
    // sprawdzenie, czy zmienna $_FILES['photo'] istnieje i czy nie wystąpiły żadne błędy podczas przesyłania pliku
    // UPLOAD_ERR_OK oznacza, że nie wystąpiły żadne błędy podczas przesyłania
    $photo = $_FILES['photo']['name'];
    $target_dir = "../uploads/";
    $target_file = $target_dir . basename($photo); // funkcja basename() jest używana do uzyskania tylko nazwy pliku z pełnej ścieżki

    if (move_uploaded_file($_FILES['photo']['tmp_name'], $target_file)) {
    } else {
        $error = "Error uploading the file.";
        header("Location: edit_post_form.php?post_id=$post_id&error=" . urlencode($error));
        exit();
    }
} else {
    $photo = $old_photo;
}

$sql = "UPDATE Posts SET title=?, content=?, photo=? WHERE post_ID=?";
$stmt = $database->prepare($sql);

$stmt->bind_param("sssi", $title, $content, $photo, $post_id);

if ($stmt->execute()) {
    $success = "Post edited successfully.";
    header("Location: edit_post_form.php?post_id=$post_id&success=" . urlencode($success));
} else {
    $error = "Error editing post: " . $stmt->error;
    header("Location: edit_post_form.php?post_id=$post_id&error=" . urlencode($error));
}

$stmt->close();
$database->close();