<?php
// Récupère les commandes selon un statut
function getOrdersByStatus($pdo, $status) {
    $requete = $pdo->prepare("
        SELECT o.*, u.trigramme 
        FROM orders o 
        JOIN users u ON o.user_id = u.id 
        WHERE o.statut = ? 
        ORDER BY o.created_at ASC
    ");
    $requete->execute([$status]);
    return $requete->fetchAll();
}

// Récupère les items d'une commande
function getOrderItems($pdo, $orderId) {
    $requete = $pdo->prepare("
        SELECT oi.quantite, r.nom 
        FROM order_items oi 
        JOIN recipes r ON oi.recipe_id = r.id 
        WHERE oi.order_id = ?
    ");
    $requete->execute([$orderId]);
    return $requete->fetchAll();
}


// Récupère le décompte total d'onigiris par recette pour un statut donné
function getStatsByStatus($pdo, $status) {
    $requete = $pdo->prepare("
        SELECT r.nom, SUM(oi.quantite) as total_qty
        FROM order_items oi
        JOIN recipes r ON oi.recipe_id = r.id
        JOIN orders o ON oi.order_id = o.id
        WHERE o.statut = ?
        GROUP BY r.id, r.nom
        ORDER BY total_qty DESC
    ");
    $requete->execute([$status]);
    return $requete->fetchAll();
}



// Récupère la liste des prix de toutes les recettes (retourne un tableau : [id => prix])
function getRecipePrices($pdo) {
    $stmt = $pdo->query("SELECT id, prix FROM recipes");
    // PDO::FETCH_KEY_PAIR crée directement le tableau [id => prix]
    return $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
}


// Fonction pour afficher une commande
function renderOrderCard($pdo, $order, $showAction = true) {
    $items = getOrderItems($pdo, $order['id']);
    $timeAgo = round((time() - strtotime($order['created_at'])) / 60);

    // Initialisation des styles par défaut
    $cardClasses = "border border-black p-4 relative bg-white";
    $textClass = "text-black";
    $itemTextClass = "text-black"; // Par défaut noir
    $showDelete = false;
    $isPret = false; // Flag pour le petit triangle
    
    // Personnalisation selon le statut
    if ($order['statut'] === 'prepa') {
        $cardClasses = "border-2 border-black p-4 bg-white shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] relative";
    } elseif ($order['statut'] === 'pret') {
        $cardClasses = "border border-[#E60012] p-4 relative bg-white";
        $textClass = "text-[#E60012]";
        $itemTextClass = "text-[#99000C]";
        $isPret = true;
    } else {
        $cardClasses .= " hover:shadow-lg transition-shadow group";
        $showDelete = true;

    }

    echo '<div class="'.$cardClasses.'">';

    // Affichage du petit triangle en haut à droite pour les commandes prêtes
    if ($isPret) {
        echo '<div class="absolute top-0 right-0 w-10 h-10 bg-[#E60012]/10 [clip-path:polygon(0_0,100%_0,100%_100%)]"></div>';
    }

    // Affichage de la croix si activée
    if ($showDelete) {
        echo '  <button class="absolute top-2 right-2 text-black/20 hover:text-[#E60012] opacity-0 group-hover:opacity-100 transition-opacity duration-100"><i data-lucide="x" class="w-4 h-4"></i></button>';
    }
    
    echo '  <div class="flex justify-between mb-3">';
    echo '    <div class="text-2xl font-black '.$textClass.'">'.$order['trigramme'].'</div>';
    echo '    <div class="text-xs text-black/40">'.$timeAgo.' min</div>';
    echo '  </div>';

    echo '  <ul class="text-sm space-y-1 mb-5">';
    foreach ($items as $item) {
        echo '    <li class="flex justify-between items-center '.$itemTextClass.'"><span>'.$item['nom'].'</span><span class="font-bold">x'.$item['quantite'].'</span></li>';
    }
    echo '  </ul>';
    
    if($showAction) {
        switch ($order['statut']) {
            case 'attente':
                $btnLabel = 'Préparer';
                $btnClass = 'hover:bg-black hover:text-white';
                break;

            case 'prepa':
                $btnLabel = 'PRÊT !';
                $btnClass = 'bg-green-500/20 hover:bg-green-500/70';
                break;

            default: 
                $btnLabel = 'ARCHIVER';
                $btnClass = 'bg-black hover:bg-[#E60012] hover:border-[#E60012] text-white';
                break;
        }
        // echo '<button class="uppercase w-full py-2 border border-black text-sm font-bold tracking-widest transition-colors '.$btnClass.'">'.$btnLabel.'</button>';
        echo '<button 
                onclick="updateOrderStatus('.$order['id'].', \''.$order['statut'].'\')" 
                class="uppercase w-full py-2 border border-black text-sm font-bold tracking-widest transition-colors '.$btnClass.'">
                '.$btnLabel.'
            </button>';
   }
    echo '</div>';
}


function renderArchivedOrder($order) {
    echo '<div class="flex justify-between border-b border-black/10 pb-1">';
    echo '    <span>#'.$order['id'].' - '.$order['trigramme'].'</span><span>'.number_format($order['montant_total'], 2).'€</span>';
    echo '</div>';
}



// Affiche les lignes de statistiques pour les onigiris
function renderStats($stats) {
    if ($stats) {
        foreach ($stats as $stat) {
            echo '<div class="flex justify-between items-center py-1 border-b border-black/5">';
            echo '    <span class="text-black/80">' . htmlspecialchars($stat['nom']) . '</span>';
            echo '    <span class="font-bold text-base">' . $stat['total_qty'] . '</span>';
            echo '</div>';
        }
    }    
}


// Fonction pour créer une commande
function createOrder($pdo, $trigramme, $items) {
    try {
        $pdo->beginTransaction();

        // Trouver l'utilisateur
        $stmtUser = $pdo->prepare("SELECT id FROM users WHERE trigramme = ?");
        $stmtUser->execute([strtoupper($trigramme)]);
        $userId = $stmtUser->fetchColumn();
        if (!$userId) throw new Exception("Utilisateur introuvable");

        // CALCUL DU TOTAL
        $prices = getRecipePrices($pdo);
        $montantTotal = 0;

        foreach ($items as $recipeId => $qty) {
            if ($qty > 0) {
                $montantTotal += $prices[$recipeId] * $qty;
            }
        }

        // Insertion de la commande
        $stmtOrder = $pdo->prepare("
            INSERT INTO orders (user_id, event_id, statut, montant_total, created_at) 
            VALUES (?, 1, 'attente', ?, NOW())
        ");
        $stmtOrder->execute([$userId, $montantTotal]);
        $orderId = $pdo->lastInsertId();

        // Insertion des articles
        $stmtItem = $pdo->prepare("INSERT INTO order_items (order_id, recipe_id, quantite) VALUES (?, ?, ?)");
        foreach ($items as $recipeId => $qty) {
            if ($qty > 0) {
                $stmtItem->execute([$orderId, $recipeId, $qty]);
            }
        }

        $pdo->commit();
        return true;
    } catch (Exception $e) {
        $pdo->rollBack();
        return false;
    }
}

?>