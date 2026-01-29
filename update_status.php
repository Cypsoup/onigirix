<?php
require_once 'config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $orderId = $_POST['orderId'] ?? null;
    $newStatus = $_POST['newStatus'] ?? null;

    if ($orderId && $newStatus) {
        $stmt = $pdo->prepare("UPDATE orders SET statut = ? WHERE id = ?");
        $success = $stmt->execute([$newStatus, $orderId]);

        echo json_encode(['success' => $success]);
        exit;
    }
}
echo json_encode(['success' => false]);
exit;