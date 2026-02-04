<?php
session_start();
require_once 'config/db.php';
require_once 'includes/functions.php';

// On indique au navigateur qu'on va parler exclusivement en JSON
header('Content-Type: application/json');

$eventId = $_SESSION['event_id'] ?? 1; // ID par défaut à 1, il faudra gérer ça dynamiquement plus tard

// On nettoie les espaces et on force majuscule
$trigramme = strtoupper(trim($_POST['trigramme'] ?? ''));

// Récupération des onigiris
$allItems = $_POST['items'];
$itemsToOrder = [];

// On parcourt le tableau reçu pour ne garder que les quantités > 0
foreach ($allItems as $id => $qty) {
    if ($qty > 0) {
        $itemsToOrder[$id] = $qty;
    }
}

// La fonction createOrder retourne true ou false
$result = createOrder($pdo, $trigramme, $itemsToOrder, $eventId);

if ($result) {
    echo json_encode(['success' => true]); // on renvoie un succès au format JSON
} else {
    throw new Exception('Impossible de créer la commande (problème technique ou stock).');
}
