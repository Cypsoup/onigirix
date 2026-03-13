<?php

require_once 'users/users.php';

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
            $nom = htmlspecialchars($recipe->nom ?? 'Produit');
            $qty = (int) ($item->quantite ?? 1);
            $itemsHtml .= <<<HTML
                <li class="flex justify-between items-center text-sm">
                    <span class="font-medium">{$nom}</span>
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
                    onclick="updateOrderStatus({$orderId}, '{$status}')" 
                    class="w-full py-2 border-2 border-black text-xs font-black uppercase tracking-widest transition-all {$btnClass}">
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
                $nom = htmlspecialchars($stat['nom']);
                $qty = (int) $stat['totalQty'];
                echo <<<HTML
                    <div class="flex justify-between items-center py-1 border-b border-black/5">
                        <span class="text-black/80"> {$nom} </span>
                        <span class="font-bold text-base"> {$qty} </span>
                    </div>
                HTML;
            }
        }
    }
}

?>