<?php

require_once 'recipes/recipe.php';
require_once 'recipes/recipeRenderer.php';

if ($access == "admin") {
    $availableRecipes = Recipe::getAllRecipes($pdo, 1);
    $packedRecipes = Recipe::getAllRecipes($pdo, 0);
} else {
    $availableRecipes = Recipe::getAllRecipes($pdo, 1);
}

echo '<h2 class="text-xl font-bold mb-4">DISPONIBLES</h2>';
RecipeRenderer::displayList($availableRecipes, $access);

if ($access == "admin") {
    echo '<h2 class="text-xl font-bold mt-12 mb-4 text-gray-400">ARCHIVÉES</h2>';
    RecipeRenderer::displayList($packedRecipes, $access);
}

?>