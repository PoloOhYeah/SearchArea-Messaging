<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="searchicon.png">
    <link rel="stylesheet" href="stylesheets.css">
    <title>SearchArea - Accueil</title>
</head>
<body>
    <div class="main-wrapper">
        <div class="nav-bar">
            <a href="index.php"><img src="logosearchenginenew.png" alt="SearchArea" id="google-logo"></a>
        </div>
        <div class="login-register">
            <h2>Se connecter</h2>
            <form method="post" action="login.php">
                <input type="text" name="username" placeholder="Nom d'utilisateur" required>
                <input type="password" name="password" placeholder="Mot de passe" required>
                <button type="submit">Se connecter</button>
            </form>
            <h2>S'inscrire</h2>
            <form method="post" action="register.php" enctype="multipart/form-data">
                <input type="text" name="username" placeholder="Nom d'utilisateur" required>
                <input type="password" name="password" placeholder="Mot de passe" required>
                <input type="email" name="email" placeholder="Email" required>
                <button type="submit">S'inscrire</button>
            </form>
        </div>
    </div>
</body>
</html>
