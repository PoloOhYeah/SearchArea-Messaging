<?php
session_start();
require_once 'db_config.php';

$user_id = $_SESSION['user_id']; // ID de l'utilisateur connecté

$stmt = $pdo->prepare('SELECT pm.message, pm.sent_at, u.username AS receiver 
                       FROM private_messages pm 
                       JOIN users u ON pm.receiver_id = u.id 
                       WHERE pm.sender_id = :user_id 
                       ORDER BY pm.sent_at DESC');
$stmt->execute(['user_id' => $user_id]);
$messages = $stmt->fetchAll();

if ($messages) {
    foreach ($messages as $msg) {
        echo "<p><strong>À {$msg['receiver']}:</strong> {$msg['message']} <em>({$msg['sent_at']})</em></p>";
    }
} else {
    echo "Aucun message envoyé.";
}
?>
