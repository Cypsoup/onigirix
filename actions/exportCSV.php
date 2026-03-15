<?php
// On ouvre la session pour vérifier les droits
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Sécurité : Seul un admin peut exporter
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    exit("Accès refusé");
}

require_once __DIR__ . '/../config/db.php';

$eventId = $_GET['eventId'] ?? null;

if (!$eventId) {
    exit("ID d'événement manquant");
}

// 1. Récupération des commandes et des détails (ajuste les noms de colonnes selon ta BDD)
$query = "SELECT o.id, o.trigramme, o.status, o.dateOrder, o.totalPrice 
          FROM orders o 
          WHERE o.eventId = ? 
          ORDER BY o.dateOrder ASC";

$stmt = $pdo->prepare($query);
$stmt->execute([$eventId]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$orders) {
    exit("Aucune commande trouvée pour cet événement.");
}

// 2. Configuration des headers pour le téléchargement
$filename = "export_ventes_event_" . $eventId . "_" . date('Y-m-d') . ".csv";
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=' . $filename);

// 3. Création du fichier CSV
$output = fopen('php://output', 'w');

// Entêtes du CSV (Bom pour Excel)
fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

// Ligne de titres
fputcsv($output, ['ID Commande', 'Trigramme', 'Statut', 'Date', 'Total (€)'], ';');

// Remplissage des données
foreach ($orders as $order) {
    fputcsv($output, [
        $order['id'],
        $order['trigramme'],
        $order['status'],
        $order['dateOrder'],
        number_format($order['totalPrice'], 2, ',', '')
    ], ';');
}

fclose($output);
exit;

?>