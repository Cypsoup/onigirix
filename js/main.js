import { initEventListeners, initCreateUserListeners } from "./listeners.js";
import { showToast } from "./utils.js";

document.addEventListener("DOMContentLoaded", () => {
  initEventListeners();
  initCreateUserListeners();

  const msg = document.body.dataset.flashMessage;
  const type = document.body.dataset.flashType;
  if (msg) showToast(msg, type);

  // Initialisation des icônes
  if (window.lucide) lucide.createIcons();
});

// Logique d'ouverture du panel
function togglePanel() {
  const panel = document.getElementById("slideOver");
  const overlay = document.getElementById("overlay");

  // Si le panneau est "caché", on l'ouvre
  if (panel.classList.contains("translate-x-full")) {
    panel.classList.remove("translate-x-full");
    overlay.classList.remove("hidden");
    setTimeout(() => overlay.classList.add("opacity-100"), 10); // on attend 10 ms pour laisser le temps au CSS de s'appliquer
  } else {
    panel.classList.add("translate-x-full");
    overlay.classList.remove("opacity-100");
    setTimeout(() => overlay.classList.add("hidden"), 300);
  }
}

// Logique d'ouverture du menu déroulant des commandes archivées
function toggleArchives() {
  const container = document.getElementById("archiveContainer");
  const list = document.getElementById("archiveList");
  const icon = document.getElementById("archiveIcon");

  // On bascule la visibilité de la liste
  list.classList.toggle("hidden");

  // On fait tourner l'icône
  icon.classList.toggle("rotate-180");

  // Si la liste est visible (donc menu ouvert), on donne toute la place disponible au conteneur (flex-1)
  // Sinon, on le rend rigide (flex-none) pour qu'il ne prenne que la place du titre
  if (!list.classList.contains("hidden")) {
    container.classList.remove("flex-none");
    container.classList.add("flex-1");
    // Petit hack pour forcer le titre à garder sa marge quand c'est ouvert
    // (optionnel selon tes préférences de design)
  } else {
    container.classList.remove("flex-1");
    container.classList.add("flex-none");
  }
}

// Fonction pour changer d'onglet de stats
function switchStats(tab) {
  // Récupération des éléments
  const btnTotal = document.getElementById("btn-total");
  const btnNext = document.getElementById("btn-next");
  const contentTotal = document.getElementById("content-total");
  const contentNext = document.getElementById("content-next");

  // Classes pour l'état ACTIF des boutons
  const activeClasses = ["text-black", "border-black"];
  // Classes pour l'état INACTIF des boutons
  const inactiveClasses = ["text-black/40", "border-transparent"];

  if (tab === "total") {
    contentTotal.classList.remove("hidden");
    contentNext.classList.add("hidden");

    btnTotal.classList.add(...activeClasses);
    btnTotal.classList.remove(...inactiveClasses);

    btnNext.classList.add(...inactiveClasses);
    btnNext.classList.remove(...activeClasses);
  } else {
    contentNext.classList.remove("hidden");
    contentTotal.classList.add("hidden");

    btnNext.classList.add(...activeClasses);
    btnNext.classList.remove(...inactiveClasses);

    btnTotal.classList.add(...inactiveClasses);
    btnTotal.classList.remove(...activeClasses);
  }
}

function updateOrderStatus(orderId, currentStatus) {
  // Déterminer le prochain statut
  let nextStatus = "";
  if (currentStatus === "attente") nextStatus = "prepa";
  else if (currentStatus === "prepa") nextStatus = "pret";
  else if (currentStatus === "pret") nextStatus = "archive";

  // Créer les données à envoyer
  const formData = new FormData();
  formData.append("orderId", orderId);
  formData.append("newStatus", nextStatus);

  // Envoyer la requête au PHP
  fetch("update_status.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        // Recharger la page pour voir le changement dans les colonnes
        window.location.reload();
      } else {
        alert("Erreur lors de la mise à jour");
      }
    });
}

/* document.addEventListener("DOMContentLoaded", () => {
  // Le navigateur vient de se recharger, on vérifie s'il y a un message en attente
  const flashMessage = sessionStorage.getItem("successMessage");

  if (flashMessage) {
    // On affiche le message
    showSuccessToast(flashMessage);
    // On supprime le message pour qu'il ne réapparaisse pas si on recharge la page
    sessionStorage.removeItem("successMessage");
  }

  // Gestion du formulaire
  const orderForm = document.getElementById("orderForm");
  if (orderForm) {
    orderForm.addEventListener("submit", function (e) {
      e.preventDefault(); // On empêche le comportement par défaut du formulaire (rechargement de la page)

      // new FormData(this) capture tout (Trigramme + Tableau items[])
      const formData = new FormData(this);

      // Envoyer la requête au PHP
      fetch("create_order.php", {
        method: "POST",
        body: formData,
      })
        .then((res) => res.json()) // On transforme la réponse res (qui est une réponse HTTP) en JSON
        .then((data) => {
          console.log(data);
          if (data.success) {
            sessionStorage.setItem(
              "successMessage",
              "Commande validée et en attente !",
            ); // On enregistre le message
            window.location.reload(); // On recharge la page
          } else {
            alert("Erreur : " + (data.message || "Vérifie le trigramme."));
          }
        })
        .catch((err) => console.error("Erreur technique :", err));
    });
  }
});

function showSuccessToast(message) {
  const toast = document.createElement("div");

  // Style Brutaliste Vert (Même design que le reste de ton site)
  toast.className = `
        fixed top-5 left-1/2 -translate-x-1/2 
        bg-[#22C55E] text-white px-8 py-4 
        border-2 border-black font-black uppercase tracking-widest text-sm
        shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]
        z-[9999] transition-all duration-300 transform -translate-y-24 opacity-0
    `;
  toast.innerText = message;

  document.body.appendChild(toast);

  // Animation d'entrée (On le fait descendre)
  // Le petit délai permet au navigateur de calculer le CSS avant d'animer
  requestAnimationFrame(() => {
    toast.classList.remove("-translate-y-24", "opacity-0");
  });

  // Animation de sortie après 3 SECONDES
  setTimeout(() => {
    // On le fait remonter
    toast.classList.add("-translate-y-24", "opacity-0");

    // On le supprime proprement du HTML une fois l'animation finie
    setTimeout(() => {
      toast.remove();
    }, 300); // 300ms correspond à la durée de 'transition-all duration-300'
  }, 3000);
}

// --- FONCTION POUR LE POP-UP (A copier aussi dans ton JS) ---
// function showSuccessToast(text) {
//     const toast = document.createElement('div');

//     // Style Brutaliste Vert (assorti à ton design)
//     toast.className = "fixed top-5 left-1/2 -translate-x-1/2 bg-[#22C55E] text-white border-2 border-black px-6 py-3 font-bold uppercase tracking-widest shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] z-50 transition-all duration-300 transform translate-y-[-100px]";
//     // Style Brutaliste Vert
//     // toast.className = `
//     //     fixed top-5 left-1/2 -translate-x-1/2
//     //     bg-[#22C55E] text-white px-6 py-3
//     //     border-2 border-black font-bold uppercase tracking-widest text-sm
//     //     shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]
//     //     z-[9999] transition-all duration-300 transform -translate-y-20 opacity-0
//     // `;
//     toast.innerText = text;

//     document.body.appendChild(toast);

//     // Animation d'entrée (On attend 10ms pour que le CSS prenne en compte la transition)
//     setTimeout(() => {
//         toast.classList.remove('-translate-y-20', 'opacity-0');
//     }, 100);

//     // Animation de sortie après 3 SECONDES
//     setTimeout(() => {
//         // On le fait remonter et disparaître
//         toast.classList.add('-translate-y-20', 'opacity-0');

//         // On le supprime du HTML une fois l'animation finie
//         setTimeout(() => {
//             toast.remove();
//         }, 300);
//     }, 3000);
// }

*/
