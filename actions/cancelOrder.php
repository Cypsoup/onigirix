<?php
session_name("SessionOnigirix");
session_start();

require_once '../config/db.php';
require_once '../utils/flash.php';

// Vérification de sécurité basique
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['order_id'])) {
    Flash::error("Action non autorisée.");
    header("Location: ../index.php?page=dashboardUser");
    exit;
}

$orderId = (int) $_POST['order_id'];
$userId = $_SESSION['userId'] ?? null;

if (!$userId) {
    header("Location: ../index.php");
    exit;
}

try {
    // 1. On vérifie que la commande appartient au user et qu'elle est bien en 'attente'
    $stmt = $pdo->prepare("SELECT status FROM orders WHERE id = ? AND userId = ?");
    $stmt->execute([$orderId, $userId]);
    $order = $stmt->fetch();

    if (!$order) {
        Flash::error("Commande introuvable ou vous n'en êtes pas le propriétaire.");
    } elseif ($order['status'] !== 'attente') {
        Flash::error("Trop tard ! Votre commande est déjà en préparation.");
    } else {
        // 2. Si tout est bon, on supprime la commande et ses articles
        $pdo->beginTransaction();
        
        $stmt1 = $pdo->prepare("DELETE FROM order_items WHERE orderId = ?");
        $stmt1->execute([$orderId]);

        $stmt2 = $pdo->prepare("DELETE FROM orders WHERE id = ?");
        $stmt2->execute([$orderId]);
        
        $pdo->commit();
        
        Flash::success("Votre commande a bien été annulée.");
    }
} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    error_log("Erreur cancelOrder : " . $e->getMessage());
    Flash::error("Une erreur est survenue lors de l'annulation.");
}

// 3. Retour au dashboard
header("Location: ../index.php?page=dashboardUser");
exit;