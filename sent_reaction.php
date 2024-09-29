<?php
session_start();
require_once 'db_config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $message_id = $_POST['message_id'];
    $reaction = $_POST['reaction'];

    // Vérifiez que le message existe
    $stmt = $pdo->prepare('SELECT * FROM private_messages WHERE id = :id');
    $stmt->execute(['id' => $message_id]);
    $message = $stmt->fetch();

    if ($message) {
        // Ajoutez la réaction
        $insert_stmt = $pdo->prepare('INSERT INTO reactions (user_id, message_id, reaction) VALUES (:user_id, :message_id, :reaction)');
        $insert_stmt->execute([
            'user_id' => $user_id,
            'message_id' => $message_id,
            'reaction' => $reaction,
        ]);
        echo "Réaction envoyée avec succès.";
    } else {
        echo "Message non trouvé.";
    }
}
