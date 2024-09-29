<?php
session_start();
require_once 'db_config.php';

$user_id = $_SESSION['user_id'];
$conversation_id = isset($_GET['id']) ? (int)$_GET['id'] : 0; // ID de la conversation

$stmt = $pdo->prepare('
    SELECT pm.message, pm.sent_at, u.username AS sender, u.id AS sender_id 
    FROM private_messages pm 
    JOIN users u ON pm.sender_id = u.id 
    WHERE (pm.sender_id = :user_id OR pm.receiver_id = :user_id)
    AND (pm.sender_id = :conversation_id OR pm.receiver_id = :conversation_id)
    ORDER BY pm.sent_at DESC
');
$stmt->execute(['user_id' => $user_id, 'conversation_id' => $conversation_id]);
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($messages);
