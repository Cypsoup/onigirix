<?php
session_name("SessionOnigirix");
session_start();

require_once '../config/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Méthode non autorisée.']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$orderId = $data['orderId'] ?? null;
$userId = $_SESSION['userId'] ?? null;

if (!$orderId || !$userId) {
    echo json_encode(['success' => false, 'error' => 'Données manquantes.']);
    exit;
}

try {
    // 1. On vérifie que la commande appartient au user et qu'elle est bien en 'attente'
    $stmt = $pdo->prepare("SELECT status FROM orders WHERE id = ? AND userId = ?");
    $stmt->execute([$orderId, $userId]);
    $order = $stmt->fetch();

    if (!$order) {
        echo json_encode(['success' => false, 'error' => 'Commande introuvable ou vous n\'en êtes pas le propriétaire.']);
        exit;
    }

    if ($order['status'] !== 'attente') {
        echo json_encode(['success' => false, 'error' => 'Trop tard ! Votre commande est déjà en préparation.']);
        exit;
    }

    // 2. Suppression propre (d'abord les articles liés, puis la commande)
    $pdo->beginTransaction();
    $stmt1 = $pdo->prepare("DELETE FROM order_items WHERE orderId = ?");
    $stmt1->execute([$orderId]);

    $stmt2 = $pdo->prepare("DELETE FROM orders WHERE id = ?");
    $stmt2->execute([$orderId]);
    $pdo->commit();

    echo json_encode(['success' => true]);

} catch (Exception $e) {
    $pdo->rollBack();
    error_log("Erreur cancel-order : " . $e->getMessage());
    echo json_encode(['success' => false, 'error' => 'Erreur serveur lors de l\'annulation.']);
}