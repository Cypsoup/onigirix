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

// On regroupe tout ce qui doit se lancer au chargement de la page dans UN SEUL bloc
document.addEventListener("DOMContentLoaded", () => {
  // 1. Initialisation des écouteurs
  initEventListeners();
  initCreateUserListeners();
  initEditPasswordListeners();
  initTrigrammeListener();
  initStatsBtnListeners();
  initToggleArchivedOrdersBtnListener();
  initAddOrderPanelBtnListener();

  // 2. Gestion des messages Flash (PHP -> JS)
  const msg = document.body.dataset.flashMessage;
  const type = document.body.dataset.flashType;
  if (msg) showToast(msg, type);

  // 3. Initialisation des icônes
  if (window.lucide) lucide.createIcons();

  // 4. Gestion du formulaire de commande
  const orderForm = document.getElementById("orderForm");
  if (orderForm) {
    orderForm.addEventListener("submit", function (e) {
      e.preventDefault();

      const existingError = document.getElementById("error-msg");
      if (existingError) existingError.remove();

      // Calcul du total
      const quantityInputs = document.querySelectorAll('input[name^="items"]');
      let total = 0;
      quantityInputs.forEach((input) => {
        total += parseInt(input.value) || 0;
      });

      // Validation
      if (total <= 0 || total > 4) {
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

        this.querySelector('button[type="submit"]').after(errorDiv);
        return;
      }

      const formData = new FormData(this);
      const dataToSend = {
        trigramme: formData.get("trigramme"),
        items: {},
      };

      formData.forEach((value, key) => {
        if (key.startsWith("items[")) {
          const match = key.match(/\[(\d+)\]/);
          if (match && parseInt(value) > 0) {
            dataToSend.items[match[1]] = parseInt(value);
          }
        }
      });

      // Envoi au serveur
      fetch("api/submit-order.php", {
        method: "POST",
        body: JSON.stringify(dataToSend),
        headers: {
          "Content-Type": "application/json",
        },
      })
        .then((res) => res.json())
        .then((data) => {
          if (data.success) {
            window.location.reload();
          } else {
            alert(
              "Erreur : " +
                (data.error ||
                  data.message ||
                  "Erreur technique côté serveur."),
            );
          }
        })
        .catch((err) => console.error("Erreur technique :", err));
    });
  }
});
