export async function handleRecipeStatus(id, todo) {
  try {
    const response = await fetch(
      `actions/archiveRecipe.php?id=${id}&todo=${todo}`,
    );
    const data = await response.json();

    if (data.success) {
      // On recharge la page pour voir les changements
      location.reload();
    } else {
      alert("Erreur lors de la mise à jour de la recette.");
    }
  } catch (error) {
    console.error("Erreur fatale:", error);
  }
}
