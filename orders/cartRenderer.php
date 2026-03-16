<?php

class CartRenderer
{
    // ==========================================
    // 1. LE HEADER
    // ==========================================
    public static function renderHeader()
    {
        echo <<<HTML
        <header class="bg-white z-40 p-6 border-b-2 border-black flex items-center justify-between">
            <div class="flex flex-col gap-1">
                <p class="text-[10px] text-black/50" id="headerSubtitle">Cliquez pour ajouter vos onigiris (0 / 4)</p>
                <h1 class="text-xl font-black italic text-black uppercase">QUELS ONIGIRIS ?</h1>
            </div>
            <div class="w-16 h-12 bg-white flex items-center justify-center">
                <img src="images/logo.jpg" alt="Logo" class="object-cover w-full h-full">
            </div>
        </header>
        HTML;
    }

    // ==========================================
    // 2. LA GRILLE DU MENU
    // ==========================================
    public static function renderMenu($recipes)
    {
        echo '<main class="p-8 pb-44">';
        echo '<div class="flex flex-col gap-4 sm:grid sm:grid-cols-2 md:grid md:grid-cols-3" id="menuGrid">';
        
        if (!empty($recipes)) {
            foreach ($recipes as $recipe) {
                // On délègue l'affichage de la carte à votre RecipeRenderer
                RecipeRenderer::renderUserOrderCard($recipe);
            }
        } else {
            echo <<<HTML
                <p class="col-span-2 text-center text-black/40 font-bold py-10 uppercase tracking-widest border-2 border-black border-dotted">
                    Aucun onigiri au menu aujourd'hui...
                </p>
            HTML;
        }
        
        echo '</div>';
        echo '</main>';
    }

    // ==========================================
    // 3. LE DRAWER (Panneau du bas pour choisir la quantité)
    // ==========================================
    public static function renderDrawer()
    {
        echo <<<HTML
        <div id="drawer" class="fixed inset-0 z-[100] hidden">
            <div id="drawerOverlay" class="absolute inset-0 bg-black/60 backdrop-blur-sm transition-opacity duration-300 ease-out opacity-0"></div>

            <div id="drawerContent" class="absolute bottom-0 left-0 right-0 bg-white border-t-2 border-black max-h-[85vh] overflow-y-auto translate-y-full transition-transform duration-300 ease-out">
                <div class="flex justify-center pt-4 pb-2">
                    <div class="w-12 h-2 bg-black"></div>
                </div>

                <div class="px-6 pb-8 pt-2">
                    <div class="w-32 h-32 mx-auto border-2 border-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] bg-zinc-50 flex items-center justify-center mb-6 overflow-hidden">
                        <img src="images/onigiri.png" id="drawerImage" alt="Onigiri" class="w-full h-full object-cover">
                    </div>

                    <h2 class="text-3xl font-black italic uppercase text-black text-center mb-2" id="drawerName">Nom Onigiri</h2>
                    <p class="text-xl font-mono font-black text-center mb-6" style="color: #E60012;" id="drawerPrice">0.00€</p>

                    <div class="border-2 border-black border-dotted p-4 mb-8 bg-white">
                        <p class="font-mono text-sm uppercase tracking-tighter text-zinc-600 leading-relaxed text-center" id="drawerDescription">
                            Description
                        </p>
                    </div>

                    <div class="flex items-center justify-center gap-6 mb-8">
                        <button class="js-btn-qty w-12 h-12 border-2 border-black bg-white flex items-center justify-center shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] active:translate-y-1 transition-transform" data-delta="-1">
                            <i data-lucide="minus" class="w-5 h-5 text-black pointer-events-none"></i>
                        </button>

                        <div class="text-4xl font-mono font-black italic text-black w-16 text-center" id="drawerQty">0</div>

                        <button class="js-btn-qty w-12 h-12 border-2 border-black bg-[#E60012] flex items-center justify-center shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] active:translate-y-1 transition-transform" data-delta="1">
                            <i data-lucide="plus" class="w-5 h-5 text-white pointer-events-none"></i>
                        </button>
                    </div>

                    <button class="js-close-drawer w-full bg-black text-white font-bold uppercase tracking-widest text-sm py-4 border-2 border-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] active:translate-y-1 transition-transform">
                        Ajouter à la commande
                    </button>
                </div>
            </div>
        </div>
        HTML;
    }

    // ==========================================
    // 4. LE FOOTER (Barre collée en bas)
    // ==========================================
    public static function renderFooter()
    {
        echo <<<HTML
        <div id="cartFooter" class="fixed bottom-20 left-0 right-0 bg-white border-t-2 border-black px-6 py-4 z-30 opacity-0 pointer-events-none transition-all duration-300 [&.visible]:opacity-100 [&.visible]:pointer-events-auto">
            <div class="flex items-center justify-between">
                <div class="flex flex-col gap-1">
                    <p class="font-mono uppercase tracking-tighter text-[10px] text-zinc-500" id="cartItemsCount">0 Onigiris sélectionnés</p>
                    <p class="text-2xl font-mono font-black italic text-black" id="cartTotal">0.00€</p>
                </div>
                <button class="js-open-validation bg-[#E60012] text-white font-bold px-8 py-3 border-2 border-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] active:translate-y-1 transition-transform">
                    SUIVANT
                </button>
            </div>
        </div>
        HTML;
    }

    // ==========================================
    // 5. LA MODALE DE VALIDATION
    // ==========================================
    public static function renderValidationModal($trigramme)
    {
        echo <<<HTML
        <div id="validationOverlay" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-sm hidden flex items-center justify-center px-5 transition-opacity duration-300 ease opacity-0">
            <div id="validationContent" class="bg-white border-2 border-black shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] p-8 w-full max-w-md scale-90 opacity-0 transition-all duration-300 ease-out relative">

                <button class="js-close-validation absolute -top-4 -right-4 w-10 h-10 bg-white border-2 border-black flex items-center justify-center shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] active:translate-y-1 transition-transform">
                    <i data-lucide="x" class="w-5 h-5 text-black pointer-events-none"></i>
                </button>

                <div class="text-center mb-2">
                    <div class="w-16 h-16 mx-auto border-2 border-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] bg-zinc-50 flex items-center justify-center mb-6">
                        <i data-lucide="check" class="w-8 h-8 text-[#E60012]"></i>
                    </div>
                </div>

                <div class="mb-6">
                    <span class="font-mono uppercase tracking-tighter text-sm text-zinc-400 mb-2 block text-center">Ton Trigramme</span>
                    <div class="w-full text-center text-5xl font-black text-black bg-white border-2 border-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] py-4 uppercase tracking-wider">
                        {$trigramme}
                    </div>
                </div>

                <div class="border-2 border-black border-dotted p-4 mb-8 bg-white">
                    <div class="flex justify-between items-center mb-3">
                        <span class="font-mono uppercase tracking-tighter text-sm text-zinc-600">Articles</span>
                        <span class="font-mono text-sm font-bold text-black" id="finalItemsCount">0</span>
                    </div>

                    <div id="finalItemsList" class="flex flex-col gap-2 mb-4 border-b-2 border-black pb-4">
                        </div>

                    <div class="flex justify-between items-center">
                        <span class="font-mono uppercase tracking-tighter text-sm text-zinc-600">Total</span>
                        <span class="text-xl font-mono font-black text-[#E60012]" id="finalTotal">0.00€</span>
                    </div>
                </div>

                <button class="js-submit-order w-full bg-[#E60012] text-white font-black italic uppercase tracking-widest py-4 border-2 border-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] active:translate-y-1 transition-transform text-lg">
                    VALIDER
                </button>
            </div>
        </div>
        HTML;
    }
}