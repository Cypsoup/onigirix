<?php

require_once 'users/users.php';
require_once 'utils/flash.php';

class OrderRenderer
{

    public static function renderOrderCard($dbh, $order)
    {
        // --- PRÉPARATION ET SÉCURISATION DES DONNÉES ---
        // Récupération du trigramme
        $user = User::getUserById($dbh, $order->userId);
        if (!$user) {
            Flash::error("Impossible d'afficher la commande :" . $order->id);
        }
        $trigramme = $user->trigramme;

        // Récupération des informations de la commande
        $orderId = (int) $order->id;
        $status = htmlspecialchars($order->status ?? 'attente');
        $trigramme = htmlspecialchars($trigramme ?? '???');
        $timeAgo = round((time() - strtotime($order->createdAt)) / 60);
        $items = Order::getOrderItems($dbh, $orderId);

        // --- LOGIQUE D'AFFICHAGE ---
        // Initialisation du style par défaut
        $cardClass = "border-2 border-black p-4 relative bg-white transition-all shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]";
        $textClass = "text-black";
        $btnLabel = 'ARCHIVER';
        $btnClass = 'bg-black text-white hover:bg-[#E60012]';
        $isPret = ($status === 'pret');

        // Ajustements selon le statut
        if ($status === 'attente') {
            $btnLabel = 'PRÉPARER';
            $btnClass = 'bg-white text-black hover:bg-black hover:text-white';
        } elseif ($status === 'prepa') {
            $btnLabel = 'PRÊT !';
            $btnClass = 'bg-green-500 text-white hover:bg-green-600';
        } elseif ($isPret) {
            $cardClass = "border-2 border-[#E60012] p-4 relative bg-white";
            $textClass = "text-[#E60012]";
        }

        // Pré-génération du triangle visuel
        $badgePret = $isPret
            ? '<div class="absolute top-0 right-0 w-10 h-10 bg-[#E60012]/10 [clip-path:polygon(0_0,100%_0,100%_100%)]"></div>'
            : '';

        // --- PRÉPARATION DU CONTENU DES ITEMS ---
        $itemsHtml = "";
        foreach ($items as $item) {
            $recipe = Recipe::getRecipeById($dbh, $item->recipeId);
            $name = htmlspecialchars($recipe->name ?? 'Produit');
            $qty = (int) ($item->quantity ?? 1);
            $itemsHtml .= <<<HTML
                <li class="flex justify-between items-center text-sm">
                    <span class="font-medium">{$name}</span>
                    <span class="font-black bg-gray-100 px-1.5 py-0.5 rounded text-xs ml-2">x{$qty}</span>
                </li>
            HTML;
        }

        // --- RENDU FINAL ---
        echo <<<HTML
            <div class="{$cardClass}">
                {$badgePret}
                <div class="flex justify-between items-start mb-3">
                    <div class="text-3xl font-black uppercase tracking-tighter {$textClass}">
                        {$trigramme}
                    </div>
                    <div class="text-[10px] font-bold uppercase text-black/40">
                        {$timeAgo} MIN
                    </div>
                </div>

                <ul class="space-y-1 mb-6 border-t border-black/5 pt-3">
                    {$itemsHtml}
                </ul>

                <button 
                    data-order-id="{$orderId}" 
                    data-status="{$status}" 
                    class="js-status-btn w-full py-2 border-2 border-black text-xs font-black uppercase tracking-widest transition-all {$btnClass}">
                    {$btnLabel}
                </button>
            </div>
        HTML;
    }

    public static function renderArchivedOrder($dbh, $order)
    {
        // Récupération du trigramme
        $user = User::getUserById($dbh, $order->userId);
        if (!$user) {
            Flash::error("Impossible d'afficher la commande :" . $order->id);
        }
        $trigramme = $user->trigramme;

        // Information de la commande
        $orderId = (int) $order->id;
        $trigramme = htmlspecialchars($trigramme);
        $totalAmount = (int) number_format($order->totalAmount);

        // Affichage HTML
        echo <<<HTML
            <div class="flex justify-between border-b border-black/10 pb-1">
                <span># {$orderId} - {$trigramme}</span><span>{$totalAmount}€</span>
            </div>
        HTML;
    }

    public static function renderStats($stats)
    {
        if ($stats) {
            foreach ($stats as $stat) {
                $name = htmlspecialchars($stat['name']);
                $qty = (int) $stat['totalQty'];
                echo <<<HTML
                    <div class="flex justify-between items-center py-1 border-b border-black/5">
                        <span class="text-black/80"> {$name} </span>
                        <span class="font-bold text-base"> {$qty} </span>
                    </div>
                HTML;
            }
        }
    }

    // Affiche le bloc quand l'utilisateur a une commande en cours
    public static function renderUserActiveOrder($dbh, $order, $user) {
        // Récupération des données dynamiques
        $items = Order::getOrderItemsDetails($dbh, $order->id);
        $position = Order::getPositionInQueue($dbh, $order->eventId, $order->createdAt);
        $trigramme = strtoupper(htmlspecialchars($user->trigramme));

        // Gestion des statuts et de l'affichage
        $statusMap = [
            'attente' => ['text' => 'EN ATTENTE', 'step' => 1],
            'prepa'   => ['text' => 'EN PRÉPARATION', 'step' => 2],
            'pret'    => ['text' => 'PRÊT !', 'step' => 3]
        ];
        $current = $statusMap[$order->status] ?? $statusMap['attente'];
        $positionDisplay = ($order->status === 'pret') ? "GO!" : "#" . $position;

        // PRÉPARATION DES COULEURS
        // L'étape 1 (Horloge) est toujours active puisqu'on est au moins en "attente"
        $c1 = 'opacity-100 text-black';
        $b1 = 'bg-black';

        // L'étape 2 (Toque) est noire à partir de l'étape 2 (prepa), sinon grise
        $c2 = ($current['step'] >= 2) ? 'opacity-100 text-black' : 'text-zinc-300';
        $b2 = ($current['step'] >= 2) ? 'bg-black' : 'bg-zinc-200';

        // L'étape 3 (Check) est noire à partir de l'étape 3 (pret), sinon grise
        $c3 = ($current['step'] >= 3) ? 'opacity-100 text-black' : 'text-zinc-300';
        $b3 = ($current['step'] >= 3) ? 'bg-black' : 'bg-zinc-200';

        // Rendu HTML
        echo <<<HTML
        <section id="bloc-commande-en-cours" class="border-2 border-black bg-white p-4 shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] relative mb-10">
            <div class="flex justify-between items-start mb-6">
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 bg-[#E60012] animate-pulse"></div>
                    <span class="font-mono uppercase tracking-tighter text-sm font-bold">{$current['text']}</span>
                </div>
                <span class="font-mono uppercase tracking-tighter text-sm text-zinc-400">{$trigramme}</span>
            </div>

            <div class="flex justify-between mb-4 border-b-2 border-black border-dotted pb-6 px-2 gap-2">
                <div class="flex flex-col items-center gap-2 flex-1 {$c1}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock" aria-hidden="true">
                        <path d="M12 6v6l4 2"></path>
                        <circle cx="12" cy="12" r="10"></circle>
                    </svg>
                    <div class="h-1 w-full {$b1}"></div>
                </div>
                <div class="flex flex-col items-center gap-2 flex-1 {$c2}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chef-hat" aria-hidden="true">
                        <path d="M17 21a1 1 0 0 0 1-1v-5.35c0-.457.316-.844.727-1.041a4 4 0 0 0-2.134-7.589 5 5 0 0 0-9.186 0 4 4 0 0 0-2.134 7.588c.411.198.727.585.727 1.041V20a1 1 0 0 0 1 1Z"></path>
                        <path d="M6 17h12"></path>
                    </svg>
                    <div class="h-1 w-full {$b2}"></div>
                </div>
                <div class="flex flex-col items-center gap-2 flex-1 {$c3}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-check" aria-hidden="true">
                        <circle cx="12" cy="12" r="10"></circle>
                        <path d="m9 12 2 2 4-4"></path>
                    </svg>
                    <div class="h-1 w-full {$b3}"></div>
                </div>
            </div>

            <ul class="space-y-2 mb-6">
        HTML;

        // Boucle sur les produits de la commande
        foreach ($items as $item) {
            $name = strtoupper(htmlspecialchars($item['name']));
            $price = number_format($item['price'] * $item['quantity'], 2, '.', '');
            echo <<<HTML
                <li class="flex justify-between font-mono text-sm">
                    <span>{$item['quantity']}X {$name}</span>
                    <span class="font-bold">{$price}€</span>
                </li>
            HTML;
        }

        echo <<<HTML
            </ul>

            <div class="flex justify-between items-end mt-4">
                
                <div>
        HTML;

        // Le bouton ANNULER ne s'affiche que si la commande est en attente
        if ($order->status === 'attente') {
            echo <<<HTML
                <form action="actions/cancelOrder.php" method="POST" onsubmit="return confirm('Voulez-vous vraiment annuler votre commande ?');">
                    <input type="hidden" name="order_id" value="{$order->id}">
                    <button type="submit" class="border-2 border-black border-dotted px-4 py-2 text-xs font-bold flex items-center gap-2 hover:bg-[#E60012] hover:text-white hover:border-[#E60012] transition-colors cursor-pointer bg-transparent">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x" aria-hidden="true">
                            <path d="M18 6 6 18"></path>
                            <path d="m6 6 12 12"></path>
                        </svg> 
                        ANNULER
                    </button>
                </form>
            HTML;
        }

        echo <<<HTML
                </div>

                <div class="text-right">
                    <span class="font-mono uppercase tracking-tighter text-[10px] block text-zinc-400 mb-1">POSITION</span>
                    <span class="text-5xl font-black leading-none">{$positionDisplay}</span>
                </div>

            </div>
        </section>
        HTML;
    }


    // Affiche le bloc "C'est le moment de commander"
    public static function renderUserNoOrder() {
        echo <<<HTML
        <section id="bloc-aucune-commande" class="border-2 border-black p-6 shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] mb-10 bg-white flex flex-col gap-8">
            <div class="h-40 flex items-center justify-center relative">
                <img src="./images/logo.png" alt="Logo" class="max-h-full w-auto object-contain">
            </div>
            <div class="flex flex-col gap-6">
                <div class="text-center flex flex-col gap-1">
                    <h3 class="text-2xl font-black italic uppercase">ONIGIRIX EST OUVERT</h3>
                    <p class="text-sm text-zinc-500">C'est le moment de commander !</p>
                </div>
                <a href="?page=commandeUser" class="w-full bg-[#E60012] text-white font-bold py-4 border-2 border-black flex items-center justify-center text-center hover:bg-black transition-colors">
                    COMMANDER MAINTENANT 
                </a>
            </div>
        </section>
        HTML;
    }

    // Affiche le bloc "ITADAKIMASU" quand la commande a été récupérée
    public static function renderUserOrderRetrieved() {
        echo <<<HTML
        <section id="bloc-commande-recuperee" class="border-2 border-black bg-white p-4 shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] overflow-hidden mb-10">
            <div class="h-40 flex items-center justify-center relative">
                <img src="./images/logo.png" alt="Logo" class="max-h-full w-auto object-contain">
            </div>

            <div class="flex flex-col gap-6 p-4">
                <div class="text-center flex flex-col gap-1">
                    <h3 class="text-2xl font-black italic uppercase">ITADAKIMASU !</h3>
                    <p class="text-sm text-zinc-500">Votre commande vous a été remise. Bon appétit !</p>
                </div>
                <a href="index.php?page=historyUser" class="block text-center w-full bg-black text-white py-4 font-bold uppercase tracking-widest text-xs hover:bg-zinc-800 transition-colors shadow-[4px_4px_0px_0px_rgba(0,0,0,0.3)] active:translate-y-1 active:shadow-none">
                    Voir mon historique
                </a>
            </div>
        </section>
        HTML;
    }

    // Affiche le bloc "Service Fermé" quand aucune session de vente n'est en cours
    public static function renderServiceClosed() {
        echo <<<HTML
        <section id="bloc-service-ferme" class="border-2 border-black bg-zinc-50 p-4 shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] border-dashed overflow-hidden mb-10">
            <div class="h-40 flex items-center justify-center relative opacity-40 grayscale">
                <img src="./images/logo.png" alt="Logo" class="max-h-full w-auto object-contain">
            </div>

            <div class="flex flex-col gap-6 p-4">
                <div class="text-center flex flex-col gap-1">
                    <h3 class="text-2xl font-black italic uppercase">SERVICE FERMÉ</h3>
                    <p class="text-sm text-zinc-500">Les fourneaux sont éteints. Revenez pour la prochaine vente !</p>
                </div>
                <a href="index.php?page=menu" class="block text-center w-full bg-white border-2 border-black text-black py-4 font-bold uppercase tracking-widest text-xs hover:bg-zinc-100 hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] hover:-translate-y-1 hover:-translate-x-1 transition-all duration-200 active:translate-y-0 active:translate-x-0 active:shadow-none">
                    Consulter la carte
                </a>
            </div>
        </section>
        HTML;
    }

    /**
     * Affiche les statistiques de l'utilisateur
     */
    public static function renderUserStats($stats) {
        // On formate les nombres pour qu'ils aient toujours 2 chiffres (ex: "04" au lieu de "4")
        $totalItems = str_pad($stats['totalItems'] ?? 0, 2, '0', STR_PAD_LEFT);
        $totalOrders = str_pad($stats['totalOrders'] ?? 0, 2, '0', STR_PAD_LEFT);
        $favorite = htmlspecialchars($stats['favorite'] ?? 'Aucun pour le moment');

        echo <<<HTML
        <section class="mb-10">
            <h3 class="font-black text-xl italic uppercase tracking-tighter mb-4">Vos statistiques</h3>
            <div class="grid grid-cols-2 gap-4">
                <div class="border-2 border-black bg-white p-4 shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] col-span-1 py-6 flex flex-col justify-center items-center text-center">
                    <span class="uppercase tracking-tighter text-3xl font-black mb-1">{$totalItems}</span>
                    <p class="text-[10px] font-bold text-zinc-400 uppercase leading-none">Onigiris<br>Consommés</p>
                </div>
                <div class="border-2 border-black bg-white p-4 shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] col-span-1 py-6 flex flex-col justify-center items-center text-center">
                    <span class="uppercase tracking-tighter text-3xl font-black mb-1">{$totalOrders}</span>
                    <p class="text-[10px] font-bold text-zinc-400 uppercase leading-none">Commandes<br>Passées</p>
                </div>
                
                <div class="border-2 border-black bg-white p-4 shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] col-span-2 py-4 flex items-center justify-between px-6">
                    <div class="text-left">
                        <span class="font-mono uppercase tracking-tighter text-[10px] text-zinc-400 uppercase">Onigiri préféré</span>
                        <h4 class="font-black text-lg italic uppercase">{$favorite}</h4>
                    </div>
                    <div class="w-10 h-10 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="#E60012" stroke="#E60012" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                        </svg>
                    </div>
                </div>
            </div>
        </section>
        HTML;
    }

    /**
     * Affiche l'historique récent de l'utilisateur avec le détail des onigiris
     */
    public static function renderUserRecentOrders($pdo, $orders) {
        echo <<<HTML
        <section class="mb-10">
            <div class="flex justify-between items-end mb-4">
                <h3 class="font-black text-xl italic uppercase tracking-tighter">Dernières Commandes</h3>
                <a href="?page=historyUser" class="font-mono uppercase tracking-tighter text-[10px] text-zinc-400 hover:text-black">VOIR TOUT</a>
            </div>
            
            <div class="space-y-4">
        HTML;

        if (empty($orders)) {
            echo <<<HTML
                <div class="text-center py-6 border-2 border-black border-dotted bg-white">
                    <p class="font-bold text-sm text-zinc-400 uppercase">Aucune commande passée</p>
                </div>
            HTML;
        } else {
            foreach ($orders as $order) {
                // 1. Formatage de la date
                $date = new DateTime($order->createdAt);
                $formattedDate = $date->format('d/m/Y');
                
                // 2. Formatage du prix
                $total = number_format($order->totalAmount, 2, '.', '');

                // 3. RÉCUPÉRATION DES DÉTAILS DE LA COMMANDE
                $itemsDetails = Order::getOrderItemsDetails($pdo, $order->id);
                $itemsStringArray = [];
                
                // On crée des bouts de phrase "2x Saumon" qu'on range dans le tableau
                foreach ($itemsDetails as $item) {
                    $itemsStringArray[] = $item['quantity'] . 'x ' . $item['name'];
                }
                
                // On assemble le tableau avec des virgules : "2x Saumon, 1x Thon"
                $orderDetailsString = implode(', ', $itemsStringArray);
                
                // Sécurité au cas où la commande n'a pas d'items en base de données
                if (empty($orderDetailsString)) {
                    $orderDetailsString = "Commande N°" . $order->id;
                }

                echo <<<HTML
                <div class="flex justify-between items-center border-b-2 border-black pb-4">
                    <div>
                        <span class="font-mono uppercase tracking-tighter text-[10px] text-zinc-400">{$formattedDate}</span>
                        <p class="font-bold text-sm uppercase">{$orderDetailsString}</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="font-mono uppercase tracking-tighter font-bold">{$total}€</span>
                        <a href="index.php?page=commandeUser&reorder={$order->id}"class="w-10 h-10 border-2 border-black flex items-center justify-center bg-white active:bg-zinc-100 hover:bg-zinc-50" title="Recommander">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-rotate-ccw" aria-hidden="true">
                                <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path>
                                <path d="M3 3v5h5"></path>
                            </svg>
                        </a>
                    </div>
                </div>
                HTML;
            }
        }

        echo <<<HTML
            </div>
        </section>
        HTML;
    }

}

?>