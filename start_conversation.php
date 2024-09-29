<?php
session_start();
require_once 'db_config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $receiver_id = (int)$_POST['receiver_id'];
    $sender_id = $_SESSION['user_id'];

    // Vérifier si l'utilisateur existe
    $stmt = $pdo->prepare('SELECT id FROM users WHERE id = ?');
    $stmt->execute([$receiver_id]);
    $user = $stmt->fetch();

    if ($user) {
        // Rediriger vers la page de conversation si l'utilisateur existe
        header("Location: conversation.php?id={$receiver_id}");
        exit;
    } else {
        echo "Utilisateur non trouvé.";
    }
}
