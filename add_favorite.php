<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    exit('Non autorisé');
}

$userId = $_SESSION['user_id'];
$pageUrl = $_POST['page_url'];

// Connexion à la base de données
$pdo = new PDO('mysql:host=localhost;dbname=VOTRE DB NAME', 'USER NAME', 'MDP');

// Vérification si la page est déjà dans les favoris
$sql = "SELECT COUNT(*) FROM favorites WHERE user_id = ? AND page_url = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$userId, $pageUrl]);
$count = $stmt->fetchColumn();

if ($count == 0) {
    $sql = "INSERT INTO favorites (user_id, page_url) VALUES (?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$userId, $pageUrl]);
    echo 'Page ajoutée aux favoris';
} else {
    echo 'Page déjà dans les favoris';
}
?>
