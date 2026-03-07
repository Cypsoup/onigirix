import { showToast } from "./utils.js";

export function validatePasswordInput(password, confirm) {
  if (confirm.value === "") {
    confirm.style.borderColor = "black";
    return;
  }

  if (password.value === confirm.value) {
    confirm.style.borderColor = "#22C55E"; // Vert OnigiriX
  } else {
    confirm.style.borderColor = "#EF4444"; // Rouge Alerte
  }
}

export function validatePasswordSubmit(password, confirm, event) {
  if (password.value !== confirm.value) {
    event.preventDefault();
    showToast("Les mots de passe ne correspondent pas !", "error");
    confirm.focus();
  }
}
