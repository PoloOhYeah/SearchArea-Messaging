<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id']; // ID de l'utilisateur connecté

// ID de l'utilisateur dont on consulte le profil
$profile_user_id = isset($_GET['id']) ? (int)$_GET['id'] : $user_id;

// Connexion à la base de données
$pdo = new PDO('mysql:host=localhost;dbname=VOTRE DB NAME', 'USER NAME', 'MDP');

// Récupération des informations de l'utilisateur du profil
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$profile_user_id]);
$user = $stmt->fetch();

// Récupération des favoris
$sql = "SELECT page_url FROM favorites WHERE user_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$profile_user_id]);
$favorites = $stmt->fetchAll(PDO::FETCH_COLUMN);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://searcharea.ddns.net/stylesphp.css">
    <title>Mon Profil - SearchArea</title>
</head>
<body>
    <div class="profile-wrapper">
    <div class="profile-header">
    <img src="<?php echo htmlspecialchars($user['banner']); ?>" alt="Bannière" class="banner">
    <img src="<?php echo htmlspecialchars($user['profile_picture']); ?>" alt="Profil" class="profile-picture">
    
    
</div>
<center><h1><?php echo htmlspecialchars($user['username']); ?></h1></center>
        <div class="profile-bio">
            <p>ID du profil : <?php echo htmlspecialchars($profile_user_id); ?></p> <!-- Affichage de l'ID -->
            <h2>Biographie :</h2>
            <p><?php echo htmlspecialchars($user['biography']); ?></p>
        </div>


        <div class="profile-favorites">
            <h2>Service du compte :</h2>
            <ul>
                <li><a href="messaging.php">Boîte de Réception</a></li>
                <li><a href="comments.php">Tchat public</a></li>
                <li><a href="index.html">SearchArea</a></li>
                <li><a href="https://searcharea.ddns.net/PornZone/pagedeconfirmation.html">PornZone</a></li>


            </ul>
        </div>





        <div class="profile-favorites">
            <h2>Favoris :</h2>
            <ul>
                <?php foreach ($favorites as $favorite): ?>
                    <li><a href="<?php echo htmlspecialchars($favorite); ?>" target="_blank"><?php echo htmlspecialchars($favorite); ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>

        <!-- Bouton pour envoyer un message privé -->
        <?php if ($user_id != $profile_user_id): ?>
            <form action="send_message.php" method="POST">
                <input type="hidden" name="receiver_id" value="<?php echo $profile_user_id; ?>">
                <textarea name="message" placeholder="Écris ton message ici"></textarea>
                <button type="submit">Envoyer un message privé</button>
            </form>
        <?php endif; ?>

        <a href="logout.php">Se déconnecter</a>
    </div>
</body>
</html>
