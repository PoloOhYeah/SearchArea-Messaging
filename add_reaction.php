<?php
session_start();
require_once 'db_config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id']; // ID de l'utilisateur
    $message_id = $_POST['message_id'];
    $reaction = $_POST['reaction'];

    // Vérifiez si la réaction existe déjà pour ce message
    $check_stmt = $pdo->prepare('SELECT * FROM reactions WHERE message_id = :message_id AND user_id = :user_id');
    $check_stmt->execute(['message_id' => $message_id, 'user_id' => $user_id]);
    $existing_reaction = $check_stmt->fetch();

    if ($existing_reaction) {
        // Si une réaction existe déjà, mettez à jour
        $update_stmt = $pdo->prepare('UPDATE reactions SET reaction = :reaction WHERE message_id = :message_id AND user_id = :user_id');
        $update_stmt->execute(['reaction' => $reaction, 'message_id' => $message_id, 'user_id' => $user_id]);
    } else {
        // Sinon, insérez une nouvelle réaction
        $insert_stmt = $pdo->prepare('INSERT INTO reactions (user_id, message_id, reaction) VALUES (:user_id, :message_id, :reaction)');
        $insert_stmt->execute(['user_id' => $user_id, 'message_id' => $message_id, 'reaction' => $reaction]);
    }

    echo json_encode(['status' => 'success']);
}
?>
