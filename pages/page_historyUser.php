<?php
// On importe uniquement le modèle commande
require_once 'orders/order.php';

// On récupère l'ID du user connecté depuis la session
$userId = $_SESSION['userId'] ?? null;

// Si le user n'est pas connecté, sécurité : on le renvoie à l'accueil
if (!$userId) {
    header("Location: index.php");
    exit;
}

// On récupère son historique grâce à la méthode optimisée (on met une limite haute, ex: 50)
$archivedOrders = Order::getUserRecentOrders($pdo, $userId, 50);
?>

<header class="flex items-center justify-between p-6 border-b-2 border-black bg-white">
    <div class="flex flex-col gap-1">
        <h3 class="text-[10px] text-black/50 tracking-widest font-bold">VOS ARCHIVES</h3>
        <h1 class="text-xl font-black italic text-black uppercase">VOTRE HISTORIQUE</h1>
    </div>
    <div class="w-16 h-12 bg-white flex items-center justify-center">
        <img src="images/logo.jpg" alt="Logo" class="object-cover w-full h-full">
    </div>
</header>

<main class="p-6 pb-28 h-full overflow-y-auto no-scrollbar bg-white">
    <div class="max-w-3xl mx-auto space-y-4">
        
        <?php if (empty($archivedOrders)): ?>
            <div class="p-10 border-4 border-black border-dashed text-center bg-zinc-50">
                <p class="font-black uppercase tracking-widest text-black/40">Vous n'avez passé aucune commande</p>
            </div>
        <?php else: ?>
            
            <?php 
            $compteur = count($archivedOrders);
            foreach ($archivedOrders as $order): 
                // Formatage de la date 
                $date = new DateTime($order->createdAt);
                $formattedDate = $date->format('d/m/Y à H:i');
                
                // Formatage du prix 
                $total = number_format($order->totalAmount ?? 0, 2, '.', '');
                
                // Récupération dynamique du détail des onigiris
                $itemsDetails = Order::getOrderItemsDetails($pdo, $order->id);
                $itemsStringArray = [];
                foreach ($itemsDetails as $item) {
                    $itemsStringArray[] = $item['quantity'] . 'x ' . $item['name'];
                }
                $details = implode(', ', $itemsStringArray);
                
                // Sécurité au cas où
                if (empty($details)) {
                    $details = "Détails non disponibles";
                }
            ?>
            
            <details class="group border-2 border-black bg-white shadow-[6px_6px_0px_0px_rgba(0,0,0,1)] transition-all overflow-hidden [&_summary::-webkit-details-marker]:hidden mb-4">
                
                <summary class="flex items-center justify-between p-5 cursor-pointer hover:bg-yellow-50 active:bg-yellow-100 transition-colors list-none outline-none">
                    <div class="flex flex-col">
                        <div class="flex items-center gap-3">
                            <span class="font-black text-xl uppercase">COMMANDE #<?= $compteur ?></span>
                        </div>
                        <span class="text-xs font-bold text-black/50 mt-1"><?= $formattedDate ?></span>
                    </div>
                    
                    <div class="flex items-center gap-4">
                        <span class="font-black text-2xl"><?= $total ?>€</span>
                        <div class="w-8 h-8 border-2 border-black flex items-center justify-center group-open:bg-black group-open:text-white transition-colors">
                            <i data-lucide="chevron-down" class="w-5 h-5 transition-transform duration-300 group-open:rotate-180"></i>
                        </div>
                    </div>
                </summary>
                
                <div class="p-5 border-t-2 border-black bg-zinc-50 flex flex-col gap-2">
                    <h4 class="text-[10px] font-black uppercase tracking-widest text-black/40 mb-1">Contenu de la commande</h4>
                    <p class="font-bold text-sm uppercase leading-relaxed text-black">
                        <?= htmlspecialchars($details) ?>
                    </p>
                    
                    <div class="mt-2 flex justify-end">
                        <a href="index.php?page=commandeUser&reorder=<?= $order->id ?>" class="text-[10px] font-black uppercase tracking-widest border-b-2 border-black pb-1 hover:text-[#E60012] hover:border-[#E60012] transition-colors flex items-center gap-2">
                            <i data-lucide="rotate-ccw" class="w-3 h-3"></i> Recommander
                        </a>
                    </div>
                </div>
                
            </details>
            
            <?php 
            // On décrémente le compteur pour la commande suivante (qui est plus ancienne)
            $compteur--;
            endforeach; ?>
        <?php endif; ?>
        
    </div>
</main>