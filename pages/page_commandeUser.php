<?php

// Importation des fichiers
require_once 'config/db.php';
require_once 'recipes/recipe.php';
require_once 'recipes/recipeRenderer.php';
require_once 'includes/functions.php';

// Récupération des recettes actives depuis la base de données
$recipes = Recipe::getAllRecipes($pdo, 1);
if ($recipes === null) {
    $recipes = [];
}
// $recipesJSON = getRecipesAsJSON($pdo);
$recipesJSON = json_encode($recipes);


?>

<header class="sticky top-0 bg-white z-40 p-6 border-b-2 border-black flex items-center justify-between">
    <div class="flex flex-col gap-1">
        <p class="text-[10px] text-black/50" id="headerSubtitle">Cliquez pour ajouter vos onigiris (0 / 4)</p>
        <h1 class="text-xl font-black italic text-black uppercase">QUELS ONIGIRIS ?</h1>
    </div>
    <div class="w-16 h-12 bg-white flex items-center justify-center">
        <img src="images/logo.png" alt="Logo" class="object-cover w-full h-full">
    </div>
</header>

<main class="p-8">
    <div class="flex flex-col gap-4" id="menuGrid">
        <?php
        if (!empty($recipes)) {
            foreach ($recipes as $recipe) {
                RecipeRenderer::renderUserOrderCard($recipe);
            }
        } else {
            echo <<<end
                <p class="col-span-2 text-center text-black/40 font-bold py-10 uppercase tracking-widest border-2 border-black border-dotted">
                    Aucun onigiri au menu aujourd'hui...
                </p>
                end;
        }
        ?>
    </div>
</main>

<div id="drawer" class="fixed inset-0 z-50 hidden">
    <div id="drawerOverlay"
        class="absolute inset-0 bg-black/60 backdrop-blur-sm transition-opacity duration-300 ease-out"
        onclick="closeDrawer()"></div>

    <div id="drawerContent"
        class="absolute bottom-0 left-0 right-0 bg-white border-t-2 border-black max-h-[85vh] overflow-y-auto translate-y-full transition-transform duration-300 ease-out">
        <div class="flex justify-center pt-4 pb-2">
            <div class="w-12 h-2 bg-black"></div>
        </div>

        <div class="px-6 pb-8 pt-2">
            <div
                class="w-32 h-32 mx-auto border-2 border-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] bg-zinc-50 flex items-center justify-center mb-6 overflow-hidden">
                <img src="images/onigiri.png" id="drawerImage" alt="Onigiri" class="w-full h-full object-cover">
            </div>

            <h2 class="text-3xl font-black italic uppercase text-black text-center mb-2" id="drawerName">Sake
                Onigiri</h2>

            <p class="text-xl font-mono font-black text-center mb-6" style="color: #E60012;" id="drawerPrice">3.50€
            </p>

            <div class="border-2 border-black border-dotted p-4 mb-8 bg-white">
                <p class="font-mono text-sm uppercase tracking-tighter text-zinc-600 leading-relaxed text-center"
                    id="drawerDescription">
                    Saumon grillé mariné, riz japonais vinaigré, algue nori croustillante.
                </p>
            </div>

            <div class="flex items-center justify-center gap-6 mb-8">
                <button onclick="changeQty(currentItem.id, -1)"
                    class="w-12 h-12 border-2 border-black bg-white flex items-center justify-center shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] active:translate-y-1 transition-transform">
                    <svg class="w-5 h-5 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M20 12H4" />
                    </svg>
                </button>

                <div class="text-4xl font-mono font-black italic text-black w-16 text-center" id="drawerQty">1</div>

                <button onclick="changeQty(currentItem.id, 1)"
                    class="w-12 h-12 border-2 border-black bg-[#E60012] flex items-center justify-center shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] active:translate-y-1 transition-transform">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" />
                    </svg>
                </button>
            </div>

            <button onclick="closeDrawer()"
                class="w-full bg-black text-white font-bold uppercase tracking-widest text-sm py-4 border-2 border-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] active:translate-y-1 transition-transform">
                Ajouter à la commande
            </button>
        </div>
    </div>
</div>

<div id="cartFooter" class="cart-footer fixed bottom-20 left-0 right-0 bg-white border-t-2 border-black px-6 py-4 z-30">
    <div class="flex items-center justify-between">
        <div class="flex flex-col gap-1">
            <p class="font-mono uppercase tracking-tighter text-[10px] text-zinc-500" id="cartItemsCount">0 Onigiris
                sélectionnés</p>
            <p class="text-2xl font-mono font-black italic text-black" id="cartTotal">0.00€</p>
        </div>
        <button onclick="openValidation()"
            class="bg-[#E60012] text-white font-bold px-8 py-3 border-2 border-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] active:translate-y-1 transition-transform">
            SUIVANT
        </button>
    </div>
</div>

<div id="validationOverlay"
    class="fixed inset-0 z-50 bg-black/80 backdrop-blur-sm hidden flex items-center justify-center px-5 transition-opacity duration-300 ease">
    <div id="validationContent"
        class="bg-white border-2 border-black shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] p-8 w-full max-w-md scale-90 opacity-0 transition-all duration-300 ease-out relative">

        <button onclick="closeValidation()"
            class="absolute -top-4 -right-4 w-10 h-10 bg-white border-2 border-black flex items-center justify-center shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] active:translate-y-1 transition-transform">
            <svg class="w-5 h-5 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <div class="text-center mb-8">
            <div
                class="w-16 h-16 mx-auto border-2 border-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] bg-zinc-50 flex items-center justify-center mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none"
                    stroke="#E60012" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
            </div>
            <h2 class="text-2xl font-black italic uppercase text-black mb-2">Finaliser</h2>
            <p class="font-mono uppercase tracking-tighter text-[10px] text-zinc-500">Vérifiez avant de valider</p>
        </div>

        <div class="mb-6">
            <span class="font-mono uppercase tracking-tighter text-sm text-zinc-400 mb-2 block text-center">Ton
                Trigramme</span>
            <div
                class="w-full text-center text-5xl font-black text-black bg-white border-2 border-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] py-4 uppercase tracking-wider">
                <?= $_SESSION['trigramme'] ?? 'ABC' ?>
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

        <button onclick="submitOrder()"
            class="w-full bg-[#E60012] text-white font-black italic uppercase tracking-widest py-4 border-2 border-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] active:translate-y-1 transition-transform text-lg">
            VALIDER
        </button>
    </div>
</div>

<script>
    // ==========================================
    // DONNÉES DEPUIS LA BASE DE DONNÉES
    // ==========================================

    const menuItems = Object.values(<?= $recipesJSON ?>);
    console.log('Menu Items:', menuItems);

    // ==========================================
    // ETAT GLOBAL
    // ==========================================
    let cart = {};
    let currentItem = null;
    let drawerQuantity = 0;
    const MAX_ITEMS = 4;

    // ==========================================
    // INITIALISATION
    // ==========================================
    function init() {
        updateCartUI();
        updateHeaderSubtitle();
    }

    // ==========================================
    // GESTION DU PANIER
    // ==========================================
    function changeQty(itemId, delta) {
        const currentItemQty = cart[itemId] || 0;
        if (currentItemQty === 0 && delta < 0) {
            return;
        }

        const totalItems = Object.values(cart).reduce((sum, qty) => sum + qty, 0);

        if (totalItems >= MAX_ITEMS && delta > 0) {
            showNotification('Pas plus de ' + MAX_ITEMS + ' onigiris', 'warning');
            return;
        }

        if (totalItems <= 0 && delta < 0) {
            showNotification('Votre panier est vide', 'warning');
            return;
        }

        const newQty = currentItemQty + delta;
        // Si on tombe à 0, on supprime l'article du panier
        if (newQty === 0) {
            delete cart[itemId]; // "delete" efface complètement la ligne de l'objet
        } else {
            cart[itemId] = newQty; // Sinon on met à jour le chiffre
        }

        updateCartUI();
        updateHeaderSubtitle();
    }

    function updateCartUI() {
        // On remet tous les compteurs des cartes à zéro par défaut
        document.querySelectorAll('[id^="card-qty-"]').forEach(el => el.textContent = '0');

        const totalItems = Object.values(cart).reduce((sum, qty) => sum + qty, 0);
        let totalPrice = 0;
        for (let id in cart) {
            const qty = cart[id];

            // On met à jour le chiffre sur la carte correspondante
            const cardQtyElement = document.getElementById('card-qty-' + id);
            if (cardQtyElement) {
                cardQtyElement.textContent = qty;
            }

            const item = menuItems.find(i => i.id == id);
            if (item) {
                totalPrice += item.price * qty;
            }

            if (currentItem && currentItem.id == id) {
                const drawerQtyEl = document.getElementById('drawerQty');
                if (drawerQtyEl) {
                    drawerQtyEl.textContent = qty;
                }
            }
        }

        document.getElementById('cartItemsCount').textContent = `${totalItems} Onigiri${totalItems > 1 ? 's' : ''} sélectionné${totalItems > 1 ? 's' : ''}`;
        document.getElementById('cartTotal').textContent = `${totalPrice.toFixed(2)}€`;

        const footer = document.getElementById('cartFooter');
        if (totalItems > 0) {
            footer.classList.add('visible');
        } else {
            footer.classList.remove('visible');
        }
    }

    function updateHeaderSubtitle() {
        const totalItems = Object.values(cart).reduce((sum, qty) => sum + qty, 0);
        document.getElementById('headerSubtitle').textContent =
            `Cliquez pour ajouter vos onigiris (${totalItems} / ${MAX_ITEMS})`;
    }

    // ==========================================
    // GESTION DU DRAWER
    // ==========================================
    function openDrawer(itemId) {
        currentItem = menuItems.find(i => i.id == itemId);

        if (!currentItem) {
            console.error('Recette non trouvée:', itemId);
            return;
        }

        document.getElementById('drawerImage').src = currentItem.image
        document.getElementById('drawerImage').alt = `${currentItem.name} Onigiri`;
        document.getElementById('drawerName').textContent = `${currentItem.name}`;
        document.getElementById('drawerPrice').textContent = `${currentItem.price.toFixed(2)}€`;
        document.getElementById('drawerDescription').textContent = currentItem.description;
        document.getElementById('drawerQty').textContent = cart[itemId] || 0;

        const drawer = document.getElementById('drawer');
        drawer.classList.remove('hidden');
        setTimeout(() => {
            document.getElementById('drawerOverlay').style.opacity = '1';
            document.getElementById('drawerContent').classList.remove('translate-y-full');
        }, 10);
    }

    function closeDrawer() {
        const drawer = document.getElementById('drawer');
        document.getElementById('drawerOverlay').style.opacity = '0';
        document.getElementById('drawerContent').classList.add('translate-y-full');
        setTimeout(() => {
            drawer.classList.add('hidden');
        }, 300);
    }


    // ==========================================
    // GESTION DE LA VALIDATION
    // ==========================================
    function openValidation() {
        const totalItems = Object.values(cart).reduce((sum, qty) => sum + qty, 0);
        // Si le panier est vide, on bloque l'ouverture
        if (totalItems === 0) {
            showNotification('Votre panier est vide', 'warning');
            return;
        }

        let totalPrice = 0;

        const itemsListContainer = document.getElementById('finalItemsList');
        itemsListContainer.innerHTML = ''; // On vide la liste avant de la remplir

        for (let id in cart) {
            const qty = cart[id];
            const item = menuItems.find(i => i.id == id);
            if (item) {
                const itemTotalPrice = item.price * qty;
                totalPrice += itemTotalPrice;

                itemsListContainer.innerHTML += `
                        <div class="flex justify-between items-center">
                            <span class="font-mono text-xs uppercase text-black">
                                <span class="font-bold">${qty}x</span> ${item.name}
                            </span>
                            <span class="font-mono text-xs font-bold text-black">
                                ${itemTotalPrice.toFixed(2)}€
                            </span>
                        </div>
                    `;
            }
        }

        document.getElementById('finalItemsCount').textContent = totalItems;
        document.getElementById('finalTotal').textContent = `${totalPrice.toFixed(2)}€`;

        const overlay = document.getElementById('validationOverlay');
        const content = document.getElementById('validationContent');

        overlay.classList.remove('hidden');
        setTimeout(() => {
            overlay.style.opacity = '1';
            content.classList.replace('scale-90', 'scale-100');
            content.classList.replace('opacity-0', 'opacity-100');
        }, 10);
    }

    function closeValidation() {
        const overlay = document.getElementById('validationOverlay');
        const content = document.getElementById('validationContent');

        overlay.style.opacity = '0';
        content.classList.replace('scale-100', 'scale-90');
        content.classList.replace('opacity-100', 'opacity-0');
        setTimeout(() => {
            overlay.classList.add('hidden');
        }, 300);
    }

    async function submitOrder() {
        const trigramme = "<?php echo $_SESSION['trigramme'] ?? 'ABC'; ?>"
        const totalItems = Object.values(cart).reduce((sum, qty) => sum + qty, 0);

        if (totalItems === 0) {
            showNotification('Votre panier est vide !', 'error');
            return;
        }

        if (trigramme.length !== 3) {
            showNotification('Veuillez entrer un trigramme de 3 lettres', 'error');
            return;
        }

        const orderData = {
            trigramme: trigramme,
            items: cart,
        };

        try {
            const response = await fetch('api/submit-order.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(orderData)
            });

            const result = await response.json();

            if (result.success) {
                showNotification('Commande validée ! 🍙', 'success');

                cart = {};
                updateCartUI();
                updateHeaderSubtitle();
                closeValidation();

            } else {
                showNotification('Erreur: ' + (result.error || 'Erreur inconnue'), 'error');
            }
        } catch (error) {
            console.error('Erreur lors de la soumission:', error);
            showNotification('❌ Erreur de connexion au serveur', 'error');
        }
    }

    // ==========================================
    // NOTIFICATIONS
    // ==========================================
    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        // Notification Brutaliste
        notification.className = `fixed top-24 left-1/2 transform -translate-x-1/2 px-6 py-4 border-2 border-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] z-50 transition-all duration-300 font-bold text-sm text-center uppercase tracking-wider`;

        switch (type) {
            case 'success':
                notification.classList.add('bg-[#22C55E]', 'text-black');
                break;
            case 'error':
                notification.classList.add('bg-[#E60012]', 'text-white');
                break;
            case 'warning':
                notification.classList.add('bg-black', 'text-white');
                break;
            default:
                notification.classList.add('bg-white', 'text-black');
        }

        notification.textContent = message;
        notification.style.opacity = '0';
        notification.style.transform = 'translate(-50%, -20px)';

        document.body.appendChild(notification);

        setTimeout(() => {
            notification.style.opacity = '1';
            notification.style.transform = 'translate(-50%, 0)';
        }, 10);

        setTimeout(() => {
            notification.style.opacity = '0';
            notification.style.transform = 'translate(-50%, -20px)';
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }

    // ==========================================
    // LANCEMENT
    // ==========================================
    init();
</script>