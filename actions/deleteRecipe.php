<?php

session_name("SessionOnigirix");
session_start();
header('Content-Type: application/json');

require_once '../config/db.php';
require_once '../Recipes/Recipe.php';
require_once '../utils/flash.php';

// Vérification d'accès admin
$access = $_SESSION['role'] ?? 'user';
if ($access !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Interdit']);
    exit;
}

// Récupération du form
$id = $_POST['id'] ?? null;

// Suppression de la recette
$success = Recipe::deleteRecipe($pdo, $id);
if ($success) {
    Flash::success("La recette a été supprimée avec succès.");
} else {
    Flash::error("Impossible de supprimer la recette.");
}

header("Location: ../index.php?page=menu");
exit;

?>