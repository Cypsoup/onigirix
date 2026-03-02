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
      row.style.transform = "translateX(20px)";
      row.style.opacity = "0";

      setTimeout(() => {
        if (todo === "archive") {
          btn.textContent = "Restaurer";
          btn.className =
            "px-3 py-1 font-bold text-xs uppercase transition-all border-2 border-black hover:bg-green-500 hover:text-white";
          btn.setAttribute("onclick", `handleRecipeStatus(${id}, 'restore')`);
        } else {
          btn.textContent = "Archiver";
          btn.className =
            "px-3 py-1 font-bold text-xs uppercase transition-all bg-black text-white hover:bg-red-600";
          btn.setAttribute("onclick", `handleRecipeStatus(${id}, 'archive')`);
        }

        // 4. Déplacer la ligne
        targetList.appendChild(row);

        // 5. Animation d'entrée
        row.style.transform = "translateX(0)";
        row.style.opacity = "1";
      }, 300);
    }
  } catch (error) {
    console.error("Erreur fatale:", error);
  }
}
