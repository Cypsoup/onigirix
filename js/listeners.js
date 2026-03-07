import { handleRecipeStatus } from "./recipe.js";
import {
  validatePasswordInput,
  validatePasswordSubmit,
} from "./passwordHandler.js";

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
