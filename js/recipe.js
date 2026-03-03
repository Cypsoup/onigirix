export async function handleRecipeStatus(id, todo) {
  const row = document.getElementById(`recipe-row-${id}`);
  const btn = document.getElementById(`btn-${id}`);

  const targetListId = todo == "archive" ? "list-archived" : "list-available";
  const targetList = document.getElementById(targetListId);

  try {
    const response = await fetch(
      `actions/archiveRecipe.php?id=${id}&todo=${todo}`,
    );
    const data = await response.json();

    if (data.success) {
      row.style.opacity = "0";

      setTimeout(() => {
        if (todo === "archive") {
          btn.textContent = "Restaurer";
          btn.className =
            "js-archive-btn px-3 py-1 font-bold text-xs uppercase transition-all border-2 border-black hover:bg-green-500 hover:text-white";
          btn.dataset.todo = "restore";
        } else {
          btn.textContent = "Archiver";
          btn.className =
            "js-archive-btn px-3 py-1 font-bold text-xs uppercase transition-all bg-black text-white hover:bg-red-600";
          btn.dataset.todo = "archive";
        }

        // Déplacer la ligne
        targetList.appendChild(row);

        // Animation d'entrée
        row.style.opacity = "1";
      }, 300);
    }
  } catch (error) {
    console.error("Erreur fatale:", error);
  }
}

export async function handleDeleteRecipe(id) {
  try {
    const response = await fetch(`actions/deleteRecipe.php?id=${id}`);
    const data = await response.json();

    if (data.success) {
      window.location.href = "index.php?page=menu&deleteStatus=success";
    } else {
      alert("Erreur lors de la suppression : " + (data.message || "Inconnue"));
    }
  } catch (error) {
    console.error("Erreur fatale:", error);
  }
}
