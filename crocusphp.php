<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    // Stocker l'URL de la page actuelle pour redirection après connexion
    $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
}
$user_id = $_SESSION['user_id'] ?? null;

// Connexion à la base de données
$pdo = new PDO('mysql:host=localhost;dbname=VOTRE DB NAME', 'USER NAME', 'MDP');

// Récupération des informations de l'utilisateur si connecté
if ($user_id) {
    $sql = "SELECT * FROM users WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fuse.js/6.4.6/fuse.min.js"></script>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <link rel="shortcut icon" href="https://searcharea.ddns.net/searchicon.png" />
    <link rel="stylesheet" type="text/css" href="https://searcharea.ddns.net/stylesheets/stylesheet.css" />
    <title>SearchArea - Attentat du Crocus City Hall</title>
    <script>
      //COPYRIGHT SearchArea and EGETV+
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
    <div class="main-wrapper">
      <div class="nav-bar">
      <a href="https://searcharea.ddns.net/">
    <?php if (isset($_SESSION['user_id'])): ?>
        <!-- Utilisateur connecté, affiche l'image de profil -->
        <img src="<?php echo htmlspecialchars($user['profile_picture']); ?>" alt="Profil" class="google-logo">
    <?php else: ?>
        <!-- Utilisateur non connecté, affiche le logo sudite -->
        <img src="https://searcharea.ddns.net/logosearchenginenew.png" alt="Logo Sudite" class="google-logo">
    <?php endif; ?>
</a>
        <div class="search-container">
          <input type="text" id="search" placeholder="Effectuez une recherche..." onchange="openPage()" autocomplete="off">
          <script type="text/javascript" src="script.js"></script>
        </div>
      </div>

      <div class="second-navbar">
        <div class="inner-second-div">
          <a href="crocusphp.php">Recherche</a>
          <a href="areamaps.html">Maps</a>
          <a href="index.html">Revenir à SearchArea</a>
          <?php if (!isset($_SESSION['user_id'])): ?>
                <a href="login.php" class="auth-button">Connectez-vous</a>
            <?php else: ?>
                <a href="profile.php" class="auth-button">Accéder à votre profil</a>
                <a href="logout.php" class="auth-button">Se déconnecter</a>

            <?php endif; ?>
        </div>

      </div>
      
           
        
      <div class="search-results">
        <p>Cette page concerne un événement tragique :</p>
        <button onclick="addFavorite()">Ajouter cette page aux favoris</button>
      </div>

      <div class="box-results">
        <h4>Attentat du Crocus City Hall en Russie:</h4>
      
          <li>137 personnes décédé</li>
          <li>154 personnes blessés</li>
          <li>Incendie de la salle de concert par les terroristes.</li>
          <li>4 personnes interpellés en direction de le l'Ukraine dans une voiture Renault.</li>
          <li>Arrestation de 11 personnes, dont quatre terroristes impliqués dans l’attentat.</li>
          <li>Attentat revendiqué par le groupe terroriste Daesh.</li>
          <li>Un adolescent de 15 ans surnomé "Islam" guide vers la sortie une centaine de personnes, leurs sauvant ainsi la vie !</li>
          <li>Les forces de sécurité Russe ont torturé un des terroristes en attachant un appareil électrique à ses testicules.</li>
          <li>22 mars 2023, deuil nationale de la Russie.</li>
     
        <p>22 mars 2024, Moscou en Russie</p>
        <div class="website-chooser">
          <h2>
            <a href="https://www.lefigaro.fr/international/attentat-de-moscou-le-recit-d-une-collusion-entre-les-islamistes-et-l-occident-n-est-pas-nouveau-en-russie-20240327"
              >Attentat de Moscou : «Le récit d'une collusion entre les ...</a
            >
          </h2>
          <h4>https://www.lefigaro.fr/</h4>
        </div>
      </div>
      <div class="snippets">
        <ul>
          <li>
            <a href="abouteventannonce.html">A propos de ces annonces</a>
            <a href="https://searcharea.ddns.net/contact/index.php">Faire un retour</a>
          </li>
        </ul>
      </div>
















      <div class="webpage">
        <h3>
        <a href="https://www.lemonde.fr/international/article/2024/03/24/attentat-pres-de-moscou-deuil-en-russie-apres-l-attaque-du-crocus-city-hall-qui-a-fait-137-morts_6223934_3210.html">Attentat près de Moscou : deuil en Russie après l'attaque ...</a>
        </h3>
        <h4>
        </h4>
        <p>Vladimir Poutine n'a pas fait de nouvelles déclarations mais a allumé un cierge dans la chapelle de sa résidence de Nono-Ogarevo.</p>
      </div>

      <div class="webpage">
        <h3>
            <a href="https://www.francetvinfo.fr/monde/russie/attaque-terroriste-pres-de-moscou/direct-deux-jours-apres-l-attentat-terroriste-pres-de-moscou-une-journee-de-deuil-national-en-russie_6444061.html">DIRECT. Attentat terroriste près de Moscou</a>
        </h3>
        <h4>
        </h4>
        <p>L'attentat perpétré dans une salle de concert située près de Moscou, vendredi, a fait au moins 137 morts.</p>
      </div>

      <div class="webpage">
        <h3>
            <a href="https://www.lemonde.fr/international/live/2024/03/24/en-direct-attentat-pres-de-moscou-le-bilan-de-l-attaque-au-crocus-city-hall-s-eleve-a-137-morts-selon-les-autorites-russes_6223622_3210.html">Attentat près de Moscou : l'Ukraine accuse Vladimir ...</a>

        </h3>
        <h4>
        </h4>
        <p>L'Etat islamique porte l'entière responsabilité de cet attentat. Il n'y a eu aucune implication ukrainienne », a assuré Adrienne Watson, ...</p>
      </div>

      <div class="webpage">
        <h3>
            <a href="https://www.bfmtv.com/international/asie/russie/direct-attentat-de-moscou-les-secours-recherchent-toujours-des-disparus-deux-jours-apres-le-massacre_LN-202403240100.html">DIRECT. Moscou: Emmanuel Macron préside ce soir un ...</a>
            
        </h3>
        <h4>
        </h4>
        <p>La Russie observe ce dimanche 24 mars une journée de deuil national après le massacre revendiqué par Daesh dans une salle de concert à ...</p>
      </div>

      <div class="webpage">
        <h3>
            <a href="https://www.liberation.fr/international/europe/massacre-du-crocus-city-hall-en-russie-les-precedentes-attaques-meurtrieres-dans-lhistoire-de-moscou-20240324_V3HBKYBJ6RB55DCHJAIJJ3WQIY/">Attentat dans une salle de concert</a>

        </h3>
        <h4>
        </h4>
        <p>Le plus souvent commis par des commandos tchétchènes, plusieurs attaques ont endeuillé la capitale Russe depuis le début des années 2000.</p>

      </div>

      


      

      <div class="country-footer">
        <ul>
          <span class="kazakhstan">
            <li>© 2024 SearchArea</li>
          </span>
          <span class="bold">
            <li>Sarcelles, France</li>
          </span>
          <li>
            <a href="https://searcharea.ddns.net/contact/index.php">Contact</a>
          </li>
          <li>
            <a href="eula.html">Condition d'utilisation</a>
          </li>
          <li>
            <a href="https://twitter.com/PoloOhYeah_">Mon Twitter</a>
          </li>
        </ul>
      </div>

    </div>
  </body>
</html>
