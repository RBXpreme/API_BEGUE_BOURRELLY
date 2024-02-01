<?php
$serveur = "localhost";
$utilisateur = "root";
$motDePasse = "root";
$baseDeDonnees = "clash_of_clans";

$connexion = new mysqli($serveur, $utilisateur, $motDePasse, $baseDeDonnees);

// Vérification de la connexion
if ($connexion->connect_error) {
    $errorMessage = "La connexion à la base de données a échoué : " . $connexion->connect_error;
    error_log($errorMessage);
    die($errorMessage);
}

// Récupération des tags des joueurs depuis la base de données
$donnees = array();
$query = "SELECT * FROM joueurs GROUP BY nom";
$result = $connexion->query($query);

if ($result->num_rows > 0) {
    $donnees = $result->fetch_assoc();
}

// Retourner les tags au format JSON
header('Content-Type: application/json');
echo json_encode($donnees);
?>