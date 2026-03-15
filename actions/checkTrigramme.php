<?php

// Récupération de la Session en cours
header('Content-Type: application/json');

// Récupération des fichiers nécessaires
require_once '../config/db.php';
require_once '../users/users.php';

$trigramme = $_GET['trigramme'] ?? '';
$excludedId = $_GET['excludedId'] ?? null;

if (strlen($trigramme) !== 3) {
    echo json_encode(['exists' => false]);
    exit;
}

$user = User::getUserByTrigramme($pdo, $trigramme);

if ($user && $excludedId !== $user->id) {
    echo json_encode(['exists' => true]);
} else {
    echo json_encode(['exists' => false]);
}

?>