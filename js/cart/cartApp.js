import { Cart } from './Cart.js';
import { showToast } from '../utils.js'; 

document.addEventListener("DOMContentLoaded", () => {
    // ==========================================
    // 1. INITIALISATION
    // ==========================================
    // On récupère les variables PHP injectées dans la page
    const menuItems = window.ONIGIRIX_MENU || [];
    const trigrammeUser = window.ONIGIRIX_USER || "ABC";
    
    // On instancie notre panier
    const cart = new Cart(menuItems, 4);
    let currentDrawerItemId = null; // Pour savoir quel onigiri est affiché en grand

    // GESTION DE LA RECOMMANDATION
    if (window.REORDER_DATA && window.REORDER_DATA.length > 0) {
        
        // 1. Vider le panier actuel pour éviter les doublons
        cart.clearCart();

        // 2. Ajouter chaque produit au panier
        window.REORDER_DATA.forEach(item => {
            cart.changeQuantity(item.id, item.quantity);
        });

        // 3. Rafraîchir l'affichage du panier
        updateUI();

        setTimeout(() => {
            openValidation();
            showToast('Votre ancienne commande est prête ! 🍙', 'success');
        }, 300); // Petit délai pour que l'animation soit fluide
    } else if (window.REORDER_STATUS === 'error') {
        showToast("Certains onigiris ne sont plus disponibles aujourd'hui.", "error");
    }

    // ==========================================
    // 2. ÉCOUTEURS D'ÉVÉNEMENTS
    // ==========================================
    document.addEventListener("click", (event) => {
        
        // Clic sur le bouton + ou - dans le Drawer
        const btnQty = event.target.closest(".js-btn-qty");
        if (btnQty) {
            event.stopPropagation(); // Empêche d'ouvrir le drawer si on clique sur le bouton +

            // L'ID vient du bouton (sur la carte) OU du currentDrawerItemId (si on est dans le drawer)
            const id = btnQty.dataset.id || currentDrawerItemId; 
            const delta = parseInt(btnQty.dataset.delta);
            
            const result = cart.changeQuantity(id, delta);
            if (result.success) {
                updateUI();
            } else if (result.error === 'max_reached') {
                showToast("Pas plus de 4 onigiris !", "error");
            }
            return; // On arrête là pour ce clic
        }

        // Clic sur le bouton "Ajouter à la commande" ou fermer le drawer
        if (event.target.closest(".js-close-drawer") || event.target.id === "drawerOverlay") {
            closeDrawer();
            return;
        }

        // Clic sur "SUIVANT" dans le footer
        if (event.target.closest(".js-open-validation")) {
            if (cart.getTotalQuantity() > 0) openValidation();
            else showToast("Votre panier est vide", "error");
            return;
        }

        // Clic sur "Fermer" la modale de validation
        if (event.target.closest(".js-close-validation")) {
            closeValidation();
            return;
        }

        // Clic sur "VALIDER" la commande
        if (event.target.closest(".js-submit-order")) {
            submitOrder();
            return;
        }

        // Clic sur une carte Onigiri pour l'ouvrir (le Drawer)
        const recipeCard = event.target.closest(".js-open-recipe");
        if (recipeCard) {
            const recipeId = recipeCard.dataset.id;
            openDrawer(recipeId);
        }
    });

    // ==========================================
    // 3. FONCTIONS D'AFFICHAGE (UI)
    // ==========================================
    function updateUI() {
        const totalItems = cart.getTotalQuantity();
        const totalPrice = cart.getTotalPrice();

        // Mise à jour du sous-titre du Header
        document.getElementById('headerSubtitle').textContent = `Cliquez pour ajouter vos onigiris (${totalItems} / 4)`;

        // Mise à jour de la quantité dans le Drawer (s'il est ouvert)
        if (currentDrawerItemId) {
            const drawerQtyEl = document.getElementById('drawerQty');
            if (drawerQtyEl) drawerQtyEl.textContent = cart.getItemQuantity(currentDrawerItemId);
        }

        // Mise à jour des petits badges de quantité sur les cartes du menu
        document.querySelectorAll('.js-card-qty').forEach(el => {
            const id = el.dataset.id;
            el.textContent = cart.getItemQuantity(id);
        });

        // Mise à jour du Footer
        document.getElementById('cartItemsCount').textContent = `${totalItems} Onigiri${totalItems > 1 ? 's' : ''} sélectionné${totalItems > 1 ? 's' : ''}`;
        document.getElementById('cartTotal').textContent = `${totalPrice.toFixed(2)}€`;
        
        const footer = document.getElementById('cartFooter');
        if (totalItems > 0) footer.classList.add('visible');
        else footer.classList.remove('visible');
    }

    // ==========================================
    // 4. ANIMATIONS DU DRAWER ET MODALES
    // ==========================================
    function openDrawer(itemId) {
        currentDrawerItemId = itemId;
        const item = cart.getItemDetails(itemId);

        const imagePath = item.fileName ? `images/recipeImages/${item.fileName}` : 'images/onigiri.png';
        const description = item.description ? item.description : 'Délicieux onigiri préparé avec soin.';

        // On remplit le HTML
        document.getElementById('drawerImage').src = imagePath;
        document.getElementById('drawerName').textContent = item.name;
        document.getElementById('drawerPrice').textContent = `${parseFloat(item.price).toFixed(2)}€`;
        document.getElementById('drawerDescription').textContent = description;
        document.getElementById('drawerQty').textContent = cart.getItemQuantity(itemId);

        // Animation Tailwind (on utilise void pour forcer le recalcul du style et permettre la transition)
        const drawer = document.getElementById('drawer');
        const overlay = document.getElementById('drawerOverlay');
        const content = document.getElementById('drawerContent');

        // 1. On affiche la boîte (elle est toujours en bas, invisible)
        drawer.classList.remove('hidden');

        // 2. On force le navigateur à recalculer l'affichage
        void drawer.offsetWidth;

        // 3. Maintenant on la fait glisser vers le haut
        overlay.style.opacity = '1';
        content.classList.remove('translate-y-full');
    }

    function closeDrawer() {
        const drawer = document.getElementById('drawer');
        const overlay = document.getElementById('drawerOverlay');
        const content = document.getElementById('drawerContent');

        // 1. On fait glisser vers le bas
        overlay.style.opacity = '0';
        content.classList.add('translate-y-full');

        // 2. On attend la fin de l'animation (300ms) pour remettre le hidden
        setTimeout(() => {
            drawer.classList.add('hidden');
            currentDrawerItemId = null;
        }, 300);
    }

    function openValidation() {
        // Construction de la liste récapitulative
        const itemsListContainer = document.getElementById('finalItemsList');
        itemsListContainer.innerHTML = ''; 

        for (let id in cart.items) {
            const qty = cart.items[id];
            const item = cart.getItemDetails(id);
            const itemTotalPrice = item.price * qty;

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

        document.getElementById('finalItemsCount').textContent = cart.getTotalQuantity();
        document.getElementById('finalTotal').textContent = `${cart.getTotalPrice().toFixed(2)}€`;

        // Animations Tailwind
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

    // ==========================================
    // 5. ENVOI À LA BASE DE DONNÉES
    // ==========================================
    async function submitOrder() {
        if (cart.getTotalQuantity() === 0) {
            showToast('Votre panier est vide !', 'error');
            return;
        }

        const orderData = {
            trigramme: trigrammeUser,
            items: cart.getExportData(),
        };

        try {
            const response = await fetch('api/submit-order.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(orderData)
            });

            const result = await response.json();

            if (result.success) {
                showToast('Commande validée ! 🍙', 'success');
                cart.clearCart(); // On vide le modèle
                updateUI(); // On vide la vue
                closeValidation();
                window.location.href = "index.php?page=dashboardUser";
            } else {
                showToast('Erreur: ' + (result.error || 'Erreur inconnue'), 'error');
            }
        } catch (error) {
            console.error('Erreur lors de la soumission:', error);
            showToast('❌ Erreur de connexion au serveur', 'error');
        }
    }

    // Lancement de l'UI au démarrage
    updateUI();
});