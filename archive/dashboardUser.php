<?php
session_start();
require_once '../config/db.php';
require_once '../utils/LayoutRenderer.php'; // Votre nouvelle classe unifiée !
require_once '../orders/order.php';
require_once '../orders/orderRenderer.php';

$activePage = 'dashboardUser';

// 1. Récupération de l'utilisateur (On simule l'ID 1 si non connecté pour l'instant)
$userId = $_SESSION['user_id'] ?? 1;
$userName = $_SESSION['user_name'] ?? 'Alex'; // À adapter selon votre BDD

// 2. Récupération des données dynamiques depuis la BDD (Modèle)
$activeOrder = Order::getUserActiveOrder($pdo, $userId); // Commande en cours
$recentOrders = Order::getUserRecentOrders($pdo, $userId, 3); // 3 dernières commandes
$userStats = Order::getUserStats($pdo, $userId); // Statistiques

// 3. Affichage (Vue)
LayoutRenderer::generateHTMLHeader("Suivi - Onigirix");
?>

    <header class="flex items-center justify-between p-6 border-b-2 border-black">
        <div class="flex flex-col gap-1">
            <h3 class="text-[10px] text-black/50">BIENVENUE</h3>
            <h1 class="text-xl font-black italic text-black uppercase">KON'NICHIWA, <?= htmlspecialchars($userName) ?>!</h1>
        </div>
        <div class="w-16 h-12 bg-white flex items-center justify-center">
            <img src="images/logo.jpg" alt="Logo" class="object-cover w-full h-full">
        </div>
    </header>
    
    <main class="p-6">
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

<?php 
// Affichage du menu mobile
LayoutRenderer::renderBottomNav($activePage); 
LayoutRenderer::generateHTMLFooter(); 
?>