import { showToast } from "./utils.js";
import {
  validatePasswordInput,
  validatePasswordSubmit,
  checkTrigrammeUniqueness,
} from "./formHandler.js";
import {
  switchStats,
  toggleArchivedOrders,
  toggleAddOrderPanel,
  updateOrderStatus,
} from "./orderHandler.js";
import { handleRecipeStatus } from "./recipe.js";

export function initEventListeners() {
  document.addEventListener("click", (event) => {
    // ECOUTEUR POUR ARCHIVER - RESTAURER UNE RECETTE
    const archiveBtn = event.target.closest(".js-archive-btn");
    if (archiveBtn) {
      const id = archiveBtn.dataset.recipeId;
      const todo = archiveBtn.dataset.todo;
      handleRecipeStatus(id, todo);
    }

    // ÉCOUTEUR POUR LES STATUTS DE COMMANDE
    const statusBtn = event.target.closest(".js-status-btn");
    if (statusBtn) {
      const orderId = statusBtn.dataset.orderId;
      const currentStatus = statusBtn.dataset.status;
      updateOrderStatus(orderId, currentStatus);
    }
  });
}

export function initCreateUserListeners() {
  const form = document.getElementById("createUserForm");
  if (!form) return;

  const password = document.getElementById("password");
  const confirm = document.getElementById("passwordConfirm");

  // Vérification pendant l'entrée des mots de passe
  password.addEventListener("input", () =>
    validatePasswordInput(password, confirm),
  );
  confirm.addEventListener("input", () =>
    validatePasswordInput(password, confirm),
  );

  // Envoie du formulaire à condition d'égalité des mots de passe
  form.addEventListener("submit", (event) =>
    validatePasswordSubmit(password, confirm, event),
  );
}

export function initEditPasswordListeners() {
  const form = document.getElementById("editUserPasswordForm");
  if (!form) return;

  const password = document.getElementById("password");
  const confirm = document.getElementById("passwordConfirm");

  // Vérification pendant l'entrée des mots de passe
  password.addEventListener("input", () =>
    validatePasswordInput(password, confirm),
  );
  confirm.addEventListener("input", () =>
    validatePasswordInput(password, confirm),
  );

  // Envoie du formulaire à condition d'égalité des mots de passe
  form.addEventListener("submit", (event) =>
    validatePasswordSubmit(password, confirm, event),
  );
}

export function initTrigrammeListener() {
  const input = document.getElementById("trigrammeInput");
  if (!input) return;

  const excludedId = document.getElementById("idInput")?.value;

  input.addEventListener("input", async () => {
    const trigramme = input.value.toUpperCase();
    input.value = trigramme;

    if (trigramme.length == 3) {
      const isTaken = await checkTrigrammeUniqueness(trigramme, excludedId);
      console.log(isTaken, trigramme, excludedId);

      if (isTaken) {
        input.style.borderColor = "#EF4444";
        showToast(`Le trigramme ${trigramme} est déjà utilisé !`, "error");
      } else {
        input.style.borderColor = "#22C55E";
      }
    } else {
      input.style.borderColor = "black";
    }
  });
}

export function initStatsBtnListeners() {
  // Récupération des éléments
  const btnPrepa = document.getElementById("btn-prepa");
  const btnAttente = document.getElementById("btn-attente");

  if (!btnPrepa || !btnAttente) return;

  btnPrepa.addEventListener("click", () => switchStats("prepa"));
  btnAttente.addEventListener("click", () => switchStats("attente"));
}

export function initToggleArchivedOrdersBtnListener() {
  // Récupération des éléments
  const toggleBtn = document.getElementById("btn-toggle-archived-orders");

  if (!toggleBtn) return;

  toggleBtn.addEventListener("click", () => toggleArchivedOrders());
}

export function initAddOrderPanelBtnListener() {
  // Récupération des éléments
  const toggleOpenBtn = document.getElementById("add-order-panel-open-btn");
  const toggleCloseBtn = document.getElementById("add-order-panel-close-btn");

  if (toggleOpenBtn) {
    toggleOpenBtn.addEventListener("click", () => toggleAddOrderPanel("open"));
  }
  if (toggleCloseBtn) {
    toggleCloseBtn.addEventListener("click", () =>
      toggleAddOrderPanel("close"),
    );
  }
}
