<?php
session_start();
// Importation des fichiers
require_once 'config/db.php';
require_once 'includes/functions.php';


$activePage = 'orderUser';
$user_access = 1;
$user_connected = 1;

// Récupération des recettes depuis la base de données
$recipes = getAllRecipes($pdo);
$recipesJSON = getRecipesAsJSON($pdo);

// HTML Header
// generateHTMLHeader(getPageTitle($activePage));

// Sidebar
// generateSidebar($activePage, $user_access, $user_connected)

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commande Onigiri</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .gradient-red {
            background: linear-gradient(135deg, #E60012 0%, #B80010 100%);
        }
        
        .gradient-red-glossy {
            background: linear-gradient(180deg, #FF1122 0%, #E60012 50%, #B80010 100%);
        }
        
        
        .cart-footer {
            transition: transform 0.3s ease, opacity 0.3s ease;
            transform: translateY(100%);
            opacity: 0;
        }
        
        .cart-footer.visible {
            transform: translateY(0);
            opacity: 1;
        }
        
        .btn-press:active {
            transform: scale(0.95);
        }
        
        input[type="text"]:focus {
            outline: none;
        }
    </style>
</head>
<body class="bg-white min-h-screen pb-32 font-sans">

    <!-- Header -->
    <header class="sticky top-0 bg-white/80 backdrop-blur-md z-40 px-5 py-5 border-b border-black/10">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-black text-black tracking-tight">Onigiris</h1>
                <p class="text-xs text-black/50 mt-0.5" id="headerSubtitle">Cliquez pour ajouter vos onigiris (0 / 4)</p>
            </div>
             <div class="h-14 flex items-center justify-center">
                <img src="images/logo.png" alt="Logo" class="object-cover w-full h-full">
            </div>
        </div>
    </header>

    <!-- Menu Grid -->
    <main class="px-5 py-6">
        <div class="grid grid-cols-2 gap-4" id="menuGrid">
            <?php 
            if ($recipes && count($recipes) > 0) {
                foreach ($recipes as $recipe) {
                    renderMenuCard($recipe);
                }
            }
            else {
                echo <<<end
                <p class="col-span-2 text-center text-black/40 font-bold py-10 uppercase tracking-widest">
                    Aucun onigiri au menu aujourd'hui...
                </p>
                end;
            }
            ?>
        </div>
    </main>

    <!-- Bottom Drawer (Détails) -->
    <div id="drawer" class="fixed inset-0 z-50 hidden">
        <!-- Overlay -->
        <div id="drawerOverlay" class="absolute inset-0 bg-black/60 backdrop-blur-sm transition-opacity duration-300 ease-out" onclick="closeDrawer()"></div>
        
        <!-- Drawer Content -->
        <div id="drawerContent" class="absolute bottom-0 left-0 right-0 bg-white rounded-t-[24px] max-h-[85vh] overflow-y-auto translate-y-full transition-transform duration-300 ease-out">
            <!-- Handle -->
            <div class="flex justify-center pt-3 pb-2">
                <div class="w-12 h-1.5 bg-black/20 rounded-full"></div>
            </div>
            
            <!-- Content -->
            <div class="px-6 pb-8">
                <!-- Image -->
                <div class="w-full aspect-square bg-black/5 rounded-xl flex items-center justify-center mb-3 overflow-hidden">
                    <img src="images/onigiri.png" id="drawerImage" alt="Onigiri" class="w-full h-full object-cover">
                </div>
                
                <!-- Nom -->
                <h2 class="text-2xl font-black text-black text-center mb-2" id="drawerName">Sake Onigiri</h2>
                
                <!-- Prix -->
                <p class="text-lg font-bold text-center mb-4" style="color: #E60012;" id="drawerPrice">3.50€</p>
                
                <!-- Description -->
                <div class="bg-black/5 rounded-xl p-4 mb-6">
                    <p class="text-sm text-black/70 leading-relaxed" id="drawerDescription">
                        Saumon grillé mariné, riz japonais vinaigré, algue nori croustillante.
                    </p>
                </div>
                
                <!-- Ingrédients -->
                <!-- <div class="mb-8">
                    <h3 class="text-xs font-bold text-black/50 uppercase tracking-wider mb-3">Ingrédients</h3>
                    <div class="flex flex-wrap gap-2" id="drawerIngredients">
                    </div>
                </div> -->
                
                <!-- Sélecteur de quantité -->
                <div class="flex items-center justify-center gap-6 mb-6">
                    <button onclick="decrementDrawerQty()" class="w-12 h-12 bg-black/5 border-[1.5px] border-black rounded-xl flex items-center justify-center btn-press transition-all hover:bg-black/10">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 12H4"/>
                        </svg>
                    </button>
                    
                    <div class="text-4xl font-black text-black w-16 text-center" id="drawerQty">1</div>
                    
                    <button onclick="incrementDrawerQty()" class="w-12 h-12 gradient-red-glossy border-[1.5px] border-black/10 rounded-xl flex items-center justify-center btn-press transition-all text-white shadow-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                        </svg>
                    </button>
                </div>
                
                <!-- Bouton Ajouter -->
                <button onclick="addToCart()" class="w-full gradient-red-glossy text-white font-bold py-4 rounded-xl btn-press transition-all border-[1.5px] border-black/10 shadow-xl">
                    Ajouter à ma commande
                </button>
            </div>
        </div>
    </div>

    <!-- Sticky Cart Footer -->
    <div id="cartFooter" class="cart-footer fixed bottom-0 left-0 right-0 bg-black px-5 py-4 z-30 rounded-t-[20px]">
        <div class="flex items-center justify-between mb-3">
            <div>
                <p class="text-white/60 text-xs font-medium" id="cartItemsCount">0 Onigiris sélectionnés</p>
                <p class="text-white text-xl font-black mt-0.5" id="cartTotal">0.00€</p>
            </div>
            <button onclick="openValidation()" class="gradient-red-glossy text-white font-bold px-8 py-3 rounded-xl btn-press transition-all border-[1.5px] border-white/10 shadow-lg">
                Suivant
            </button>
        </div>
    </div>

    <!-- Validation Overlay -->
    <div id="validationOverlay" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-md hidden flex items-center justify-center px-5 transition-opacity duration-300 ease">
        <div id="validationContent" class="bg-white rounded-2xl p-8 w-full max-w-md scale-90 opacity-0 transition-all duration-300 ease-out">
            <!-- Close Button -->
            <button onclick="closeValidation()" class="absolute top-4 right-4 w-10 h-10 bg-black/5 rounded-full flex items-center justify-center btn-press">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
            
            <!-- Content -->
            <div class="text-center mb-8">
                <div class="w-20 h-20 mx-auto bg-black/5 rounded-2xl flex items-center justify-center mb-4">
                    <span class="text-5xl">✨</span>
                </div>
                <h2 class="text-2xl font-black text-black mb-2">Finaliser la commande</h2>
                <p class="text-sm text-black/50">Vérifie bien ta commande et ton trigramme avant de valider</p>
            </div>
            
            <!-- Trigramme -->
            <div class="mb-6">
                <span class="text-xs font-bold text-black/50 uppercase tracking-wider mb-2 block">Ton Trigramme</span>
                <div class="w-full text-center text-5xl font-black text-black bg-black/5 border-[1.5px] border-black rounded-xl py-4 uppercase tracking-wider">
                    <?= $_SESSION['trigramme'] ?? 'ABC' ?>
                </div>
            </div>
            
            
            <!-- Récapitulatif -->
            <div class="bg-black/5 rounded-xl p-4 mb-6">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm text-black/60">Articles</span>
                    <span class="text-sm font-bold text-black" id="finalItemsCount">0</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-black/60">Total</span>
                    <span class="text-lg font-black text-black" id="finalTotal">0.00€</span>
                </div>
            </div>
            
            <!-- Bouton Final -->
            <button onclick="submitOrder()" class="w-full gradient-red-glossy text-white font-black py-4 rounded-xl btn-press transition-all border-[1.5px] border-white/10 shadow-xl text-lg">
                VALIDER LA COMMANDE
            </button>
        </div>
    </div>

    <script>
        // ==========================================
        // DONNÉES DEPUIS LA BASE DE DONNÉES
        // ==========================================

        // Récupération des recettes depuis PHP
        const menuItems = Object.values(<?= $recipesJSON ?>);
        console.log('Menu Items:', menuItems); // Vérification des données dans la console
        

        // ==========================================
        // ETAT GLOBAL
        // ==========================================
        let cart = {}; // Structure : { itemId: quantity, ... }
        let currentItem = null;
        let drawerQuantity = 0;
        const MAX_ITEMS = 4; // Limite de 4 onigiris par commande

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
        // Change quantity
        function changeQty(itemId, delta) {
            // let totalItems = 0;
            // for (let id in cart) {
            //     totalItems += cart[id];
            // }
            // Version plus courte pour calculer le total d'items dans le panier
            const totalItems = Object.values(cart).reduce((sum, qty) => sum + qty, 0);

            if (totalItems >= MAX_ITEMS && delta > 0) {
                showNotification('Vous avez atteint la limite de ' + MAX_ITEMS + ' onigiris', 'warning');
                return;
            }

            if (totalItems <= 0 && delta < 0) {
                showNotification('Votre panier est vide', 'warning');
                return;
            }

            if (!cart[itemId]) {
                cart[itemId] = 0;
            }
            cart[itemId] += delta;
            updateCartUI();
            updateCartUI();
            updateHeaderSubtitle();
        }

        // Ajouter au panier depuis le drawer
        function addToCart() {
            const totalItems = Object.values(cart).reduce((sum, qty) => sum + qty, 0);

            if (totalItems + drawerQuantity > MAX_ITEMS) {
                const remaining = MAX_ITEMS - totalItems;
                showNotification(`Vous ne pouvez ajouter que ${remaining} onigiri(s) de plus`, 'warning');
                return;
            }

            if (!cart[currentItem.id]) {
                cart[currentItem.id] = 0;
            }

            cart[currentItem.id] += drawerQuantity;
            updateCartUI();
            updateHeaderSubtitle();
            closeDrawer();
        }

        // Mise à jour de l'UI du panier
        function updateCartUI() {
            const totalItems = Object.values(cart).reduce((sum, qty) => sum + qty, 0);
            let totalPrice = 0;
            for (let id in cart) {
                const qty = cart[id];
                // On cherche l'onigiri correspondant dans le menu pour avoir son prix
                const item = menuItems.find(i => i.id == id); // menuItems est un tableau JS créé à partir des recettes PHP
                
                if (item) {
                    totalPrice += item.price * qty;
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

        // Mise à jour du sous-titre du header
        function updateHeaderSubtitle() {
            const totalItems = Object.values(cart).reduce((sum, qty) => sum + qty, 0);
            document.getElementById('headerSubtitle').textContent = 
                `Cliquez pour ajouter vos onigiris (${totalItems} / ${MAX_ITEMS})`;
        }


        // ==========================================
        // GESTION DU DRAWER
        // ==========================================

        // Ouvrir le drawer
        function openDrawer(itemId) {
            currentItem = menuItems.find(i => i.id == itemId); // On trouve l'onigiri sélectionné dans le tableau menuItems
            
            if (!currentItem) {
                console.error('Recette non trouvée:', itemId);
                return;
            }

            document.getElementById('drawerImage').src = currentItem.image
            document.getElementById('drawerImage').alt = `${currentItem.name} Onigiri`;
            document.getElementById('drawerName').textContent = `${currentItem.name} Onigiri`;
            document.getElementById('drawerPrice').textContent = `${currentItem.price.toFixed(2)}€`;
            document.getElementById('drawerDescription').textContent = currentItem.description;
            document.getElementById('drawerQty').textContent = drawerQuantity;
            
            // Ingrédients
            // const ingredientsHTML = currentItem.ingredients.map(ing => 
            //     `<span class="px-3 py-1.5 bg-black/5 border border-black/10 rounded-lg text-xs font-medium text-black">${ing}</span>`
            // ).join('');
            // document.getElementById('drawerIngredients').innerHTML = ingredientsHTML;
            
            const drawer = document.getElementById('drawer');
            drawer.classList.remove('hidden');
            // Petite temporisation pour permettre à la classe "hidden" de s'appliquer avant de lancer l'animation
            setTimeout(() => {
                document.getElementById('drawerOverlay').style.opacity = '1';
                document.getElementById('drawerContent').classList.remove('translate-y-full');
            }, 10);
        }

        // Fermer le drawer
        function closeDrawer() {
            const drawer = document.getElementById('drawer');
            document.getElementById('drawerOverlay').style.opacity = '0';
            document.getElementById('drawerContent').classList.add('translate-y-full');
            setTimeout(() => {
                drawer.classList.add('hidden');
            }, 300);
        }

        // Incrémenter quantité drawer
        function incrementDrawerQty() {
            const totalItems = Object.values(cart).reduce((sum, qty) => sum + qty, 0);
            
            if (totalItems + drawerQuantity >= MAX_ITEMS) {
                showNotification('Limite de ' + MAX_ITEMS + ' onigiris atteinte', 'warning');
                return;
            }

            drawerQuantity++;
            document.getElementById('drawerQty').textContent = drawerQuantity;
        }

        // Décrémenter quantité drawer
        function decrementDrawerQty() {
            if (drawerQuantity >= 1) {
                drawerQuantity--;
                document.getElementById('drawerQty').textContent = drawerQuantity;
            }
        }



        // ==========================================
        // GESTION DE LA VALIDATION
        // ==========================================

        // Ouvrir la validation
        function openValidation() {
            const totalItems = Object.values(cart).reduce((sum, qty) => sum + qty, 0);
            let totalPrice = 0;
            for (let id in cart) {
                const qty = cart[id];
                const item = menuItems.find(i => i.id == id);
                if (item) {
                    totalPrice += item.price * qty;
                }
            }

            if (totalItems === 0) {
                showNotification('Votre panier est vide', 'warning');
                return;
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

        // Fermer la validation
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

        // Soumettre la commande
        async function submitOrder() {
            const trigramme = "<?php echo $_SESSION['trigramme'] ?? 'ABC'; ?>" // Récupération du trigramme depuis la session PHP, ou valeur par défaut
            const totalItems = Object.values(cart).reduce((sum, qty) => sum + qty, 0);

            if (totalItems === 0) {
                showNotification('Votre panier est vide !', 'error');
                return;
            }

            if (trigramme.length !== 3) {
                showNotification('Veuillez entrer un trigramme de 3 lettres', 'error');
                return;
            }

            // Préparation des données pour l'API
            const orderData = {
                trigramme: trigramme,
                items: cart,
            };


            try {
                // Envoi de la commande à l'API
                const response = await fetch('api/submit-order.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(orderData) // Envoi des données de la commande au format JSON à l'API
                });

                const result = await response.json(); // On attend la réponse de l'API et on la parse en JSON

                if (result.success) {
                    // Succès
                    showNotification('Commande validée avec succès ! 🍙', 'success');
                    
                    // Reset
                    cart = {};
                    updateCartUI();
                    updateHeaderSubtitle();
                    closeValidation();
                    
                    // Redirection optionnelle
                    // setTimeout(() => {
                    //     window.location.href = 'order-success.php?id=' + result.orderId;
                    // }, 2000);
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
        
        function showNotification(message, type='info') {
            // Création d'une notification simple
            const notification = document.createElement('div');
            notification.className = `fixed top-20 left-1/2 transform -translate-x-1/2 px-6 py-3 rounded-xl shadow-lg z-50 transition-all duration-300 font-semibold text-sm text-center`;
            
            switch(type) {
                case 'success':
                    notification.classList.add('bg-green-500', 'text-white');
                    break;
                case 'error':
                    notification.classList.add('bg-red-500', 'text-white');
                    break;
                case 'warning':
                    notification.classList.add('bg-orange-500', 'text-white');
                    break;
                default:
                    notification.classList.add('bg-black', 'text-white');
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

</body>
</html>