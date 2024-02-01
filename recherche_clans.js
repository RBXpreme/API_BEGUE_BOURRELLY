// Script JavaScript pour la recherche de clans
let clanInfoContainer;

document.addEventListener('DOMContentLoaded', function() {
    // Récupération de l'élément du formulaire
    const clanForm = document.getElementById('clanForm');

    // Ajout d'un écouteur d'événement pour le formulaire
    clanForm.addEventListener('submit', function(event) {
        event.preventDefault(); // Empêche le formulaire de se soumettre normalement

        // Récupération de l'ID du clan saisi par l'utilisateur
        const clanId = document.getElementById('clanId').value;

        // Requête AJAX pour récupérer les informations du clan depuis le serveur PHP
        fetch('recherche_clans.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `clanId=${clanId}`,
        })
        .then(response => response.json())
        .then(data => {
            console.log(data);

            // Vérification des données récupérées
            if (!data.error) {
                // Affichage des informations du clan
                clanInfoContainer = document.getElementById('clanInfo');
                clanInfoContainer.classList.remove('hidden');
                clanInfoContainer.innerHTML = `
                    <h2>Informations du clan</h2>
                    <p>Nom du clan : ${data.data.name}</p> <!-- Modification ici -->
                    <p>Type du clan : ${data.data.type}</p> <!-- Modification ici -->
                    <p>Description : ${data.data.description}</p> <!-- Modification ici -->
                    <p>Niveau du clan : ${data.data.clanLevel}</p> <!-- Modification ici -->
                    <!-- Ajoutez d'autres informations du clan ici si nécessaire -->
                `;

                // Affichage de la liste des membres du clan
                const membersList = document.getElementById('membersList');
                membersList.classList.remove('hidden');
                membersList.innerHTML = '';

                if (data.members.length > 0) {
                    // Parcourir la liste des membres et les afficher
                    data.members.forEach(member => {
                        let memberHTML = `<div class="cadre-joueur">`;
                        memberHTML += `<p>Nom du membre : ${member.name}</p>`;
                        memberHTML += `<p>Rôle dans le clan : ${member.role}</p>`;
                        memberHTML += `<p>Ligue : ${member.league.name}</p>`;
                        memberHTML += `<p>Trophées : ${member.trophies}</p>`;
                        memberHTML += `<p>Niveau : ${member.expLevel}</p>`;
                        memberHTML += `<p>Niveau de l'HDV : ${member.townHallLevel}</p>`;
                        
                        // Ajout d'images correspondant au niveau de l'HDV
                        switch (member.townHallLevel) {
                            case 1:
                                memberHTML += '<img src="./HDV1.png">';
                                break;
                            case 2:
                                memberHTML += '<img src="./HDV2.png">';
                                break;
                            case 3:
                                memberHTML += '<img src="./HDV3.png">';
                                break;
                            case 4:
                                memberHTML += '<img src="./HDV4.png">';
                                break;
                            case 5:
                                memberHTML += '<img src="./HDV5.png">';
                                break;
                            case 6:
                                memberHTML += '<img src="./HDV6.png">';
                                break;
                            case 7:
                            memberHTML += '<img src="./HDV7.png">';
                                break;
                            case 8:
                            memberHTML += '<img src="./HDV8.png">';
                                break;
                            case 9:
                            memberHTML += '<img src="./HDV9.png">';
                                break;
                            case 10:
                            memberHTML += '<img src="./HDV10.png">';
                                break;
                            case 11:
                            memberHTML += '<img src="./HDV11.png">';
                                break;
                            case 12:
                            memberHTML += '<img src="./HDV12-1.png">';
                                break;
                            case 13:
                            memberHTML += '<img src="./HDV13-1.png">';
                                break;
                            case 14:
                            memberHTML += '<img src="./HDV14-1.png">';
                                break;
                            case 15:
                            memberHTML += '<img src="./HDV15-1.png">';
                                break;
                            // Ajoutez d'autres cas pour chaque niveau de l'HDV
                            // ...
                            default:
                                memberHTML += '<p>Image non disponible</p>';
                        }
                        
                        memberHTML += `</div>`;
                        membersList.innerHTML += memberHTML;
                    });
                } else {
                    membersList.innerHTML = '<p>Aucun membre trouvé</p>';
                }
                
                // ... Ajoutez d'autres données à afficher pour le clan ...

            } else {
                // Gestion des erreurs en cas de problème avec la récupération des données
                console.error('Erreur lors de la récupération des données:', data.error);
                clanInfoContainer.innerHTML = '<p>Une erreur est survenue lors de la récupération des données du clan.</p>';
            }
        })
        .catch(error => {
            // Gestion des erreurs lors de la requête AJAX
            console.error('Erreur lors de la récupération des données:', error);
            clanInfoContainer.innerHTML = '<p>Une erreur est survenue lors de la récupération des données du clan.</p>';
        });
    });
});