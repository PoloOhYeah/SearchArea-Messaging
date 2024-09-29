<?php
session_start();
require_once 'db_config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Récupérer toutes les conversations de l'utilisateur
$stmt = $pdo->prepare('SELECT DISTINCT 
                            CASE WHEN sender_id = :user_id THEN receiver_id ELSE sender_id END AS other_user_id,
                            u.username AS other_user
                        FROM private_messages pm
                        JOIN users u ON (u.id = pm.sender_id OR u.id = pm.receiver_id)
                        WHERE pm.sender_id = :user_id OR pm.receiver_id = :user_id
                        ORDER BY pm.sent_at DESC');

$stmt->execute(['user_id' => $user_id]);
$conversations = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messagerie</title>
    <link rel="stylesheet" href="https://searcharea.ddns.net/styles.css">
    <style>
        /* Style simple pour la page de messagerie */
        .messaging-wrapper {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        .conversation {
            border-bottom: 1px solid #eee;
            padding: 15px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .conversation:last-child {
            border-bottom: none;
        }

        .conversation a {
            font-weight: bold;
            color: #007BFF;
            text-decoration: none;
        }

        .conversation a:hover {
            text-decoration: underline;
        }

        .new-conversation {
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="messaging-wrapper">
    <h1><img src="logomsg.png"></h1>

    <?php if ($conversations): ?>
        <?php foreach ($conversations as $conv): ?>
            <div class="conversation">
                <a href="conversation.php?id=<?php echo $conv['other_user_id']; ?>">
                    <?php echo htmlspecialchars($conv['other_user']); ?>
                </a>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Aucune conversation à afficher.</p>
    <?php endif; ?>

    <div class="new-conversation">
    <h2>Démarrer une nouvelle conversation</h2>
    <form action="start_conversation.php" method="POST">
        <input type="number" name="receiver_id" placeholder="ID de l'utilisateur" required>
        <button type="submit">Démarrer</button>
    </form>
    <br>
    <center><a href="https://searcharea.ddns.net/contactpriv/index.php">Porter plainte contre un message</a> / <a href="helpformessaging.html">Centre d'aide</a> / <a href="profile.php">Votre profil</a></center>

</div>

</div>

</body>
</html>
