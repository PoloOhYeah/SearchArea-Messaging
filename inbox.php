<?php
session_start();
require_once 'db_config.php';

$user_id = $_SESSION['user_id']; // ID de l'utilisateur connecté

// Récupération des messages et des réactions associées
$stmt = $pdo->prepare('SELECT pm.id, pm.message, pm.sent_at, u.username AS sender, u.id AS sender_id 
                       FROM private_messages pm 
                       JOIN users u ON pm.sender_id = u.id 
                       WHERE pm.receiver_id = :user_id 
                       ORDER BY pm.sent_at DESC');
$stmt->execute(['user_id' => $user_id]);
$messages = $stmt->fetchAll();

// Récupération des réactions pour chaque message
$reactions_stmt = $pdo->prepare('SELECT emoji, COUNT(*) as total 
                                 FROM reactions 
                                 WHERE message_id = :message_id 
                                 GROUP BY emoji');

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boîte de Réception</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .inbox-wrapper {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        .message {
            border-bottom: 1px solid #eee;
            padding: 15px 0;
            display: flex;
            flex-direction: column;
        }

        .sender {
            font-weight: bold;
            color: #007BFF;
        }

        .msg-content {
            margin-top: 5px;
        }

        .msg-time {
            font-size: 0.9em;
            color: #999;
        }

        .reaction-menu {
            margin-top: 10px;
            display: inline-block;
            position: relative;
        }

        .reaction-menu select {
            padding: 5px;
            font-size: 1em;
        }

        .reaction-display {
            margin-top: 5px;
            display: flex;
            align-items: center;
        }

        .emoji {
            font-size: 1.5em;
            margin-right: 10px;
        }

        .reaction-btn {
            background-color: transparent;
            border: none;
            cursor: pointer;
            font-size: 1.2em;
        }

        .reaction-count {
            font-size: 0.9em;
            color: #999;
            margin-left: 5px;
        }

        .reply-section {
            margin-top: 15px;
            display: flex;
        }

        .reply-section textarea {
            flex-grow: 1;
            margin-right: 10px;
        }

        .reply-btn {
            padding: 5px 10px;
            background-color: #28a745;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .reply-btn:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

<div class="inbox-wrapper">
    <h1>Boîte de Réception</h1>

    <?php if ($messages): ?>
        <?php foreach ($messages as $msg): ?>
            <div class="message">
                <div>
                    <span class="sender"><?php echo htmlspecialchars($msg['sender']); ?>:</span>
                    <span class="msg-content"><?php echo htmlspecialchars($msg['message']); ?></span>
                    <span class="msg-time"><?php echo htmlspecialchars($msg['sent_at']); ?></span>
                </div>

                <!-- Affichage des réactions -->
                <div class="reaction-display">
                    <?php
                    $reactions_stmt->execute(['message_id' => $msg['id']]);
                    $reactions = $reactions_stmt->fetchAll();
                    foreach ($reactions as $reaction):
                    ?>
                        <span class="emoji"><?php echo htmlspecialchars($reaction['emoji']); ?> <span class="reaction-count"><?php echo $reaction['total']; ?></span></span>
                    <?php endforeach; ?>
                </div>

                <!-- Menu déroulant pour ajouter des réactions -->
                <div class="reaction-menu">
                    <form action="add_reaction.php" method="POST">
                        <input type="hidden" name="message_id" value="<?php echo $msg['id']; ?>">
                        <label for="emoji-select-<?php echo $msg['id']; ?>">Réagir :</label>
                        <select name="emoji" id="emoji-select-<?php echo $msg['id']; ?>">
                            <option value="👍">👍</option>
                            <option value="❤️">❤️</option>
                            <option value="😂">😂</option>
                            <option value="😢">😢</option>
                            <option value="😡">😡</option>
                            <option value="👏">👏</option>
                        </select>
                        <button type="submit" class="reaction-btn">Ajouter</button>
                    </form>
                </div>

                <!-- Réponse au message -->
                <div class="reply-section">
                    <form action="send_message.php" method="POST">
                        <input type="hidden" name="receiver_id" value="<?php echo $msg['sender_id']; ?>">
                        <textarea name="message" placeholder="Écris ta réponse ici"></textarea>
                        <button type="submit" class="reply-btn">Répondre</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Aucun message reçu.</p>
    <?php endif; ?>
</div>

</body>
</html>


