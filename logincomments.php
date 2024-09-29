<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les informations de connexion depuis le formulaire
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Connexion à la base de données
    $pdo = new PDO('mysql:host=localhost;dbname=VOTRE DB NAME', 'USER NAME', 'MDP');

    // Vérifier les identifiants
    $stmt = $pdo->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        // Stocker les informations de l'utilisateur dans la session
        $_SESSION['user'] = ['id' => $user['id'], 'username' => $username];

        // Redirection vers la page cible ou vers le profil
        $redirectUrl = isset($_SESSION['redirect_after_login']) ? $_SESSION['redirect_after_login'] : 'comments.php';
        unset($_SESSION['redirect_after_login']); // Supprimer la variable après redirection
        header("Location: " . $redirectUrl);
        exit();
    } else {
        // Si les identifiants sont incorrects, renvoyer à la page de connexion
        header("Location: logincomments.php?error=1");
        exit();
    }
}

// Vérifier si l'utilisateur est déjà connecté avant d'accéder à la page de connexion
if (isset($_SESSION['user'])) {
    // Rediriger vers la page de commentaires ou vers le profil si déjà connecté
    header("Location: comments.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <style>
        form {
    width: 300px;
    margin: 0 auto;
    padding: 20px;
    background-color: #f7f7f7;
    border: 1px solid #ccc;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

label {
    font-size: 14px;
    color: #333;
    margin-bottom: 8px;
    display: block;
}

input[type="text"],
input[type="password"] {
    width: 100%;
    padding: 10px;
    margin-bottom: 20px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 14px;
    box-sizing: border-box;
}

button[type="submit"] {
    width: 100%;
    padding: 10px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s;
}

button[type="submit"]:hover {
    background-color: #0056b3;
}

        </style>
        <link rel="stylesheet" href="https://searcharea.ddns.net/styles.css">
</head>
<body>
    

    <?php if (isset($_GET['error'])): ?>
        <p style="color: red;">Nom d'utilisateur ou mot de passe incorrect.</p>
    <?php endif; ?>
<br>
<br>
    <form method="post" action="logincomments.php">
        <center><h1>Connexion</h1></center>
        <label for="username">Nom d'utilisateur :</label>
        <input type="text" name="username" id="username" required>

        <label for="password">Mot de passe :</label>
        <input type="password" name="password" id="password" required>

        <button type="submit">Se connecter</button>
    </form>
    <br>
    <center>
<img src="commentswarn.png">
    </center>
</body>
</html>
