<?php

require_once 'recipes/recipe.php';
require_once 'recipes/recipeRenderer.php';
require_once 'orders/cartRenderer.php';
require_once 'orders/order.php';
require_once 'users/users.php';
require_once 'events/event.php';

// Récupération de l'event actif
$activeEvent = Event::getOpenEvent($pdo);
if (!$activeEvent) {
    // Si pas d'événement, on redirige avec une erreur
    Flash::error("Aucune vente d'onigiris n'est en cours !");
    header("Location: index.php?page=dashboardUser");
    exit;
}

// On vérifie que les commandes sont ouvertes
if (!$activeEvent->canOrder) {
    Flash::error("Impossible de commander pour cette session !");
    header("Location: index.php?page=dashboardUser");
    exit;
}

// --- GESTION DE LA RECOMMANDATION ---
$reorderItemsJson = '[]'; // Par défaut, rien à recommander
$reorderStatus = "error";

if (isset($_GET['reorder'])) {
    $reorderId = (int)$_GET['reorder'];
    $oldItems = Order::getOrderItems($pdo, $reorderId); 
    
    $validItems = [];
    if ($oldItems) {
        foreach ($oldItems as $item) {
            // On vérifie que la recette est toujours disponible (available == 1)
            if (isset($item->available) && $item->available == 1) {
                $validItems[] = [
                    'id' => (int)$item->recipeId,
                    'quantity' => (int)$item->quantity
                ];
            }
        }
    }
    
    // Si on a trouvé des produits valides, on les prépare pour le Javascript
    if (!empty($validItems)) {
        $reorderItemsJson = json_encode($validItems);
        $reorderStatus = '"success"';
    } else {
        $reorderStatus = '"error"';
    }
}
// ==========================================

// 1. Récupération des recettes depuis la BDD
$recipes = Recipe::getAllRecipes($pdo, 1) ?? [];
$recipesJSON = json_encode($recipes); // On prépare le JSON pour le Javascript

// 2. Récupération des infos de l'utilisateur
$userId = $_SESSION['userId'] ?? null;
$user = User::getUserById($pdo, $userId);
$trigramme = $user ? htmlspecialchars($user->trigramme) : 'ABC';

// 3. Affichage de la page via le CartRenderer
CartRenderer::renderHeader();
CartRenderer::renderMenu($recipes);
CartRenderer::renderDrawer();
CartRenderer::renderFooter();
CartRenderer::renderValidationModal($trigramme);
?>

<script>
    window.ONIGIRIX_MENU = <?= $recipesJSON ?>;
    window.ONIGIRIX_USER = "<?= $trigramme ?>";

    // On passe les produits à recommander au Javascript
    window.REORDER_DATA = <?= $reorderItemsJson ?>;
</script>

<script type="module" src="js/cart/cartApp.js"></script>