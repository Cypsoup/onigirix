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
            $actionBtn = <<<HTML
            <button onclick="archiveRecipe({$id})" class="px-3 py-1 bg-black text-white text-xs font-bold hover:bg-red-600 transition-colors uppercase">
                Archiver
            </button>
        HTML;
        } else {
            $actionBtn = <<<HTML
                    <button onclick="restoreRecipe({$id})" class="px-3 py-1 border border-black text-xs font-bold hover:bg-green-500 hover:text-white transition-colors uppercase">
                        Restaurer
                    </button>
        HTML;
        }

        echo <<<HTML
            <div class="flex items-center gap-4 bg-white border border-black p-2 mb-2 hover:bg-gray-50 transition-colors">
                <img src="{$image}" class="w-12 h-12 object-cover border border-black flex-shrink-0">

                <div class="flex-grow min-w-0">
                    <div class="font-bold uppercase text-sm truncate">{$nom}</div>
                    <div class="text-xs text-gray-500 italic truncate">{$desc}</div>
                </div>

                <div class="font-black text-sm w-16 text-center">{$prix}€</div>

                <div class="flex gap-2">
                    <a href="index.php?page=edit_recipe&id={$id}" class="inline-block p-2 border border-black hover:bg-blue-100 transition-colors">
                        <i data-lucide="edit-2" class="w-4 h-4"></i>
                    </a>
                    {$actionBtn}
                </div>
            </div>
        HTML;
    }

}

?>