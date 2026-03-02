<?php
class RecipeRenderer
{

    public static function displayList($recipes, $access)
    {
        if (!is_array($recipes) || empty($recipes)) {
            echo "<p>Aucune recette à afficher !</p>";
            return null;
        } else {
            echo '<div class="recipe-container">';
            foreach ($recipes as $recipe) {
                self::displayRecipe($recipe, $access);
            }
            echo "</div>";
        }
    }

    public static function displayRecipe($recipe, $access)
    {
        if ($access == "admin") {
            self::renderAdminRow($recipe);
        } else {
            self::renderUserCard($recipe);
        }
    }

    private static function renderUserCard($recipe)
    {
        $nom = htmlspecialchars($recipe->nom);
        $prix = number_format($recipe->prix, 2, ',', ' ');
        $imagePath = "images/recipeImages/" . $recipe->fileName;
        $desc = nl2br(htmlspecialchars($recipe->description));

        echo <<<HTML
        <div class="border-2 border-black bg-white flex flex-col h-full shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">
            <div class="border-b-2 border-black p-2 bg-black text-white text-center font-black uppercase tracking-tighter">
                {$nom}
            </div>
            
            <div class="aspect-video w-full overflow-hidden border-b border-black bg-gray-100">
                <img src="{$imagePath}" alt="' . $recipe->nom . '" class="w-full h-full object-cover">
            </div>

            <div class="p-4 flex-grow text-sm leading-relaxed italic text-gray-700">
                {$desc}
            </div>

            <div class="p-4 pt-0 text-right">
                <span class="inline-block bg-yellow-300 border-2 border-black px-3 py-1 font-black text-xl shadow-[2px_2px_0px_0px_rgba(0,0,0,1)]">
                    {$prix}€
                </span>
            </div>
        </div>';
        HTML;
    }

    private static function renderAdminRow($recipe)
    {
        $id = (int) $recipe->id;
        $nom = htmlspecialchars($recipe->nom);
        $prix = number_format($recipe->prix, 2, ',', ' ');
        $image = "images/recipeImages/" . $recipe->fileName;

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
            <div id="recipe-row-{$id}" class="flex items-center gap-4 bg-white border border-black p-2 mb-2 hover:bg-gray-50 transition-colors">
                <img src="{$image}" class="w-12 h-12 object-cover border border-black flex-shrink-0">

                <div class="flex-grow min-w-0">
                    <div class="font-bold uppercase text-sm truncate">{$nom}</div>
                    <div class="text-xs text-gray-500 italic truncate">{$desc}</div>
                </div>

                <div class="font-black text-sm w-16 text-center">{$prix}€</div>

                <div class="flex gap-2">
                    <a href="index.php?page=editRecipe&id={$id}" class="inline-block p-2 border border-black hover:bg-blue-100 transition-colors">
                        <i data-lucide="edit-2" class="w-4 h-4"></i>
                    </a>
                    <button id="btn-{$id}" 
                        onclick="handleRecipeStatus({$id}, '{$todo}')" 
                        class="px-3 py-1 font-bold text-xs uppercase transition-all {$btnClass}">
                        {$btnText}
                    </button>
                </div>
            </div>
        HTML;
    }

    public static function renderEditForm($recipe)
    {
        $id = $recipe->id;
        $nom = htmlspecialchars($recipe->nom);
        $desc = htmlspecialchars($recipe->description);
        $prix = $recipe->prix;
        $img = "images/recipeImages/" . $recipe->fileName;

        echo <<<HTML
            <form action="actions/updateRecipe.php" method="POST" enctype="multipart/form-data" class="bg-white border-4 border-black p-8 shadow-[8px_8px_0px_0px_rgba(0,0,0,1)]">
                <h2 class="text-2xl font-black uppercase mb-6 italic">Modifier : {$nom}</h2>
                
                <input type="hidden" name="id" value="{$id}">

                <div class="space-y-4">
                    <div>
                        <label class="block font-black uppercase text-xs mb-1">Nom du produit</label>
                        <input type="text" name="nom" value="{$nom}" class="w-full border-2 border-black p-2 font-bold focus:bg-yellow-50 outline-none">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block font-black uppercase text-xs mb-1">Prix (€)</label>
                            <input type="number" step="0.01" name="prix" value="{$prix}" class="w-full border-2 border-black p-2 font-bold focus:bg-yellow-50 outline-none">
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
                        Sauvegarder les modifications
                    </button>
                </div>
            </form>
        HTML;
    }

}

?>