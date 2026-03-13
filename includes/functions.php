<?php
session_start();
$_SESSION['event_id'] = 1; // ID de l'événement en cours, )pour l'instant 1 mais il faudra gérer ça plus tard



// ==============================================================================================================================
// FONCTIONS DE RÉCUPÉRATION DES DONNÉES
// ==============================================================================================================================

/**
 * Récupère toutes les commandes d'un certain statut depuis la base de données
 * @param PDO $pdo - Connexion à la base de données
 * @param string $status - statut de la commande
 * @return array|false - Tableau des commandes ou false si erreur
 */
function getOrdersByStatus($pdo, $status) {
    try {
        $requete = $pdo->prepare("
            SELECT o.*, u.trigramme 
            FROM orders o 
            JOIN users u ON o.user_id = u.id 
            WHERE o.statut = ? 
            ORDER BY o.created_at ASC
        ");
        $requete->execute([$status]);
        return $requete->fetchAll();
    } catch (PDOException $e) {
        error_log("Erreur getOrderByStatus: " . $e->getMessage());
        return false;
    }
}

/**
 * Récupère les items (onigiris) d'une commande par son id
 * @param PDO $pdo - Connexion à la base de données
 * @param int $id - ID de la commande
 * @return array|false - Tableau des items de la commande ou false si erreur
 */
function getOrderItems($pdo, $orderId) {
    try {
        $requete = $pdo->prepare("
            SELECT oi.quantite, r.nom 
            FROM order_items oi 
            JOIN recipes r ON oi.recipe_id = r.id 
            WHERE oi.order_id = ?
        ");
        $requete->execute([$orderId]);
        return $requete->fetchAll();
    } catch (PDOException $e) {
        error_log("Erreur getOrderItems: " . $e->getMessage());
        return false;
    }
}

/**
 * Récupère le décompte total d'onigiris par recette pour un statut donné
 * @param PDO $pdo - Connexion à la base de données
 * @param string $status - statut des commandes à prendre en compte (ex: 'attente', 'prepa', 'pret')
 * @return array|false - Tableau des recettes et leurs quantités ou false si erreur
 */
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

/**
 * Récupère la liste de toutes les recettes avec leur stock
 * @param PDO $pdo - Connexion à la base de données
 * @return array|false - Tableau des recettes [id => recette] ou false si erreur
 */
function getAllRecipes($pdo) {
    $stmt = $pdo->query("SELECT * FROM recipes WHERE actif = 1");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
    // Exemple
    // $recipes = [
    //     ['id' => 10, 'nom' => 'Thon', ...],
    //     ['id' => 12, 'nom' => 'Poulet', ...],
    //     ['id' => 15, 'nom' => 'Bœuf', ...]
    // ];
}

/**
 * Récupère une recette spécifique par son ID
 * @param PDO $pdo - Connexion à la base de données
 * @param int $id - ID de la recette
 * @return array|false - Données de la recette ou false
 */
function getRecipeById($pdo, $id) {
    try {
        $stmt = $pdo->prepare("
            SELECT 
                id, 
                nom, 
                description, 
                prix, 
                stock,
                actif
            FROM recipes 
            WHERE id = ? AND actif = 1
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Erreur getRecipeById: " . $e->getMessage());
        return false;
    }
}

/**
 * Récupère les recettes au format JSON pour JavaScript
 * @param PDO $pdo - Connexion à la base de données
 * @return string - JSON encodé des recettes
 */
function getRecipesAsJSON($pdo) {
    $recipes = getAllRecipes($pdo);
    if (!$recipes) {
        return '[]';
    }
    
    $formattedRecipes = array_map(function($recipe) {
        return [
            'id' => (int)$recipe['id'],
            'name' => $recipe['nom'],
            'price' => (float)$recipe['prix'],
            'description' => $recipe['description'] ?? '',
            'stock' => (int)$recipe['stock'],
            'image' => 'images/onigiri.png'
        ];
    }, $recipes);
    
    return json_encode($formattedRecipes, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); // Encode en JSON avec échappement pour éviter les problèmes de sécurité lors de l'injection dans JavaScript
}

// ==============================================================================================================================
// FONCTIONS DE RENDU HTML
// ==============================================================================================================================

/**
 * Affiche les cartes de commandes dans le dashboard admin
 * @param PDO $pdo - Connexion à la base de données
 * @param array $order - Données de la commande à afficher
 * return string - HTML de la carte de commande
 */
function renderOrderCard($pdo, $order) {
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
    echo '</div>';
}

/**
 * Affiche une ligne dans la section des archives
 * @param array $order - Données de la commande à afficher
 * return string - HTML de la ligne de commande archivée
 */
function renderArchivedOrder($order) {
    echo '<div class="flex justify-between border-b border-black/10 pb-1">';
    echo '    <span>#'.$order['id'].' - '.$order['trigramme'].'</span><span>'.number_format($order['montant_total'], 2).'€</span>';
    echo '</div>';
}

/**
 * Affiche les statistiques des onigiris en attente dans le dashboard admin
 * @param array $stats - Tableau des statistiques à afficher
 * return string - HTML des statistiques
 */
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

/**
 * Affiche une ligne de recette avec les boutons + et - pour la prise de commande
 * @param int $id - ID de la recette
 * @param array $recipe - Données de la recette (nom, stock, etc.)
 * return string - HTML de la ligne de recette
 */ 
function renderRecipeRow($recipe) {
    $id = $recipe['id'];
    $stock = $recipe['stock'] ?? 0;
    $isAvailable = $stock > 0;

    $disabled = $isAvailable ? '' : 'disabled';
    $opacityClass = $isAvailable ? '' : 'opacity-40 pointer-events-none grayscale';

    return <<<HTML
    <div class="flex justify-between items-center py-2 border-b border-black/5 text-sm {$opacityClass}">
        <span>{$recipe["nom"]}</span>
        <div class="flex items-center gap-4">
            <button type="button" onclick="document.getElementById('qty-{$id}').stepDown()" class="w-8 h-8 border border-black flex items-center justify-center hover:bg-black hover:text-white transition-colors {$disabledAttr}" {$disabled}>-</button>
            
            <input type="number" name="items[{$id}]" id="qty-{$id}" value="0" min="0" max="{$stock}" class="w-4 text-center font-bold outline-none appearance-none m-0 bg-transparent" {$disabled}>
            
            <button type="button" onclick="document.getElementById('qty-{$id}').stepUp()" class="w-8 h-8 border border-black flex items-center justify-center hover:bg-black hover:text-white transition-colors" {$disabled}>+</button>
        </div>
    </div>  
HTML;
}

/**
 * Affiche une carte de recette dans le menu de prise de commande de l'utilisateur
 * @param array $recipe - Données de la recette à afficher
 * return string - HTML de la carte de recette
 */
function renderMenuCard($recipe) {
    // Préparation des données pour l'affichage
    $id = $recipe['id'];
    $name = htmlspecialchars($recipe['nom']);
    $description = htmlspecialchars($recipe['description'] ?? '');
    $price = number_format($recipe['prix'], 2, '.', '');
    $image = !empty($recipe['image']) ? $recipe['image'] : 'images/onigiri.png';

    // Gestion du stock
    $stock = $recipe['stock'] ?? 0;
    $isAvailable = $stock > 0;
    $opacityClass = $isAvailable ? '' : 'opacity-40 pointer-events-none grayscale';

    echo '
    <div class="flex flex-col bg-white border-[1.5px] border-black/20 rounded-xl p-4 active:scale-95 transition-transform cursor-pointer '.$opacityClass.'"
         onclick="openDrawer(' . $id . ')">
        
        <div class="w-full aspect-square bg-black/5 rounded-xl flex items-center justify-center mb-3 overflow-hidden">
            <img src="' . $image . '" alt="' . $name . '" class="w-full h-full object-cover">
        </div>
        
        <div class="mb-4">
            <h3 class="text-base font-black text-black leading-tight mb-0.5">' . $name . '</h3>
            <p class="text-xs text-black/50 font-medium">' . $description . '</p>
        </div>
        
        <div class="flex items-center justify-between mt-auto">
            <span class="text-lg font-black text-black">' . $price . '€</span>
            
            <span class="text-lg font-black text-black">
                0
            </span>
        </div>
    </div>';
}




// ==============================================================================================================================
// FONCTIONS DE GESTION DES COMMANDES
// ==============================================================================================================================

/**
 * Crée une nouvelle commande dans la base de données
 * @param PDO $pdo - Connexion à la base de données
 * @param string $trigramme - Trigramme de l'utilisateur qui passe la commande
 * @param array $items - Tableau des items à commander [recipeId => quantity]
 * @return bool|int - l'ID de la commande créée si la commande a été créée avec succès, false sinon
 */
function createOrder($pdo, $trigramme, $items, $montant_total) {
    try {
        $totalOnigiris = array_sum($items);

        if ($totalOnigiris <= 0) {
            throw new Exception("Ta commande est vide !");
        }
        if ($totalOnigiris > 4) {
            throw new Exception("Limite de 4 onigiris maximum par commande.");
        }
        if (!isset($_SESSION['event_id'])) {
            throw new Exception("Session expirée ou event_id manquant.");
        }
    
        $pdo->beginTransaction();  // Commence une transaction (= groupe d'opérations sur la base de données)

        // Trouver l'utilisateur
        $stmtUser = $pdo->prepare("SELECT id FROM users WHERE trigramme = ?");
        $stmtUser->execute([strtoupper($trigramme)]);
        $userId = $stmtUser->fetchColumn();
        if (!$userId) throw new Exception("Utilisateur introuvable");
        
        // Insertion de la commande
        $stmtOrder = $pdo->prepare("
            INSERT INTO orders (user_id, event_id, statut, montant_total, created_at) 
            VALUES (?, ?, 'attente', ?, NOW())
        ");
        $stmtOrder->execute([$userId, $_SESSION['event_id'], $montant_total]);
        $orderId = $pdo->lastInsertId(); // récupère l'ID de la commande insérée

        // Insertion des articles
        $stmtItem = $pdo->prepare("INSERT INTO order_items (order_id, recipe_id, quantite) VALUES (?, ?, ?)");
        foreach ($items as $recipeId => $qty) {
            if ($qty > 0) {
                $stmtItem->execute([$orderId, $recipeId, $qty]);
            }
        }

        $pdo->commit();  // on valide les changements
        return $orderId; // on retourne l'ID de la commande créée
    } 
    catch (Exception $e) {
        $pdo->rollBack(); // annule les modifications sur la base de données en cas d'erreur
        // On arrête tout et on affiche l'erreur exacte
        die(json_encode([
            "success" => false, 
            "error" => "Erreur détectée : " . $e->getMessage()
        ]));
        return false;
    }
}

function calculateOrderTotal($pdo, $items) {
    if (empty($items)) return 0;

    // On récupère uniquement les IDs des produits dans le panier
    // $items est sous la forme [id => quantite]
    $ids = array_keys($items);

    // On prépare les points d'interrogation pour le SQL (ex: "?,?,?")
    $placeholders = str_repeat('?,', count($ids) - 1) . '?';

    try {
        // On récupère les prix en une seule fois
        $stmt = $pdo->prepare("SELECT id, prix FROM recipes WHERE id IN ($placeholders)");
        $stmt->execute($ids);
        $recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $total = 0;

        // On calcule le total en croisant les prix de la BDD et les quantités du panier
        foreach ($recipes as $recipe) {
            $id = $recipe['id'];
            $prix = (float)$recipe['prix'];
            $quantite = (int)$items[$id];
            
            $total += $prix * $quantite;
        }

        return $total;

    } catch (PDOException $e) {
        error_log("Erreur calcul total: " . $e->getMessage());
        return false;
    }
}

?>