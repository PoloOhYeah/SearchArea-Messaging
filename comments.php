<?php
session_start();

// Connexion à la base de données
$pdo = new PDO('mysql:host=localhost;dbname=VOTRE DB NAME', 'USER NAME', 'MDP');

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header("Location: logincomments.php");
    exit();
}

// Traitement des commentaires
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['comment'])) {
    $comment = $_POST['comment'];
    $userId = $_SESSION['user']['id'];

    $stmt = $pdo->prepare("INSERT INTO comments (user_id, comment_text) VALUES (?, ?)");
    $stmt->execute([$userId, $comment]);

    header("Location: comments.php"); // Rediriger après le traitement du formulaire
    exit();
}

// Récupérer les commentaires
$sql = "SELECT c.comment_text, u.username FROM comments c JOIN users u ON c.user_id = u.id ORDER BY c.id DESC";
$stmt = $pdo->query($sql);
$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commentaires</title>
    <style>
        /* Styles généraux */
body {
    font-family: 'Arial', sans-serif;
    background-color: #f4f4f9;
    color: #333;
    margin: 0;
    padding: 0;
}

h1 {
    text-align: center;
    color: #444;
    margin: 20px 0;
}

/* Formulaire de commentaires */
.comment-form {
    width: 50%;
    margin: 20px auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.comment-form textarea {
    width: 100%;
    height: 100px;
    padding: 10px;
    margin-bottom: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 14px;
    box-sizing: border-box;
}

.comment-form button[type="submit"] {
    width: 100%;
    padding: 10px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.comment-form button[type="submit"]:hover {
    background-color: #0056b3;
}

/* Conteneur des commentaires */
.comments-container {
    width: 50%;
    margin: 20px auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

/* Commentaire individuel */
.comment {
    padding: 10px;
    border-bottom: 1px solid #eaeaea;
}

.comment:last-child {
    border-bottom: none;
}

.comment p {
    margin: 0;
    color: #555;
}

.comment strong {
    color: #007bff;
    font-weight: bold;
}

/* Responsive */
@media (max-width: 768px) {
    .comment-form, .comments-container {
        width: 90%;
    }
}

        </style>
</head>
<body>

<h1>Bienvenue sur la page de commentaires, <?php echo htmlspecialchars($_SESSION['user']['username']); ?> !</h1>
<div class="comment-form">
    <form action="comments.php" method="post">
        <textarea name="comment" placeholder="Écrivez votre commentaire..." required></textarea>
        <button type="submit">Publier le commentaire</button>
    </form><br>
</div>

<div class="comments-container">
    <?php foreach ($comments as $row): ?>
        <div class="comment">
            <p><strong><?php echo htmlspecialchars($row['username']); ?>:</strong> <?php echo htmlspecialchars($row['comment_text']); ?></p>
        </div>
    <?php endforeach; ?>
</div>

<script>
    function reloadPage() {
        location.reload(true);
    }

    setInterval(reloadPage, 30000); // Actualiser toutes les 30 secondes
</script>
</body>
</html>
