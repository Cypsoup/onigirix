<?php

// Importation des fichiers
require_once 'config/db.php';
require_once 'recipes/recipe.php';
require_once 'recipes/recipeRenderer.php';
require_once 'orders/order.php';
require_once 'orders/orderRenderer.php';


// Configuration des commandes de la session
$orderConfig = [
    'attente' => ['title' => 'En Attente', 'class' => ''],
    'prepa' => ['title' => 'En Préparation', 'class' => ''],
    'pret' => ['title' => 'Prêts', 'class' => 'text-[#E60012]']
];


$_SESSION['event_id'] = 1;

?>

<!--- Affichage des commandes --->
<main class="flex-1 grid grid-cols-4 h-full">

    <?php foreach ($orderConfig as $status => $config): ?>
    <?php
    // Récupération des données pour chaque statut
    $orders = Order::getOrdersByStatus($pdo, $status);
    $count = count($orders);
    ?>

    <section class="flex flex-col column-divider h-full min-h-0">
        <div class="p-4 flex justify-between items-center border-b border-black/5">
            <h2 class="font-bold uppercase tracking-widest text-sm <?= $config['class'] ?>">
                <?= $config['title'] ?>
            </h2>
            <span class="font-black text-5xl <?= $config['class'] ?>">
                <?= $count ?>
            </span>
        </div>

        <div class="flex-1 overflow-y-auto p-4 space-y-4 no-scrollbar">
            <?php foreach ($orders as $order): ?>
            <?php orderRenderer::renderOrderCard($pdo, $order); ?>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endforeach; ?>

</main>


<aside class="bg-gray-50 flex flex-col p-4 gap-4 h-full border-l border-black/5">
    <div class="bg-white border border-black p-4">
        <div class="flex gap-4 border-b border-black/10 mb-4 text-xs font-bold">
            <button id="btn-prepa" class="pb-2 text-black border-b-2 border-black transition-colors">
                EN PRÉPARATION
            </button>

            <button id="btn-attente"
                class="pb-2 text-black/40 border-b-2 border-transparent hover:text-black transition-colors">
                EN ATTENTE
            </button>
        </div>

        <div id="stats-container">
            <div id="content-prepa" class="space-y-2 text-sm overflow-y-auto">
                <?php
                $stats = Order::getStatsByStatus($pdo, 'prepa');
                OrderRenderer::renderStats($stats);
                ?>
            </div>

            <div id="content-attente" class="hidden space-y-2 text-sm overflow-y-auto">
                <?php
                $stats = Order::getStatsByStatus($pdo, 'attente');
                OrderRenderer::renderStats($stats);
                ?>
            </div>
        </div>
    </div>


    <div id="archived-orders-container"
        class="bg-white border border-black p-4 flex-none overflow-hidden flex flex-col transition-all duration-300">
        <button id="btn-toggle-archived-orders"
            class="flex justify-between items-center w-full font-bold text-xs uppercase mb-0 group">
            Commandes retirées
            <i id="archived-orders-icon" data-lucide="chevron-down"
                class="w-5 h-5 transition-transform duration-300"></i>
        </button>
        <div id="archived-orders-list" class="hidden text-xs text-black/40 space-y-2 overflow-y-auto mt-4">
            <?php
            $archivedOrders = Order::getOrdersByStatus($pdo, 'archive');
            if ($archivedOrders) {
                foreach ($archivedOrders as $order) {
                    OrderRenderer::renderArchivedOrder($pdo, $order);
                }
            }
            ?>
        </div>
    </div>

    <div class="bg-white border border-black p-4 space-y-3">
        <button onclick="togglePanel()"
            class="w-full py-3 bg-zinc-800 text-white font-bold text-sm rounded flex items-center justify-center gap-2 hover:bg-black transition-colors">
            <i data-lucide="plus-circle" class="w-4 h-4 mt-0.5"></i> AJOUTER COMMANDE
        </button>
        <div class="flex gap-2">
            <button class="flex-1 py-2 border border-black flex justify-center hover:bg-gray-100"><i data-lucide="pause"
                    class="w-4 h-4"></i></button>
            <button class="flex-1 py-2 bg-black text-white flex justify-center hover:bg-zinc-800"><i data-lucide="power"
                    class="w-4 h-4"></i></button>
        </div>
    </div>
</aside>
</main>

<div id="overlay"
    class="fixed inset-0 bg-black/40 backdrop-blur-sm z-30 hidden opacity-0 transition-opacity duration-300"></div>

<div id="slideOver"
    class="fixed top-0 right-0 h-full w-[400px] bg-white z-40 translate-x-full transition-transform duration-300 ease-in-out border-l-4 border-black shadow-2xl p-8">
    <div class="flex justify-between items-center mb-10">
        <h2 class="text-2xl font-black uppercase italic">Nouvelle Commande</h2>
        <button onclick="togglePanel()" class="hover:rotate-90 transition-transform"><i data-lucide="x"></i></button>
    </div>

    <form id="orderForm" class="space-y-8">
        <div>
            <label class="block text-xs font-bold uppercase mb-2">Trigramme</label>
            <input id="trigramme" name="trigramme" type="text" maxlength="3" required
                class="w-full border-b-2 border-black text-3xl font-black focus:outline-none focus:border-[#E60012] uppercase placeholder-black/20"
                placeholder="ABC">
        </div>

        <div class="space-y-4">
            <label class="block text-xs font-bold uppercase">Onigiris</label>

            <?php
            $recipes = getAllRecipes($pdo);

            foreach ($recipes as $id => $recipe) {
                echo renderRecipeRow($id, $recipe);
            }
            ?>
        </div>

        <button type="submit"
            class="w-full py-4 bg-black text-white font-bold tracking-widest mt-10 hover:bg-[#E60012] transition-colors">
            VALIDER LA COMMANDE
        </button>
    </form>
</div>