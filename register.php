<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $email = $_POST['email'];
    $biography = $_POST['biography'];

    // Gestion des fichiers
    $profilePicture = 'default-profile.png';
    $banner = 'default-banner.png';

    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
        $profilePicture = 'uploads/' . basename($_FILES['profile_picture']['name']);
        move_uploaded_file($_FILES['profile_picture']['tmp_name'], $profilePicture);
    }

    if (isset($_FILES['banner']) && $_FILES['banner']['error'] == 0) {
        $banner = 'uploads/' . basename($_FILES['banner']['name']);
        move_uploaded_file($_FILES['banner']['tmp_name'], $banner);
    }

    // Connexion à la base de données
    $pdo = new PDO('mysql:host=localhost;dbname=searcharea', 'root', '');

    // Insertion de l'utilisateur
    $sql = "INSERT INTO users (username, password, email, profile_picture, banner, biography) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$username, $password, $email, $profilePicture, $banner, $biography]);

    // Redirection après inscription
    header('Location: profile.php?register_success=true');
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <style>
        /* Style global pour le wrapper du formulaire */
.register-wrapper {
    width: 100%;
    max-width: 500px;
    margin: 0 auto;
    padding: 20px;
    background-color: #f4f4f4;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    font-family: Arial, sans-serif;
}

.register-wrapper h1 {
    text-align: center;
    color: #333;
    margin-bottom: 20px;
}

/* Style des champs du formulaire */
.register-wrapper input[type="text"],
.register-wrapper input[type="password"],
.register-wrapper input[type="email"],
.register-wrapper textarea,
.register-wrapper input[type="file"] {
    width: 100%;
    padding: 10px;
    margin: 10px 0;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 16px;
    box-sizing: border-box;
}

/* Style pour le champ de texte area (biographie) */
.register-wrapper textarea {
    height: 100px;
    resize: none;
}

/* Style des boutons */
.register-wrapper button {
    width: 100%;
    padding: 10px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 5px;
    font-size: 18px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.register-wrapper button:hover {
    background-color: #007bff;
}

/* Effet de focus pour les champs */
.register-wrapper input[type="text"]:focus,
.register-wrapper input[type="password"]:focus,
.register-wrapper input[type="email"]:focus,
.register-wrapper textarea:focus {
    border-color: #007bff;
    outline: none;
}

/* Adaptation pour mobile */
@media (max-width: 600px) {
    .register-wrapper {
        padding: 15px;
    }
}

        </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - SearchArea</title>
</head>
<body>
   <br>
    <div class="register-wrapper">
        <h1>S'inscrire</h1>
        <center><p>Une erreur 500 peut survenir si il y a déjà des informations similaire dans la BDD.</p></center>
        <form method="post" action="register.php" enctype="multipart/form-data">
            <input type="text" name="username" placeholder="Nom d'utilisateur" required>
            <input type="password" name="password" placeholder="Mot de passe" required>
            <input type="email" name="email" placeholder="Email" required>
            <textarea name="biography" placeholder="Biographie"></textarea>
            Photo de profile :
            <input type="file" name="profile_picture" accept="image/*">
            Banière :
            <input type="file" name="banner" accept="image/*">
            <button type="submit">S'inscrire</button>
        </form>
    </div>
    <br>
    <center><img src="secupriv.png"></center>
</body>
</html>
