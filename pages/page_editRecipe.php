<?php

require_once 'recipes/recipe.php';
require_once 'recipes/recipeRenderer.php';

$id = $_GET['id'] ?? null;
$recipe = null;

if ($id && $id != 0) {
    $recipe = Recipe::getRecipeById($pdo, $id);

    if (!$recipe) {
        header('Location: ../index.php?page=menu&error=unfoundedRecipe');
        exit;
    }
}

echo '<div class="container mx-auto p-6 max-w-3xl">';
RecipeRenderer::renderRecipeForm($recipe);
echo '</div>';

?>