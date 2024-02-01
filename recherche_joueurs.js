// Attend que le document HTML soit complètement chargé
document.addEventListener('DOMContentLoaded', function() {
    // Récupère le formulaire et le conteneur des données du joueur
    const form = document.getElementById('playerForm');
    const playerDataContainer = document.getElementById('playerData');

    // Ajoute un écouteur d'événements sur la soumission du formulaire
    form.addEventListener('submit', function(event) {
        event.preventDefault(); // Empêche le formulaire de se soumettre normalement

        // Récupère l'ID du joueur à partir du champ de saisie du formulaire
        const playerId = document.getElementById('playerId').value;

        // Fait une requête AJAX vers le fichier PHP avec l'ID du joueur en utilisant la méthode POST
        fetch('recherche_joueurs.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `playerId=${playerId}`,
        })
        .then(response => response.json())
        .then(data => {
            console.log(data);
            if (!data.error) {
                // Affichage des informations du joueur
                const playerInfoContainer = document.getElementById('playerInfo');
                playerInfoContainer.classList.remove('hidden');
                playerInfoContainer.innerHTML = `
                    <h2>Informations du joueur</h2>
                    <p>Nom : ${data.playerInfo.name}</p>
                    <p>Tag : ${data.playerInfo.tag}</p>
                    <p>Level : ${data.playerInfo.level}</p>
                    <p>Level de l'HDV : ${data.playerInfo.townHallLevel}</p>
                    <p>Level de l'arme de l'HDV : ${data.playerInfo.townHallWeaponLevel}</p>
                    <p>Trophées : ${data.playerInfo.trophies}</p>
                    <p>Meilleurs trophées : ${data.playerInfo.besttrophies}</p>
                    <!-- Ajoutez d'autres informations ici si nécessaire -->
                `;

                // Informations du clan du joueur
                const clanInfoContainer = document.getElementById('clanInfo');
                clanInfoContainer.classList.remove('hidden');
                clanInfoContainer.innerHTML = `
                    <h2>Informations du clan du joueur</h2>
                    <p>Nom du clan : ${data.clanInfo.clanName}</p>
                    <p>ID du clan : ${data.clanInfo.clanId}</p>
                    <p>Niveau du clan : ${data.clanInfo.clanLevel}</p>
                    <!-- Ajoutez d'autres informations du clan ici -->
                `;

                // Informations de la ligue du joueur
                const leagueInfoContainer = document.getElementById('leagueInfo');
                leagueInfoContainer.classList.remove('hidden');
                leagueInfoContainer.innerHTML = `
                    <h2>Informations de la ligue du joueur</h2>
                    <p>Nom de la ligue : ${data.leagueInfo.leagueName}</p>
                    <img src="${data.leagueInfo.leagueIcon}" alt="Icône de la ligue">
                    <!-- Ajoutez d'autres informations de la ligue ici -->
                `;

                // Affichage des niveaux des troupes
                const troopsInfoContainer = document.getElementById('troopsInfo');
                troopsInfoContainer.classList.remove('hidden');
                let troopsHTML = '<h2>Niveaux des troupes du joueur</h2>';
                for (const troop in data.troops) {
                    troopsHTML += `<p>${troop} : ${data.troops[troop].level}</p>`;
                }
                troopsInfoContainer.innerHTML = troopsHTML;

                // Affichage des niveaux des héros
                const heroesInfoContainer = document.getElementById('heroesInfo');
                heroesInfoContainer.classList.remove('hidden');
                let heroesHTML = '<h2>Niveaux des héros du joueur</h2>';
                for (const hero in data.heroes) {
                    heroesHTML += `<p>${hero} : ${data.heroes[hero].level}</p>`;
                }
                heroesInfoContainer.innerHTML = heroesHTML;

                // Affichage des niveaux des sorts
                const spellsInfoContainer = document.getElementById('spellsInfo');
                spellsInfoContainer.classList.remove('hidden');
                let spellsHTML = '<h2>Niveaux des sorts du joueur</h2>';
                for (const spell in data.spells) {
                    spellsHTML += `<p>${spell} : ${data.spells[spell].level}</p>`;
                }
                spellsInfoContainer.innerHTML = spellsHTML;

            } else {
                console.error('Erreur lors de la récupération des données:', data.error);
                // Gérer l'affichage de l'erreur dans votre page HTML
            }
        })
        .catch(error => {
            console.error('Erreur lors de la récupération des données:', error);
            playerDataContainer.innerHTML = '<p>Une erreur est survenue lors de la récupération des données du joueur.</p>';
        });
    });
});
