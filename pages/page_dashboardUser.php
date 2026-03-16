<?php

require_once 'orders/order.php';
require_once 'orders/orderRenderer.php';
require_once 'users/users.php';
require_once 'events/event.php';

// Récupération de l'utilisateur
$userId = $_SESSION['userId'] ?? null;
$user = User::getUserById($pdo, $userId);

if (!$user) {
    // Si la session a expiré, on renvoie vers l'index qui redirigera vers login
    header("Location: index.php");
    exit;
}

// Initialisation des états
$activeEvent = Event::getOpenEvent($pdo);
$activeOrder = null;
$hasRetrievedOrder = false;

if ($activeEvent) {
    // A-t-il une commande en cours de préparation ?
    $activeOrder = Order::getUserActiveOrder($pdo, $userId, $activeEvent->id);
    
    // Si pas de commande en cours, a-t-il déjà récupéré sa commande aujourd'hui ?
    if (!$activeOrder) {
        $hasRetrievedOrder = Order::hasUserArchivedOrderForEvent($pdo, $userId, $activeEvent->id);
    }
}

// Récupération des données indépendantes de l'événement (Stats et Historique)
$recentOrders = Order::getUserRecentOrders($pdo, $userId, 3);
$userStats = Order::getUserStats($pdo, $userId);

?>

<header class="flex items-center justify-between p-6 border-b-2 border-black">
    <div class="flex flex-col gap-1">
        <h3 class="text-[10px] text-black/50">BIENVENUE</h3>
        <h1 class="text-xl font-black italic text-black uppercase">KON'NICHIWA,
            <?= htmlspecialchars($user->firstname) ?> !
        </h1>
    </div>
    <div class="w-16 h-12 bg-white flex items-center justify-center">
        <img src="images/logo.jpg" alt="Logo" class="object-cover w-full h-full">
    </div>
</header>

<main class="p-6 pb-28">
    <?php
    // Affichage conditionnel selon les 4 états possibles de l'utilisateur vis-à-vis de la session de vente en cours
    if (!$activeEvent) {
        // ÉTAT 1 : Fermé
        OrderRenderer::renderServiceClosed();
    } else {
        if ($activeOrder) {
            // ÉTAT 2 : A commandé, en attente de préparation
            OrderRenderer::renderUserActiveOrder($pdo, $activeOrder, $user);
        } elseif ($hasRetrievedOrder) {
            // ÉTAT 3 : A commandé et a déjà récupéré son plat
            OrderRenderer::renderUserOrderRetrieved();
        } elseif ($activeEvent->canOrder) {
            // ÉTAT 4 : Ouvert, mais n'a pas encore commandé
            OrderRenderer::renderUserNoOrder();
        } else { // ETAT 5: SERVICE EN PAUSE
            OrderRenderer::renderServiceClosed();
        }
    }

    // Affichage de l'historique
    // OrderRenderer::renderUserStats($userStats);
    OrderRenderer::renderUserRecentOrders($pdo, $recentOrders);
    ?>
</main>