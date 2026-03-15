<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../orders/order.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $orderId = $_POST['orderId'] ?? null;
    $newStatus = $_POST['newStatus'] ?? null;

    if ($orderId && $newStatus) {
        $success = Order::updateStatus($pdo, $orderId, $newStatus);
        echo json_encode(['success' => $success]);
        exit;
    }
}
echo json_encode(['success' => false]);
exit;

?>