<?php
session_start();
/**
 * API - SUBMIT ORDER
 * Endpoint pour la soumission de commandes via AJAX
 */

require_once '../config/db.php';
require_once '../recipes/recipe.php';
require_once '../users/users.php';
require_once '../orders/order.php';
require_once '../utils/flash.php';


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



try {
    // Récupération et décodage des données JSON
    $rawData = file_get_contents('php://input');
    $data = json_decode($rawData, true);

    // Validation des données reçues
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('Format JSON invalide');
    }

    if (!isset($data['trigramme']) || !isset($data['items'])) {
        throw new Exception('Données manquantes (trigramme ou items)');
    }

    // Validation du panier
    if (empty($data['items']) || !is_array($data['items'])) {
        throw new Exception('Le panier est vide');
    }

    // On nettoie les items pour enlever les quantités à 0
    $items = [];
    foreach ($data['items'] as $id => $qty) {
        if ((int) $qty > 0) {
            $items[(int) $id] = (int) $qty;
        }
    }

    if (empty($items)) {
        throw new Exception('Aucun article valide dans le panier');
    }

    $recipeIds = array_keys($items); // Récupère juste les numéros [3, 5, etc.]
    $recipes = Recipe::getRecipesByIds($pdo, $recipeIds);

    // Validation finale et calcul du total
    $calculatedTotal = 0;
    foreach ($items as $recipeId => $quantity) {
        if (!isset($recipes[$recipeId]) || $recipes[$recipeId]->available != 1) {
            throw new Exception("Recette invalide ou épuisée (ID: {$recipeId})");
        }

        // Calcul du total
        $calculatedTotal += $recipes[$recipeId]->price * $quantity;
    }

    // Validation du trigramme
    $trigramme = strtoupper(trim($data['trigramme']));
    if (empty($trigramme) || strlen($trigramme) > 3) {
        throw new Exception('Trigramme invalide');
    }

    // Recupération de l'utilisateur
    $user = User::getUserByTrigramme($pdo, $trigramme);
    if (!$user) {
        // C'est ce message qui déclenche l'alerte "Vérifie le trigramme" dans le JS
        echo json_encode(['success' => false, 'message' => 'Utilisateur introuvable']);
        exit;
        //throw new Exception("Utilisateur introuvable");
    }


    // Création de la commande
    $eventId = $_SESSION['eventId'] ?? null;
    $orderId = Order::createOrder($pdo, $user->id, $eventId, $calculatedTotal, $items);

    if ($orderId === false) {
        throw new Exception('Erreur lors de la création de la commande en base de données');
    }

    // Message flash pour la page d'accueil
    Flash::success("Commande validée et en attente !");

    // Succès
    http_response_code(201);
    echo json_encode([
        'success' => true
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