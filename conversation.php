<?php
session_start();
require_once 'db_config.php';

$user_id = $_SESSION['user_id']; // ID de l'utilisateur connecté
$username = isset($_SESSION['username']) ? $_SESSION['username'] : ''; // Vérifier si le nom d'utilisateur est défini
$receiver_id = isset($_GET['id']) ? (int)$_GET['id'] : 0; // ID du destinataire

// Vérifiez que l'utilisateur est valide
$stmt = $pdo->prepare('SELECT * FROM users WHERE id = :id');
$stmt->execute(['id' => $receiver_id]);
$receiver = $stmt->fetch();

if (!$receiver) {
    echo "Utilisateur non trouvé.";
    exit;
}

// Récupérer les messages
$messages_stmt = $pdo->prepare('SELECT pm.id AS message_id, pm.message, pm.sent_at, pm.media_path, u.username AS sender
                                 FROM private_messages pm
                                 JOIN users u ON pm.sender_id = u.id
                                 WHERE (pm.sender_id = :user_id AND pm.receiver_id = :receiver_id) OR 
                                       (pm.sender_id = :receiver_id AND pm.receiver_id = :user_id)
                                 ORDER BY pm.sent_at ASC');
$messages_stmt->execute(['user_id' => $user_id, 'receiver_id' => $receiver_id]);
$messages = $messages_stmt->fetchAll();

// Vérification des messages
if (!$messages) {
    echo "Aucun message trouvé. Vous pouvez démarrer une nouvelle conversation.";
}

// Récupération des réactions
$reactions_stmt = $pdo->prepare('SELECT message_id, GROUP_CONCAT(reaction SEPARATOR ", ") AS reactions 
                                  FROM reactions 
                                  WHERE message_id IN (SELECT id FROM private_messages WHERE (sender_id = :user_id AND receiver_id = :receiver_id) OR (sender_id = :receiver_id AND receiver_id = :user_id))
                                  GROUP BY message_id');
$reactions_stmt->execute(['user_id' => $user_id, 'receiver_id' => $receiver_id]);
$reactions = $reactions_stmt->fetchAll(PDO::FETCH_KEY_PAIR); // Utilise un tableau associatif

// Affichage des messages
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conversation avec <?php echo htmlspecialchars($receiver['username']); ?></title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }


        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        .conversation-wrapper {
    max-width: 800px;        /* Largeur maximale du conteneur */
    margin: 50px auto;      /* Centrer le conteneur */
    padding: 20px;          /* Espacement interne */
    background-color: #fff; /* Couleur de fond blanche */
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Ombre légère */
    border-radius: 10px;    /* Coins arrondis */
}

.message {
    border-bottom: 1px solid #eee; /* Ligne séparatrice entre les messages */
    padding: 10px;          /* Espacement interne pour les messages */
    display: flex;          /* Utilisation de flexbox pour l'alignement */
    flex-direction: column; /* Alignement vertical des éléments */
    margin-bottom: 10px;    /* Marge en bas des messages */
}


        .message.sent {
            align-self: flex-end;
            background-color: #d1e7dd;
            border-radius: 10px;
            padding: 10px;
            max-width: 75%;
        }

        .message.received {
            align-self: flex-start;
            background-color: #f8d7da;
            border-radius: 10px;
            padding: 10px;
            max-width: 75%;
        }

        .reactions {
            margin-top: 5px;
            font-size: 0.9em;
            color: #555;
        }

        .reaction-button {
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-left: 5px;
            padding: 5px 10px;
            font-size: 0.9em;
        }

        .reaction-dropdown {
            display: none;
            position: absolute;
            background-color: white;
            border: 1px solid #ccc;
            z-index: 1;
        }

        .reaction-dropdown button {
            display: block;
            width: 100%;
            border: none;
            background: none;
            text-align: left;
            padding: 8px;
            cursor: pointer;
        }

        .reaction-dropdown button:hover {
            background-color: #f0f0f0;
        }

        form {
            display: flex;
            flex-direction: column;
            margin-top: 20px;
        }

        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            resize: none;
            margin-bottom: 10px;
        }

        button[type="submit"] {
            padding: 10px;
            background-color: #28a745;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button[type="submit"]:hover {
            background-color: #218838;
        }

        /* Styles responsives */
        @media (max-width: 600px) {
            .conversation-wrapper {
                padding: 15px;
            }

            .message {
                padding: 8px;
            }

            textarea {
                height: 100px; /* Hauteur de la zone de texte */
            }

            button[type="submit"] {
                padding: 8px;
                font-size: 0.9em;
            }

            .reaction-button {
                padding: 4px 8px;
                font-size: 0.8em;
            }
        }
    </style>




</head>
<body>

<div class="conversation-wrapper">
    <h1>Conversation avec <?php echo htmlspecialchars($receiver['username']); ?></h1>

    <?php foreach ($messages as $msg): ?>
        <center><div class="message <?php echo ($msg['sender'] == $username) ? 'sent' : 'received'; ?>">
            <strong><?php echo htmlspecialchars($msg['sender']); ?>:</strong>
            <p><?php echo htmlspecialchars($msg['message']); ?></p>
            <?php if ($msg['media_path']): ?>
                <p><a href="<?php echo htmlspecialchars($msg['media_path']); ?>" target="_blank">Voir le média</a></p>
            <?php endif; ?>
            <span><?php echo htmlspecialchars($msg['sent_at']); ?></span>

            <!-- Affichage des réactions -->
            <?php if (isset($reactions[$msg['message_id']])): ?>
                <div class="reactions">Réactions: <?php echo htmlspecialchars($reactions[$msg['message_id']]); ?></div>
            <?php endif; ?>

            <!-- Bouton pour réagir -->
            <button class="reaction-button" onclick="toggleDropdown(<?php echo $msg['message_id']; ?>)">Réagir</button>
            <div id="dropdown-<?php echo $msg['message_id']; ?>" class="reaction-dropdown">
                <button onclick="addReaction(<?php echo $msg['message_id']; ?>, '👍')">👍</button>
                <button onclick="addReaction(<?php echo $msg['message_id']; ?>, '❤️')">❤️</button>
                <button onclick="addReaction(<?php echo $msg['message_id']; ?>, '😂')">😂</button>
                <button onclick="addReaction(<?php echo $msg['message_id']; ?>, '😮')">😮</button>
                <button onclick="addReaction(<?php echo $msg['message_id']; ?>, '😢')">😢</button>
            </div>
        </div>
    <?php endforeach; ?></center>

    <form action="send_message.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="receiver_id" value="<?php echo $receiver_id; ?>">
        <textarea name="message" placeholder="Écris ton message ici" required></textarea>
        <input type="file" name="media" accept="image/*,video/*">
        <br>
        <button onclick="window.location.reload();">Voir les nouveau message</button>
<br>
        <button type="submit">Envoyer</button>
    </form>
    <center><a href="https://searcharea.ddns.net/contactpriv/index.php">Porter plainte contre un message</a> / <a href="messaging.php">Revenir sur votre réception</a> / <a href="profile.php">Votre profil</a></center>
</div>
<script>
    // Fonction pour faire défiler la page vers le bas
    function scrollToBottom() {
        window.scrollTo(0, document.body.scrollHeight);
    }

    // Appelle la fonction pour défiler vers le bas dès le chargement de la page
    window.onload = function() {
        scrollToBottom(); // Défile vers le bas immédiatement
    };

    // Appelle la fonction pour défiler vers le bas quand le message est envoyé
    document.querySelector('form').addEventListener('submit', function() {
        scrollToBottom(); // Défile vers le bas après l'envoi
    });
</script>

<script>
    function toggleDropdown(messageId) {
        const dropdown = document.getElementById(`dropdown-${messageId}`);
        dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
    }

    function addReaction(messageId, reaction) {
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "add_reaction.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onload = function() {
            if (xhr.status === 200) {
                alert('Réaction ajoutée: ' + reaction);
                location.reload(); // Recharge la page pour voir la nouvelle réaction
            }
        };
        xhr.send("message_id=" + messageId + "&reaction=" + reaction);
    }
</script>

</body>
</html>
