import { handleRecipeStatus } from "./recipe.js";
import { showToast } from "./utils.js";

export function initEventListeners() {
  document.addEventListener("click", (event) => {
    const archiveBtn = event.target.closest(".js-archive-btn");
    if (archiveBtn) {
      const id = archiveBtn.dataset.recipeId;
      const todo = archiveBtn.dataset.todo;
      handleRecipeStatus(id, todo);
    }
  });
}

export function initCreateUserListeners() {
  const form = document.getElementById("createUserForm");
  if (!form) return;

  const password = document.getElementById("password");
  const confirm = document.getElementById("passwordConfirm");

  const validatePasswordInput = () => {
    if (confirm.value === "") {
      confirm.style.borderColor = "black";
      return;
    }

    if (password.value === confirm.value) {
      confirm.style.borderColor = "#22C55E"; // Vert OnigiriX
    } else {
      confirm.style.borderColor = "#EF4444"; // Rouge Alerte
    }
  };

  const validatePasswordSubmit = (event) => {
    if (password.value !== confirm.value) {
      event.preventDefault();
      showToast("Les mots de passe ne correspondent pas !", "error");
      confirm.focus();
    }
  };

  // Vérification pendant l'entrée des mots de passe
  password.addEventListener("input", validatePasswordInput);
  confirm.addEventListener("input", validatePasswordInput);

  // Envoie du formulaire à condition d'égalité des mots de passe
  form.addEventListener("submit", validatePasswordSubmit);
}
