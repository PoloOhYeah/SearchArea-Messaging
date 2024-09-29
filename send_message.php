<?php
session_start();
require_once 'db_config.php';

$user_id = $_SESSION['user_id'];
$receiver_id = $_POST['receiver_id'];
$message = $_POST['message'];
$media_path = null;

// Gérer l'upload de fichiers
if (isset($_FILES['media']) && $_FILES['media']['error'] === UPLOAD_ERR_OK) {
    $media_tmp_name = $_FILES['media']['tmp_name'];
    $media_name = $_FILES['media']['name'];
    $media_size = $_FILES['media']['size'];
    $media_ext = pathinfo($media_name, PATHINFO_EXTENSION);
    
    // Vérification de la taille (limite de 10 Mo)
    if ($media_size > 10 * 1024 * 1024) {
        echo "Le fichier dépasse la taille limite de 10 Mo.";
        exit;
    }

    // Vérification du type de fichier (image ou vidéo)
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'mp4'];
    if (!in_array(strtolower($media_ext), $allowed_extensions)) {
        echo "Type de fichier non autorisé.";
        exit;
    }

    // Déplacement du fichier uploadé
    $media_path = 'uploads/' . uniqid() . '.' . $media_ext;
    move_uploaded_file($media_tmp_name, $media_path);
}

// Insertion du message dans la base de données
$stmt = $pdo->prepare("INSERT INTO private_messages (sender_id, receiver_id, message, media_path) VALUES (:sender_id, :receiver_id, :message, :media_path)");
$stmt->execute([
    'sender_id' => $user_id,
    'receiver_id' => $receiver_id,
    'message' => $message,
    'media_path' => $media_path
]);

header('Location: conversation.php?id=' . $receiver_id);
exit;
