<?php

class RecipeRenderer
{
    public static function displayRecipe($recipe, $access)
    {
        if (!$recipe) {
            Flash::error("Aucune recette à afficher !");
            return;
        }
        if ($access == "admin") {
            self::renderAdminMenuRow($recipe);
        } else {
            self::renderUserMenuCard($recipe);
        }
    }

    public static function displayList($recipes, $access, $listId = null)
    {
        if (!is_array($recipes) || empty($recipes)) {
            // Garde ID pour injection depuis JS
            $idAttr = $listId ? 'id="' . $listId . '"' : '';
            echo '<div ' . $idAttr . ' class="flex flex-col gap-2">';
            echo '<p class="text-center text-black/40 font-bold py-10 uppercase tracking-widest border-4 border-black border-dashed bg-zinc-50">Aucune recette pour le moment...</p>';
            echo '</div>';
            return;
        }

        // Version Admin / User : Ordi / Mobile
        $containerClass = ($access === "admin")
            ? "flex flex-col gap-2"
            : "flex flex-col gap-6 sm:grid sm:grid-cols-2 lg:grid lg:grid-cols-3";

        // On place l'ID directement sur le conteneur Flex/Grid
        $idAttr = $listId ? 'id="' . $listId . '"' : '';
        echo '<div ' . $idAttr . ' class="' . $containerClass . '">';
        foreach ($recipes as $recipe) {
            self::displayRecipe($recipe, $access);
        }
        echo "</div>";
    }



    private static function renderUserMenuCard($recipe)
    {
        $name = htmlspecialchars($recipe->name);
        // On met un point au lieu d'une virgule pour le price, c'est plus moderne
        $price = number_format($recipe->price, 2, '.', '');
        $image = !empty($recipe->fileName)
            ? "images/recipeImages/" . $recipe->fileName
            : 'images/onigiri.png';
        $desc = htmlspecialchars($recipe->description ?? '');

        echo <<<HTML
        <div class="flex flex-col bg-white border-2 border-black p-6 shadow-[6px_6px_0px_0px_rgba(0,0,0,1)] hover:-translate-y-1 hover:-translate-x-1 hover:shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] transition-all">
            
            <div class="w-full aspect-square border-2 border-black bg-zinc-50 mb-4 flex items-center justify-center overflow-hidden">
                <img src="{$image}" alt="{$name}" class="w-full h-full object-cover">
            </div>
            
            <div class="mb-4">
                <h3 class="text-2xl font-black italic uppercase text-black leading-none mb-2">{$name}</h3>
                <p class="font-mono uppercase tracking-tighter text-xs text-zinc-500 leading-relaxed">{$desc}</p>
            </div>
            
            <div class="mt-auto text-right border-t-2 border-black border-dotted pt-4">
                <span class="text-3xl font-black italic text-[#E60012]">{$price}€</span>
            </div>
            
        </div>
        HTML;
    }

    private static function renderAdminMenuRow($recipe)
    {
        $id = (int) $recipe->id;
        $name = htmlspecialchars($recipe->name);
        $price = number_format($recipe->price, 2, ',', ' ');
        $image = !empty($recipe->fileName)
            ? "images/recipeImages/" . $recipe->fileName
            : 'images/onigiri.png';

        $shortDesc = (strlen($recipe->description) > 60)
            ? substr($recipe->description, 0, 60) . "..."
            : $recipe->description;
        $desc = htmlspecialchars($shortDesc);

        if ($recipe->available == 1) {
            $btnText = "Archiver";
            $btnClass = "bg-black text-white hover:bg-red-600";
            $todo = "archive";
        } else {
            $btnText = "Restaurer";
            $btnClass = "border-2 border-black hover:bg-green-500 hover:text-white";
            $todo = "restore";
        }

        echo <<<HTML
            <div id="recipe-row-{$id}" class="flex items-center gap-4 bg-white border border-black p-2 hover:bg-gray-50 transition-colors">
                <img src="{$image}" class="w-12 h-12 object-cover border border-black flex-shrink-0">

                <div class="flex-grow min-w-0">
                    <div class="font-bold uppercase text-sm truncate">{$name}</div>
                    <div class="text-xs text-gray-500 italic truncate">{$desc}</div>
                </div>

                <div class="font-black text-sm w-16 text-center">{$price}€</div>

                <div class="flex gap-2">
                    <a href="index.php?page=editRecipe&id={$id}" class="inline-block p-2 border border-black hover:bg-blue-100 transition-colors">
                        <i data-lucide="edit-2" class="w-4 h-4"></i>
                    </a>
                    <button 
                        id="btn-{$id}"
                        data-recipe-id="{$id}"
                        data-todo="{$todo}"
                        class="js-archive-btn px-3 py-1 font-bold text-xs uppercase transition-all {$btnClass}">
                        {$btnText}
                    </button>
                </div>
            </div>
        HTML;
    }

    public static function renderRecipeForm($recipe = null)
    {
        $isEdit = ($recipe !== null);

        $id = $isEdit ? $recipe->id : "new";
        $name = $isEdit ? htmlspecialchars($recipe->name) : '';
        $desc = $isEdit ? htmlspecialchars($recipe->description) : '';
        $price = $isEdit ? $recipe->price : '';
        $img = $isEdit ? "images/recipeImages/" . $recipe->fileName : null;

        $title = $isEdit ? "Modifier : {$name}" : "Créer une nouvelle recette";
        $btnLabel = $isEdit ? "Sauverger les modifications" : "Créer la recette";
        $action = "actions/saveRecipe.php";

        echo <<<HTML
        <div class="space-y-6">
            <form action="{$action}" method="POST" enctype="multipart/form-data" class="bg-white border-4 border-black p-8 shadow-[8px_8px_0px_0px_rgba(0,0,0,1)]">
                <h2 class="text-2xl font-black uppercase mb-6 italic">{$title}</h2>
                
                <input type="hidden" name="id" value="{$id}">

                <div class="space-y-4">
                    <div>
                        <label class="block font-black uppercase text-xs mb-1">Nom du produit</label>
                        <input type="text" name="name" value="{$name}" class="w-full border-2 border-black p-2 font-bold focus:bg-yellow-50 outline-none">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block font-black uppercase text-xs mb-1">price (€)</label>
                            <input type="number" step="0.01" name="price" value="{$price}" class="w-full border-2 border-black p-2 font-bold focus:bg-yellow-50 outline-none">
                        </div>
                        <div>
                            <label class="block font-black uppercase text-xs mb-1">Photo actuelle</label>
                            <img src="{$img}" class="h-12 w-12 border border-black object-cover">
                        </div>
                    </div>

                    <div>
                        <label class="block font-black uppercase text-xs mb-1">Description</label>
                        <textarea name="description" rows="4" class="w-full border-2 border-black p-2 italic focus:bg-yellow-50 outline-none">{$desc}</textarea>
                    </div>
                    
                    <button type="submit" class="w-full bg-black text-white font-black py-4 uppercase hover:bg-green-500 transition-colors shadow-[4px_4px_0px_0px_rgba(0,0,0,0.3)]">
                        {$btnLabel}
                    </button>
                </div>
            </form>
        HTML;

        if ($isEdit) {
            RecipeRenderer::renderDeleteRecipeBtn($id, $name);
        } else {
            RecipeRenderer::renderBackMenuBtn();
        }
        echo "</div>";
    }

    public static function renderAddRecipeBtn()
    {
        echo <<<HTML
        <div class="mb-8">
            <a 
                href="index.php?page=editRecipe&id=0"
                class="jw-full md:w-auto bg-yellow-400 text-black border-4 border-black px-6 py-4 font-black uppercase italic tracking-widest hover:bg-black hover:text-white transition-all shadow-[6px_6px_0px_0px_rgba(0,0,0,1)] active:shadow-none active:translate-x-1 active:translate-y-1 flex items-center justify-center gap-3">
                <i data-lucide="plus-circle" class="w-6 h-6"></i>
                Créer une nouvelle recette
            </a>
        </div>
        HTML;
    }

    public static function renderDeleteRecipeBtn($id, $name)
    {
        echo <<<HTML
        <div class="bg-red-50 border-4 border-red-600 p-6 shadow-[8px_8px_0px_0px_rgba(220,38,38,1)]">
            <form action="actions/deleteRecipe.php" method="POST" onsubmit="return confirm('Supprimer définitivement {$name} ?')">
                <input type="hidden" name="id" value="{$id}">
                <button type="submit" class="w-full bg-red-600 text-white font-black py-3 uppercase hover:bg-black transition-colors flex items-center justify-center gap-2">
                    <i data-lucide="trash-2" class="w-5 h-5"></i>
                    Supprimer définitivement la recette
                </button>
            </form>
        </div>
        HTML;
    }

    public static function renderBackMenuBtn()
    {
        echo <<<HTML
        <div class="mb-6">
            <a href="index.php?page=menu" 
            class="inline-flex items-center gap-2 bg-white border-2 border-black px-4 py-2 font-black uppercase text-xs tracking-widest hover:bg-black hover:text-white transition-all shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] active:shadow-none active:translate-x-1 active:translate-y-1">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                Retour aux recettes
            </a>
        </div>
        HTML;
    }

    public static function renderAdminOrderRow($recipe)
    {
        // Préparation des données
        $id = (int) $recipe->id;
        $name = htmlspecialchars($recipe->name);
        $stock = (int) ($recipe->stock ?? 0);
        $isAvailable = $stock > 0 && $recipe->available;

        // Gestion des styles
        $disabled = $isAvailable ? '' : 'disabled';
        $opacityClass = $isAvailable ? '' : 'opacity-40 pointer-events-none grayscale';

        // Rendu
        echo <<<HTML
            <div class="flex justify-between items-center py-2 border-b border-black/5 text-sm {$opacityClass}">
                <span class="font-medium">{$name}</span>
                
                <div class="flex items-center gap-4">
                        <button type="button" 
                            onclick="document.getElementById('qty-{$id}').stepDown()" 
                            class="w-8 h-8 border border-black flex items-center justify-center hover:bg-black hover:text-white transition-colors" 
                            {$disabled}>
                            -
                        </button>
                    
                    <input type="number" 
                        name="items[{$id}]" 
                        id="qty-{$id}" 
                        value="0" 
                        min="0" 
                        max="{$stock}" 
                        class="w-6 text-center font-bold outline-none appearance-none m-0 bg-transparent" 
                        {$disabled}>
                    
                    <button type="button" 
                        onclick="document.getElementById('qty-{$id}').stepUp()" 
                        class="w-8 h-8 border border-black flex items-center justify-center hover:bg-black hover:text-white transition-colors" 
                        {$disabled}>
                        +
                    </button>
                </div>
            </div>            
        HTML;
    }

    public static function renderUserOrderCard($recipe)
    {
        $id = (int) $recipe->id;
        $name = htmlspecialchars($recipe->name);
        $description = htmlspecialchars($recipe->description ?? '');
        $price = number_format((float) $recipe->price, 2, '.', '');

        $image = !empty($recipe->fileName)
            ? "images/recipeImages/" . $recipe->fileName
            : 'images/onigiri.png';

        $stock = (int) ($recipe->stock ?? 0);
        $isAvailable = $stock > 0 && $recipe->available;
        $opacityClass = $isAvailable ? '' : 'opacity-50 grayscale pointer-events-none';

        // Remplacement total des onclick par .js-open-recipe et .js-btn-qty
        // Ajout de pointer-events-none sur les images et SVG
        echo <<<HTML
        <div class="flex flex-col bg-white border-2 border-black p-8 shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] mb-2 {$opacityClass}">
            
            <div class="js-open-recipe w-full aspect-square border-2 border-black bg-zinc-50 mb-4 flex items-center justify-center overflow-hidden cursor-pointer active:translate-y-1 transition-transform" data-id="{$id}">
                <img src="{$image}" alt="{$name}" class="w-full h-full object-cover pointer-events-none">
            </div>
            
            <div class="js-open-recipe mb-4 cursor-pointer" data-id="{$id}">
                <h3 class="text-xl font-black italic uppercase text-black leading-none mb-1">{$name}</h3>
                <p class="font-mono uppercase tracking-tighter text-[10px] text-zinc-500 line-clamp-2">{$description}</p>
            </div>
            
            <div class="flex items-center justify-between mt-auto">
                <span class="text-xl font-mono font-black text-black">{$price}€</span>
                
                <div class="flex items-center gap-3">
                    <button class="js-btn-qty w-8 h-8 border-2 border-black bg-white flex items-center justify-center shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] active:translate-y-1 transition-transform" data-id="{$id}" data-delta="-1">
                        <svg class="w-4 h-4 text-black pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M20 12H4"/>
                        </svg>
                    </button>
                    
                    <span class="js-card-qty text-xl font-black font-mono italic text-black w-4 text-center" id="card-qty-{$id}" data-id="{$id}">0</span>
                    
                    <button class="js-btn-qty w-8 h-8 border-2 border-black bg-[#E60012] flex items-center justify-center shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] active:translate-y-1 transition-transform" data-id="{$id}" data-delta="1">
                        <svg class="w-4 h-4 text-white pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/>
                        </svg>
                    </button>
                </div>
            </div>
            
        </div>
        HTML;
    }
}

?>