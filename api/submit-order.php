<?php
session_start();
/**
 * API - SUBMIT ORDER
 * Endpoint pour la soumission de commandes via AJAX
 */

// Headers CORS et JSON
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Gestion des requêtes OPTIONS (preflight)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Vérification de la méthode
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'success' => false, 
        'error' => 'Méthode non autorisée. Utilisez POST.'
    ]);
    exit;
}

// Importation des dépendances
require_once '../config/db.php';
require_once '../includes/functions.php';
try {
    // Récupération et décodage des données JSON
    $rawData = file_get_contents('php://input');
    $data = json_decode($rawData, true);
    
    // Validation des données reçues
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('Format JSON invalide');
    }
    
    if (!isset($data['trigramme']) || !isset($data['items'])){
        throw new Exception('Données manquantes (trigramme, items ou total)');
    }
    
    // Validation du panier
    if (empty($data['items']) || !is_array($data['items'])) {
        throw new Exception('Le panier est vide');
    }
    
    // Validation des items
    $items = [];
    foreach ($data['items'] as $recipeId => $quantity) {
        $recipeId = (int)$recipeId;
        $quantity = (int)$quantity;
        
        if ($quantity <= 0) {
            continue;
        }
        
        // Vérifier que la recette existe
        $recipe = getRecipeById($pdo, $recipeId);
        if (!$recipe) {
            throw new Exception("Recette invalide (ID: {$recipeId})");
        }
        
        $items[$recipeId] = $quantity;
    }

    // Validation du trigramme
    $trigramme = strtoupper(trim($data['trigramme']));
    if (empty($trigramme) || strlen($trigramme) > 3) {
        throw new Exception('Trigramme invalide');
    }
    
    if (empty($items)) {
        throw new Exception('Aucun article valide dans le panier');
    }

    // Calcul du total côté serveur pour sécurité
    $calculatedTotal = calculateOrderTotal($pdo, $items);

    // Création de la commande
    $orderId = createOrder($pdo, $trigramme, $items, $calculatedTotal);
    
    if ($orderId === false) {
        throw new Exception('Erreur lors de la création de la commande en base de données');
    }
    
    // Succès
    http_response_code(201);
    echo json_encode([
        'success' => true,
        'orderId' => $orderId,
        'message' => 'Commande créée avec succès',
        'data' => [
            'trigramme' => $trigramme,
            'total' => $calculatedTotal,
            'itemsCount' => array_sum($items)
        ]
    ]);
    
} catch (Exception $e) {
    // Erreur
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
    
    // Log de l'erreur
    error_log("API Submit Order Error: " . $e->getMessage());
}