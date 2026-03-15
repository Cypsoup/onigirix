export class Cart {
    constructor(menuItems, maxItems = 4) {
        this.items = {}; // Format: { "id_recette": quantite }
        this.menuItems = menuItems;
        this.maxItems = maxItems;
    }

    // Méthode pour obtenir les infos d'une recette précise (pour le Drawer)
    getItemDetails(itemId) {
        return this.menuItems.find(item => item.id == itemId);
    }

    // Méthode pour compter le total d'articles
    getTotalQuantity() {
        return Object.values(this.items).reduce((sum, qty) => sum + qty, 0);
    }

    // Obtenir la quantité d'un article spécifique
    getItemQuantity(itemId) {
        return this.items[itemId] || 0;
    }

    // Méthode pour calculer le prix total
    getTotalPrice() {
        let total = 0;
        for (let id in this.items) {
            const item = this.getItemDetails(id);
            if (item) {
                total += parseFloat(item.price) * this.items[id];
            }
        }
        return total;
    }

    // Méthode intelligente pour ajouter/retirer
    changeQuantity(itemId, delta) {
        const currentQty = this.items[itemId] || 0;
        const totalItems = this.getTotalQuantity();

        if (totalItems >= this.maxItems && delta > 0) return { success: false, error: 'max_reached' };
        if (currentQty === 0 && delta < 0) return { success: false, error: 'already_empty' };

        const newQty = currentQty + delta;
        
        if (newQty === 0) {
            delete this.items[itemId];
        } else {
            this.items[itemId] = newQty;
        }

        return { success: true, newQty: newQty };
    }

    // Méthode pour vider complètement le panier (après la commande)
    clearCart() {
        this.items = {};
    }

    // Méthode pour exporter les données pour la base de données (le fetch)
    getExportData() {
        return this.items;
    }


}