<?php

session_name("SessionOnigirix");
session_start();
header('Content-Type: application/json');

require_once '../config/db.php';
require_once '../Recipes/Recipe.php';

$access = $_SESSION['role'] ?? 'user';

if ($access !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Interdit']);
    exit;
}

$id = $_GET['id'] ?? null;
$todo = $_GET['todo'] ?? null;
$newStatus = ($todo == "archive") ? 0 : 1;

$success = Recipe::updateAvailability($pdo, $id, $newStatus);

echo json_encode(['success' => $success, 'newStatus' => $newStatus]);

?>