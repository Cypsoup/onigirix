<?php

require_once 'recipes/recipe.php';
require_once 'recipes/recipeRenderer.php';

if ($access == "admin") {
    $availableRecipes = Recipe::getAllRecipes($pdo, 1);
    $packedRecipes = Recipe::getAllRecipes($pdo, 0);
} else {
    $availableRecipes = Recipe::getAllRecipes($pdo, 1);
}

?>

<header class="flex items-center justify-between p-6 border-b-2 border-black bg-white">
    <div class="flex flex-col gap-1">
        <h3 class="text-[10px] text-black/50 tracking-widest font-bold">DÉCOUVREZ NOTRE CARTE</h3>
        <h1 class="text-xl font-black italic text-black uppercase">LE MENU</h1>
    </div>
    <div class="w-16 h-12 bg-white flex items-center justify-center">
        <img src="images/logo.jpg" alt="Logo" class="object-cover w-full h-full">
    </div>
</header>

<main class="p-6 pb-28 min-h-screen bg-white">

    <?php if ($access == "admin"): ?>
        <?php RecipeRenderer::renderAddRecipeBtn(); ?>
    <?php endif; ?>

    <div class="mb-12">
        <h2 class="text-2xl font-black italic uppercase mb-6 flex items-center gap-3">
            <span
                class="w-4 h-4 bg-[#E60012] block border-2 border-black shadow-[2px_2px_0px_0px_rgba(0,0,0,1)]"></span>
            DISPONIBLES
        </h2>
        <?php RecipeRenderer::displayList($availableRecipes, $access, "list-available"); ?>
    </div>

    <?php if ($access == "admin"): ?>
        <div class="mb-12 opacity-60 grayscale transition-all hover:opacity-100 hover:grayscale-0">
            <h2 class="text-xl font-black italic uppercase mb-6 text-black flex items-center gap-3">
                <span class="w-4 h-4 bg-black block border-2 border-black"></span>
                ARCHIVÉES
            </h2>
            <?php RecipeRenderer::displayList($packedRecipes, $access, "list-archived"); ?>
        </div>
    <?php endif; ?>

</main>