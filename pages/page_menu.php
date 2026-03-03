<?php

require_once 'recipes/recipe.php';
require_once 'recipes/recipeRenderer.php';

if ($access == "admin") {
    $availableRecipes = Recipe::getAllRecipes($pdo, 1);
    $packedRecipes = Recipe::getAllRecipes($pdo, 0);
} else {
    $availableRecipes = Recipe::getAllRecipes($pdo, 1);
}

if ($access == "admin") {
    RecipeRenderer::renderAddRecipeBtn();
}

echo '<h2 class="text-xl font-bold mb-4">DISPONIBLES</h2>';
echo '<div id="list-available" class="space-y-2">';
RecipeRenderer::displayList($availableRecipes, $access);
echo '</div>';

if ($access == "admin") {
    echo '<h2 class="text-xl font-bold mt-12 mb-4 text-gray-400">ARCHIVÉES</h2>';
    echo '<div id="list-archived" class="space-y-2">';
    RecipeRenderer::displayList($packedRecipes, $access);
    echo '</div>';
}

?>