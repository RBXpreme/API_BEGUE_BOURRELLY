<?php

// Vérifier si la requête est de type POST et si l'ID du clan est défini
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['clanId'])) {
    // Récupérer l'ID du clan depuis la requête
    $clantag = $_POST['clanId'];
    
    // Clé d'API et URL pour la requête à l'API Clash of Clans
    $token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiIsImtpZCI6IjI4YTMxOGY3LTAwMDAtYTFlYi03ZmExLTJjNzQzM2M2Y2NhNSJ9.eyJpc3MiOiJzdXBlcmNlbGwiLCJhdWQiOiJzdXBlcmNlbGw6Z2FtZWFwaSIsImp0aSI6ImE2M2VhN2Q1LTRhOWEtNGI2OS1hYzJjLTA5N2FmZTJlNjI4OSIsImlhdCI6MTcwNjY0ODAwMCwic3ViIjoiZGV2ZWxvcGVyLzIzZGE4Y2U5LTU2MGUtNTJiOS1iMTY3LWEwNGU3MzFlYWQ1ZCIsInNjb3BlcyI6WyJjbGFzaCJdLCJsaW1pdHMiOlt7InRpZXIiOiJkZXZlbG9wZXIvc2lsdmVyIiwidHlwZSI6InRocm90dGxpbmcifSx7ImNpZHJzIjpbIjkyLjEzMy4xMDIuNzUiXSwidHlwZSI6ImNsaWVudCJ9XX0.rlTmxjNGFCxNZ5tcgzNhRTrPn1-snyME6BtK_9c2yCAayz_jKoBc6uGNLJA_ogN2oMeaFj0X1hmXB3w1u8mIrQ";
    $token1 = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiIsImtpZCI6IjI4YTMxOGY3LTAwMDAtYTFlYi03ZmExLTJjNzQzM2M2Y2NhNSJ9.eyJpc3MiOiJzdXBlcmNlbGwiLCJhdWQiOiJzdXBlcmNlbGw6Z 2FtZWFwaSIsImp0aSI6IjFjN2UyZjVlLWVlNDUtNDhlNC04MjJjLTRlMzQ5NGQ0OTU0YSIsImlhdCI6MTcwMTE1NjkxMywic3ViIjoiZGV2ZWxvcGVyLzljYTViOTEyLTE0ZjYtNGM3Ny05NGRhLTA3 YjRiMjc3NjA3OSIsInNjb3BlcyI6WyJjbGFzaCJdLCJsaW1pdHMiOlt7InRpZXIiOiJkZXZlbG9wZXIvc2lsdmVyIiwidHlwZSI6InRocm90dGxpbmcifSx7ImNpZHJzIjpbIjIxMy4xNTEu MTczLjExNCJdLCJ0eXBlIjoiY2xpZW50In1dfQ.ttyYejNJlgwQxv6wIBfxAjXIR-KLre8s7NKlmYDXj-i1j3V4mt84DNogmRAiffHezT2c-w6q75S40q9i_5M44A";
    $url = "https://api.clashofclans.com/v1/clans/%23" . urlencode($clantag);

    // Initialiser une session cURL
    $ch = curl_init($url);
    
    // Configurer les options cURL
    $headr = array();
    $headr[] = "Accept: application/json";
    $headr[] = "Authorization: Bearer " . $token;
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headr);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    // Exécuter la requête cURL
    $res = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
    // Fermer la session cURL
    curl_close($ch);

    // Vérifier si la requête a réussi (code HTTP 200)
    if ($httpCode === 200) {
        // Décoder les données JSON de la réponse
        $data = json_decode($res, true);
        
        // Récupérer la liste des membres du clan
        $members = $data["memberList"];

        // Créer un tableau contenant les données à retourner
        $response = [
            'data' => $data,
            'members' => $members,
        ];

        // Retourner les données au format JSON
        header('Content-Type: application/json');
        echo json_encode($response);

        /* 
        // Exemple d'insertion des données dans une base de données MySQL
        $insertQuery = "INSERT INTO clans (clan_id, clan_name, clan_type, clan_description, clan_location_id, clan_location_name, clan_badge_url, clan_level, clan_points, clan_versus_points, clan_war_frequency, clan_war_wins, clan_war_ties, clan_war_losses, clan_member_count, clan_members)
                        VALUES (
                        '$clanId', '$clanName', '$clanType', '$clanDescription', " . $data['location']['id'] . ", '" . $data['location']['name'] . "'," . $data['clanLevel'] . ", " . $data['clanPoints'] . ", " . $data['clanVersusPoints'] . ",'" . $data['warFrequency'] . "', " . $data['warWins'] . ", " . $data['warTies'] . ", " . $data['warLosses'] . "," . $data['members'] . ", '" . json_encode($data['memberList']) . "')";
        $result = $connexion->query($insertQuery);
        */
    } else {
        // En cas d'erreur lors de la requête API, retourner le code d'erreur HTTP
        http_response_code($httpCode);
        echo json_encode(['error' => 'Erreur lors de la récupération des données']);
    }
} else {
    // En cas de requête invalide, retourner le code d'erreur HTTP approprié
    http_response_code(400);
    echo json_encode(['error' => 'Requete invalide']);
}

?>