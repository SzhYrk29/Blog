<?php
require_once ('../functions/functions_for_database.php');
$database = connectToDatabase();

$post_id = $_POST['post_id'];
$title = $_POST['title'];
$content = $_POST['content'];

if (isset($_FILES['photo']) && $_FILES['photo']['error'] == UPLOAD_ERR_OK) {
    // sprawdzenie, czy zmienna $_FILES['photo'] istnieje i czy nie wystąpiły żadne błędy podczas przesyłania pliku
    // UPLOAD_ERR_OK oznacza, że nie wystąpiły żadne błędy podczas przesyłania
    $photo = $_FILES['photo']['name'];
    $target_dir = "../uploads/";
    $target_file = $target_dir . basename($photo); // funkcja basename() jest używana do uzyskania tylko nazwy pliku z pełnej ścieżki

    if (!move_uploaded_file($_FILES['photo']['tmp_name'], $target_file)) {
        // jeśli nie udało się przenieść przesłanego pliku do docelowej lokalizacji
        $error = "There was an error uploading the file.";
        header("Location: edit_post_form.php?post_id=$post_id&error=" . urlencode($error));
        exit();
    }

    // jeśli ustawiona jest zmienna $photo (czyli przesyłany jest nowy obrazek)
    $stmt = $database->prepare("UPDATE Posts SET title = ?, content = ?, photo = ? WHERE post_ID = ?");
    $stmt->bind_param("sssi", $title, $content, $photo, $post_id);
} else {
    // jeśli nie przesyłano nowego obrazka, aktualizujemy tylko tytuł i treść posta
    $stmt = $database->prepare("UPDATE Posts SET title = ?, content = ? WHERE post_ID = ?");
    $stmt->bind_param("ssi", $title, $content, $post_id);
}

if ($stmt->execute()) {
    $success = "Post updated successfully.";
    header("Location: edit_post_form.php?post_id=$post_id&success=" . urlencode($success));
} else {
    $error = "Error updating post: " . $stmt->error;
    header("Location: edit_post_form.php?post_id=$post_id&error=" . urlencode($error));
}

$stmt->close();
$database->close();
?>
