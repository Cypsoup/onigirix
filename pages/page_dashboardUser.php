<?php

require_once 'orders/order.php';
require_once 'orders/orderRenderer.php';
require_once 'users/users.php';

// Récupération de l'utilisateur
$userId = $_SESSION['userId'] ?? null;
$user = User::getUserById($pdo, $userId);

if (!$user) {
    // Si la session a expiré, on renvoie vers l'index qui redirigera vers login
    header("Location: index.php");
    exit;
}

// Récupération du service en cours
$eventId = $_SESSION['eventId'];

// Récupération des données
$activeOrder = $eventId ? Order::getUserActiveOrder($pdo, $userId, $eventId) : null;
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
    // Affichage conditionnel de la commande en cours
    if ($activeOrder) {
        OrderRenderer::renderUserActiveOrder($activeOrder);
    } else {
        OrderRenderer::renderUserNoOrder();
    }

    // Affichage des statistiques
    OrderRenderer::renderUserStats($userStats);

    // Affichage de l'historique
    OrderRenderer::renderUserRecentOrders($pdo, $recentOrders);
    ?>
</main>