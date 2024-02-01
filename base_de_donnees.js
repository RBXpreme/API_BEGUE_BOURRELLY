document.addEventListener('DOMContentLoaded', function () {
    // Récupération des éléments du DOM
    const selectTag = document.getElementById('selectTag');
    const btnValider = document.getElementById('btnValider');
    const playerInfoContainer = document.getElementById('playerInfoContainer');

    // Charger les tags des joueurs depuis la base de données
    fetch('base_de_donnees_nom.php')
        .then(response => response.json())
        .then(tags => {
            // Remplir la liste déroulante avec les tags récupérés
            tags.forEach(tag => {
                const option = document.createElement('option');
                option.value = tag;
                option.textContent = tag;
                selectTag.appendChild(option);
            });
        })
        .catch(error => {
            console.error('Erreur lors du chargement des tags des joueurs:', error);
        });

    // Écouter les changements dans la liste déroulante
    btnValider.addEventListener('click', function () {
        // Récupérer le tag du joueur sélectionné
        const selectedTag = selectTag.value;

        // Récupérer les informations du joueur sélectionné depuis la base de données
        fetch('base_de_donnees_affichage.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `selectedTag=${selectedTag}`,
        })
        .then(response => response.json())
        .then(data => {
            console.log('Données du joueur reçues:', data);
            // Afficher les informations du joueur dans le conteneur dédié
            playerInfoContainer.innerHTML = `
                <h2>Informations du joueur</h2>
                <p>ID du joueur : ${data.tag}</p>
                <p>Nom du joueur : ${data.nom}</p>
                <p>Niveau : ${data.niveau}</p>
                <p>Niveau de l'HDV : ${data.hdv_level}</p>
                <p>Niveau de l'arme de l'HDV : ${data.hdv_weapon_level}</p>
                <p>Trophées du joueur : ${data.trophies}</p>
                <p>Meilleurs trophées du joueur : ${data.best_trophies}</p>
                <p>Clan du joueur : ${data.clan_name}</p>
                <p>ID du clan : ${data.clan_id}</p>
                <p>Niveau du clan : ${data.clan_level}</p>
                <!-- Ajoutez d'autres informations du joueur ici si nécessaire -->
            `;
        })
        .catch(error => {
            console.error('Erreur lors de la récupération des données du joueur:', error);
            playerInfoContainer.innerHTML = '<p>Une erreur est survenue lors de la récupération des données du joueur.</p>';
        });
    });
});
