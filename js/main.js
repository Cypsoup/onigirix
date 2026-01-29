// Initialisation des icônes
lucide.createIcons();

// Logique d'ouverture du panel
function togglePanel() {
    const panel = document.getElementById('slideOver');
    const overlay = document.getElementById('overlay');
    
    // Si le panneau est "caché", on l'ouvre
    if (panel.classList.contains('translate-x-full')) {
        panel.classList.remove('translate-x-full');
        overlay.classList.remove('hidden');
        setTimeout(() => overlay.classList.add('opacity-100'), 10); // on attend 10 ms pour laisser le temps au CSS de s'appliquer
    } else {
        panel.classList.add('translate-x-full');
        overlay.classList.remove('opacity-100');
        setTimeout(() => overlay.classList.add('hidden'), 300);
    }
}

// Logique d'ouverture du menu déroulant des commandes archivées
function toggleArchives() {
    const container = document.getElementById('archiveContainer');
    const list = document.getElementById('archiveList');
    const icon = document.getElementById('archiveIcon');

    // On bascule la visibilité de la liste
    list.classList.toggle('hidden');

    // On fait tourner l'icône
    icon.classList.toggle('rotate-180');

    // Si la liste est visible (donc menu ouvert), on donne toute la place disponible au conteneur (flex-1)
    // Sinon, on le rend rigide (flex-none) pour qu'il ne prenne que la place du titre
    if (!list.classList.contains('hidden')) {
        container.classList.remove('flex-none');
        container.classList.add('flex-1');
        // Petit hack pour forcer le titre à garder sa marge quand c'est ouvert
        // (optionnel selon tes préférences de design)
    } else {
        container.classList.remove('flex-1');
        container.classList.add('flex-none');
    }
}

// Fonction pour changer d'onglet de stats
function switchStats(tab) {
    // Récupération des éléments
    const btnTotal = document.getElementById('btn-total');
    const btnNext = document.getElementById('btn-next');
    const contentTotal = document.getElementById('content-total');
    const contentNext = document.getElementById('content-next');

    // Classes pour l'état ACTIF des boutons
    const activeClasses = ['text-black', 'border-black'];
    // Classes pour l'état INACTIF des boutons
    const inactiveClasses = ['text-black/40', 'border-transparent'];

    if (tab === 'total') {
        contentTotal.classList.remove('hidden');
        contentNext.classList.add('hidden');

        btnTotal.classList.add(...activeClasses);
        btnTotal.classList.remove(...inactiveClasses);
        
        btnNext.classList.add(...inactiveClasses);
        btnNext.classList.remove(...activeClasses);
    } else {
        contentNext.classList.remove('hidden');
        contentTotal.classList.add('hidden');

        btnNext.classList.add(...activeClasses);
        btnNext.classList.remove(...inactiveClasses);

        btnTotal.classList.add(...inactiveClasses);
        btnTotal.classList.remove(...activeClasses);
    }
}


function updateOrderStatus(orderId, currentStatus) {
    // Déterminer le prochain statut
    let nextStatus = '';
    if (currentStatus === 'attente') nextStatus = 'prepa';
    else if (currentStatus === 'prepa') nextStatus = 'pret';
    else if (currentStatus === 'pret') nextStatus = 'archive';

    // Créer les données à envoyer
    const formData = new FormData();
    formData.append('orderId', orderId);
    formData.append('newStatus', nextStatus);

    // Envoyer la requête au PHP
    fetch('update_status.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Recharger la page pour voir le changement dans les colonnes
            window.location.reload(); 
        } else {
            alert("Erreur lors de la mise à jour");
        }
    });
}