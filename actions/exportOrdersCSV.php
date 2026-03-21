<?php
// Ouverture de session
if (session_status() === PHP_SESSION_NONE) {
    session_name("SessionOnigirix");
    session_start();
}

// Vérification des accès
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    exit("Accès refusé");
}

require_once __DIR__ . '/../config/db.php';

$eventId = $_GET['eventExportedId'] ?? null;

if (!$eventId) {
    exit("ID d'événement manquant");
}

// Récupération des commandes et des détails
$query = "SELECT u.name, u.trigramme, o.totalAmount 
          FROM `orders` o 
          JOIN `users` u ON u.id = o.userId
          WHERE o.eventId = ? ";    
$stmt = $pdo->prepare($query);
$stmt->execute([$eventId]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$orders) {
    exit("Aucune commande trouvée pour cet événement.");
}

// Configuration des headers
$filename = "export_ventes_event_" . $eventId . "_" . date('Y-m-d') . ".csv";
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=' . $filename);

// Création du fichier csv
$output = fopen('php://output', 'w');

// Entêtes du CSV
fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

// Ligne de titres
fputcsv($output, ['Nom', 'Trigramme', 'Total (€)'], ';');

// Remplissage des données
foreach ($orders as $order) {
    fputcsv($output, [
        $order['name'],
        $order['trigramme'],
        number_format($order['totalAmount'], 2, ',', '')
    ], ';');
}

fclose($output);

exit;