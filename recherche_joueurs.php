<?php

// Informations de connexion à la base de données
$serveur = "localhost";
$utilisateur = "root";
$motDePasse = "root";
$baseDeDonnees = "clash_of_clans";

// Établir une connexion à la base de données
$connexion = new mysqli($serveur, $utilisateur, $motDePasse, $baseDeDonnees);

// Vérification de la connexion
if ($connexion->connect_error) {
    $errorMessage = "La connexion à la base de données a échoué : " . $connexion->connect_error;
    error_log($errorMessage);
    die($errorMessage);
}

// Vérifier si la requête est une requête POST et si l'ID du joueur est fourni
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['playerId'])) {
    $playerId = $_POST['playerId'];
    
    // Token d'autorisation pour l'API Clash of Clans
    // token --> celui à utiliser avec la connexion actuelle
    // token1 --> autre clé API pour connexion avec un autre accès à internet
    $token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiIsImtpZCI6IjI4YTMxOGY3LTAwMDAtYTFlYi03ZmExLTJjNzQzM2M2Y2NhNSJ9.eyJpc3MiOiJzdXBlcmNlbGwiLCJhdWQiOiJzdXBlcmNlbGw6Z2FtZWFwaSIsImp0aSI6ImE2M2VhN2Q1LTRhOWEtNGI2OS1hYzJjLTA5N2FmZTJlNjI4OSIsImlhdCI6MTcwNjY0ODAwMCwic3ViIjoiZGV2ZWxvcGVyLzIzZGE4Y2U5LTU2MGUtNTJiOS1iMTY3LWEwNGU3MzFlYWQ1ZCIsInNjb3BlcyI6WyJjbGFzaCJdLCJsaW1pdHMiOlt7InRpZXIiOiJkZXZlbG9wZXIvc2lsdmVyIiwidHlwZSI6InRocm90dGxpbmcifSx7ImNpZHJzIjpbIjkyLjEzMy4xMDIuNzUiXSwidHlwZSI6ImNsaWVudCJ9XX0.rlTmxjNGFCxNZ5tcgzNhRTrPn1-snyME6BtK_9c2yCAayz_jKoBc6uGNLJA_ogN2oMeaFj0X1hmXB3w1u8mIrQ";
    $token1 = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiIsImtpZCI6IjI4YTMxOGY3LTAwMDAtYTFlYi03ZmExLTJjNzQzM2M2Y2NhNSJ9.eyJpc3MiOiJzdXBlcmNlbGwiLCJhdWQiOiJzdXBlcmNlbGw6Z 2FtZWFwaSIsImp0aSI6IjFjN2UyZjVlLWVlNDUtNDhlNC04MjJjLTRlMzQ5NGQ0OTU0YSIsImlhdCI6MTcwMTE1NjkxMywic3ViIjoiZGV2ZWxvcGVyLzljYTViOTEyLTE0ZjYtNGM3Ny05NGRhLTA3 YjRiMjc3NjA3OSIsInNjb3BlcyI6WyJjbGFzaCJdLCJsaW1pdHMiOlt7InRpZXIiOiJkZXZlbG9wZXIvc2lsdmVyIiwidHlwZSI6InRocm90dGxpbmcifSx7ImNpZHJzIjpbIjIxMy4xNTEu MTczLjExNCJdLCJ0eXBlIjoiY2xpZW50In1dfQ.ttyYejNJlgwQxv6wIBfxAjXIR-KLre8s7NKlmYDXj-i1j3V4mt84DNogmRAiffHezT2c-w6q75S40q9i_5M44A";


    // Configuration de l'URL de l'API Clash of Clans avec l'ID du joueur
    $url = "https://api.clashofclans.com/v1/players/%23" . urlencode($playerId);

    // Initialisation de cURL
    $ch = curl_init($url);

    // Configuration des en-têtes de la requête cURL
    $headr = array();
    $headr[] = "Accept: application/json";
    $headr[] = "Authorization: Bearer " . $token;
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headr);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    // Exécution de la requête cURL
    $res = curl_exec($ch);

    // Vérification des erreurs cURL
    if ($res === false) {
        // En cas d'erreur de requête cURL
        echo json_encode(array('error' => 'Erreur de requête cURL : ' . curl_error($ch)));
    } else {
        // Récupération du code HTTP de la réponse
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        // Vérification du code HTTP
        if ($httpCode === 200) {
            // Si la requête a réussi (200 OK)
            
            // Décodage des données JSON de la réponse
            $playerData = json_decode($res, true);

            // Extraction des informations spécifiques du joueur
            $playerInfo = array(
                'name' => $playerData['name'],
                'tag' => $playerData['tag'],
                'level' => $playerData['expLevel'],
                'townHallLevel' => $playerData['townHallLevel'],
                'townHallWeaponLevel' => $playerData['townHallWeaponLevel'],
                'trophies' => $playerData['trophies'],
                'besttrophies' => $playerData['bestTrophies'],
                // Ajoutez d'autres informations ici si nécessaire
            );

            // Extraction des informations du clan du joueur
            $clanInfo = array(
                'clanName' => isset($playerData['clan']['name']) ? $playerData['clan']['name'] : '',
                'clanId' => isset($playerData['clan']['tag']) ? $playerData['clan']['tag'] : '',
                'clanLevel' => isset($playerData['clan']['clanLevel']) ? $playerData['clan']['clanLevel'] : '',
                // Ajoutez d'autres informations du clan ici (badge, etc.)
            );

            // Extraction des informations sur la ligue du joueur
            $leagueInfo = array(
                'leagueName' => isset($playerData['league']['name']) ? $playerData['league']['name'] : '',
                'leagueIcon' => isset($playerData['league']['iconUrls']['medium']) ? $playerData['league']['iconUrls']['medium'] : '',
                // Ajoutez d'autres informations de la ligue ici
            );

            // Extraction des niveaux des troupes du joueur
            $troops = array();
            foreach ($playerData['troops'] as $troop) {
                $troops[$troop['name']] = array(
                    'name' => $troop['name'],
                    'level' => $troop['level'],
                    // Ajoutez d'autres informations si nécessaire
                );
            }

            // Extraction des niveaux des héros du joueur
            $heroes = array();
            foreach ($playerData['heroes'] as $hero) {
                $heroes[$hero['name']] = array(
                    'name' => $hero['name'],
                    'level' => $hero['level'],
                    // Ajoutez d'autres informations si nécessaire
                );
            }

            // Extraction des niveaux des sorts du joueur
            $spells = array();
            foreach ($playerData['spells'] as $spell) {
                $spells[$spell['name']] = array(
                    'name' => $spell['name'],
                    'level' => $spell['level'],
                    // Ajoutez d'autres informations si nécessaire
                );
            }

            // Requête SQL d'insertion ou de mise à jour dans la base de données
            $insertQuery = "INSERT INTO joueurs (tag, nom, niveau, hdv_level, hdv_weapon_level, trophies, best_trophies, clan_name, clan_id, clan_level)
            VALUES (
            '" . $playerData['tag'] . "',
            '" . $playerInfo['name'] . "',
            " . $playerInfo['level'] . ",
            " . $playerInfo['townHallLevel'] . ",
            " . $playerInfo['townHallWeaponLevel'] . ",
            " . $playerInfo['trophies'] . ",
            " . $playerInfo['besttrophies'] . ",
            '" . $clanInfo['clanName'] . "',
            '" . $clanInfo['clanId'] . "',
            " . $clanInfo['clanLevel'] . "
            )
            ON DUPLICATE KEY UPDATE
            nom = VALUES(nom),
            niveau = VALUES(niveau),
            hdv_level = VALUES(hdv_level),
            hdv_weapon_level = VALUES(hdv_weapon_level),
            trophies = VALUES(trophies),
            best_trophies = VALUES(best_trophies),
            clan_name = VALUES(clan_name),
            clan_id = VALUES(clan_id),
            clan_level = VALUES(clan_level)";

            // Exécution de la requête SQL
            $result = $connexion->query($insertQuery);

            // Retourner les données au format JSON
            header('Content-Type: application/json');
            echo json_encode(array(
                'playerInfo' => $playerInfo,
                'clanInfo' => $clanInfo,
                'leagueInfo' => $leagueInfo,
                'troops' => $troops,
                'heroes' => $heroes,
                'spells' => $spells,
                // Autres données du clan, de la ligue, etc.
            ));

        } else {
            // Si la requête a échoué, afficher le code d'erreur
            echo json_encode(array('error' => 'Erreur lors de la requête : ' . $httpCode));
        }
    }

    // Fermer la session cURL
    curl_close($ch);

} else {
    // Si aucun ID de joueur n'est fourni
    echo json_encode(array('error' => 'Aucun ID de joueur fourni.'));
}

?>