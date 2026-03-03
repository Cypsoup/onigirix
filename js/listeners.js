import { handleRecipeStatus } from "./recipe.js";

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
