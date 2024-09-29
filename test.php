<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Connexion à la base de données
$pdo = new PDO('mysql:host=localhost;dbname=VOTRE DB NAME', 'USER NAME', 'MDP');

// Récupération des informations de l'utilisateur
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Récupération des favoris
$sql = "SELECT page_url FROM favorites WHERE user_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$favorites = $stmt->fetchAll(PDO::FETCH_COLUMN);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="stylesheets/stylesheet.css">
    <title>Test - SearchArea</title>
    <script>
        function addFavorite() {
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'add_favorite.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function () {
                if (xhr.status === 200) {
                    alert('Page ajoutée aux favoris !');
                }
            };

            var pageUrl = window.location.href;
            xhr.send('page_url=' + encodeURIComponent(pageUrl));
        }
    </script>
</head>
<body>
    <div class="profile-wrapper">
        <div class="profile-header">
            <img src="<?php echo htmlspecialchars($user['banner']); ?>" alt="Bannière" class="banner">
            <img src="<?php echo htmlspecialchars($user['profile_picture']); ?>" alt="Profil" class="profile-picture">
            <h1><?php echo htmlspecialchars($user['username']); ?></h1>
        </div>
        <div class="profile-bio">
            <h2>Biographie</h2>
            <p><?php echo htmlspecialchars($user['biography']); ?></p>
        </div>
        <div class="profile-favorites">
            <h2>Favoris</h2>
            <ul>
                <?php foreach ($favorites as $favorite): ?>
                    <li><a href="<?php echo htmlspecialchars($favorite); ?>" target="_blank"><?php echo htmlspecialchars($favorite); ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <button onclick="addFavorite()">Ajouter cette page aux favoris</button>
        <a href="logout.php">Se déconnecter</a>
    </div>
</body>
</html>
