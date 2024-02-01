<?php

// Informations de connexion à la base de données
$serveur = "localhost";
$utilisateur = "root";
$motDePasse = "root";
$baseDeDonnees = "clash_of_clans";

// Connexion à la base de données
$connexion = new mysqli($serveur, $utilisateur, $motDePasse, $baseDeDonnees);

// Vérification de la connexion
if ($connexion->connect_error) {
    $errorMessage = "La connexion à la base de données a échoué : " . $connexion->connect_error;
    error_log($errorMessage);
    die($errorMessage);
}

// Récupération des tags des joueurs depuis la base de données
$tags = array(); // Initialisation d'un tableau pour stocker les tags
$query = "SELECT * FROM joueurs GROUP BY nom"; // Requête SQL pour récupérer les noms uniques
$result = $connexion->query($query);

if ($result->num_rows > 0) {
    // Boucle sur les résultats pour extraire les noms
    while ($row = $result->fetch_assoc()) {
        $tags[] = $row['nom']; // Ajout du nom au tableau des tags
    }
}

// Retourner les tags au format JSON
header('Content-Type: application/json');
echo json_encode($tags);

?>