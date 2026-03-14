import {
  initEventListeners,
  initCreateUserListeners,
  initEditPasswordListeners,
  initTrigrammeListener,
  initStatsBtnListeners,
  initToggleArchivedOrdersBtnListener,
  initAddOrderPanelBtnListener,
} from "./listeners.js";
import { showToast } from "./utils.js";
import { switchStats, toggleArchivedOrders } from "./orderHandler.js";

document.addEventListener("DOMContentLoaded", () => {
  initEventListeners();
  initCreateUserListeners();
  initEditPasswordListeners();
  initTrigrammeListener();
  initStatsBtnListeners();
  initToggleArchivedOrdersBtnListener();
  initAddOrderPanelBtnListener();

  const msg = document.body.dataset.flashMessage;
  const type = document.body.dataset.flashType;
  if (msg) showToast(msg, type);

  // Initialisation des icônes
  if (window.lucide) lucide.createIcons();
});

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

document.addEventListener("DOMContentLoaded", () => {
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

      // On supprime l'éventuel message d'erreur précédent
      const existingError = document.getElementById("error-msg");
      if (existingError) existingError.remove();

      // Calcul du total d'onigiris commandés (pour validation côté client)
      const quantityInputs = document.querySelectorAll('input[name^="items"]'); // On sélectionne tous les inputs qui commencent par "items"
      let total = 0;
      quantityInputs.forEach((input) => {
        total += parseInt(input.value) || 0; // parseInt pour convertir en nombre, et || 0 pour éviter les NaN si le champ est vide
      });

      if (total <= 0 || total > 4) {
        // Création du message d'erreur
        const errorDiv = document.createElement("div");
        errorDiv.id = "error-msg";
        errorDiv.className =
          "italic mt-2 text-[11px] text-[#E60012] font-bold text-center uppercase tracking-wider";
        errorDiv.innerText =
          total <= 0
            ? "Ton panier est vide !"
            : "Maximum 4 onigiris par personne (Tu en as sélectionné " +
              total +
              ")";

        // On l'affiche avant le bouton
        this.querySelector('button[type="submit"]').after(errorDiv);

        return; // on stoppe tout ici, le fetch ne sera pas exécuté
      }

      // new FormData(this) capture tout (Trigramme + Tableau items[])
      const formData = new FormData(this);

      // Envoyer la requête au PHP
      fetch("api/submit-order.php", {
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

// Exposer les fonctions dynamiques au HTML (car main.js est un module)
window.togglePanel = togglePanel;
window.updateOrderStatus = updateOrderStatus;
window.switchStats = switchStats;
window.toggleArchivedOrders = toggleArchivedOrders;
