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
    public static function renderUserActiveOrder($order) {
        // Logique pour la barre de progression selon le statut
        $statusColors = [
            'attente' => ['text' => 'EN ATTENTE', 'bar1' => 'bg-black', 'bar2' => 'bg-zinc-200', 'bar3' => 'bg-zinc-200'],
            'prepa'   => ['text' => 'EN PRÉPARATION', 'bar1' => 'bg-black', 'bar2' => 'bg-black', 'bar3' => 'bg-zinc-200'],
            'pret'    => ['text' => 'PRÊT !', 'bar1' => 'bg-[#E60012]', 'bar2' => 'bg-[#E60012]', 'bar3' => 'bg-[#E60012]']
        ];
        $display = $statusColors[$order->status] ?? $statusColors['attente'];

        echo <<<HTML
        <section id="bloc-commande-en-cours" class="border-2 border-black p-4 shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] relative mb-10">
            <div class="flex justify-between items-start mb-6">
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 bg-[#E60012] animate-pulse"></div>
                    <span class="font-mono uppercase tracking-tighter text-sm font-bold">{$display['text']}</span>
                </div>
                <span class="font-mono uppercase tracking-tighter text-sm text-zinc-400">N°{$order->id}</span>
            </div>

            <div class="flex justify-between mb-8 border-b-2 border-black border-dotted pb-6 px-2">
                <div class="flex flex-col items-center gap-2"><div class="h-1 w-full {$display['bar1']}"></div></div>
                <div class="flex flex-col items-center gap-2"><div class="h-1 w-full {$display['bar2']}"></div></div>
                <div class="flex flex-col items-center gap-2"><div class="h-1 w-full {$display['bar3']}"></div></div>
            </div>

            <div class="absolute bottom-4 right-4 text-right">
                <span class="font-mono uppercase tracking-tighter text-[10px] block text-zinc-400">TOTAL</span>
                <span class="text-3xl font-black leading-none">{$order->totalAmount}€</span>
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
                <a href="commandeUser.php" class="w-full bg-[#E60012] text-white font-bold py-4 border-2 border-black flex items-center justify-center text-center hover:bg-black transition-colors">
                    COMMANDER MAINTENANT 
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
                <a href="#" class="font-mono uppercase tracking-tighter text-[10px] text-zinc-400 hover:text-black">VOIR TOUT</a>
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
                        <button class="w-10 h-10 border-2 border-black flex items-center justify-center bg-white active:bg-zinc-100 hover:bg-zinc-50" title="Recommander">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-rotate-ccw" aria-hidden="true">
                                <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path>
                                <path d="M3 3v5h5"></path>
                            </svg>
                        </button>
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