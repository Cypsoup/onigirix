<?php

// Récupération de la Session en cours
session_name("SessionOnigirix");
session_start();

require_once '../config/db.php';
require_once '../Recipes/Recipe.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../index.php?page=menu&error=access_denied');
    exit;
}

$id = $_POST['id'] ?? null;
$nom = $_POST['nom'] ?? '';
$prix = $_POST['prix'] ?? 0;
$description = $_POST['description'] ?? '';
$available = isset($_POST['available']) ? 1 : 0;

if (!$id) {
    header('Location: ../index.php?page=menu');
    exit;
}

$fileName = null;
if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
    $tempName = $_FILES['image']['tmp_name'];
    $fileName = time() . '_' . $_FILES['image']['name']; // Nom unique
    move_uploaded_file($tempName, "../images/recipeImages/" . $fileName);
}

$success = Recipe::updateRecipe($pdo, $id, $nom, $fileName, $description, $prix);

if ($success) {
    header("Location: ../index.php?page=menu&status=updated");
} else {
    header("Location: ../index.php?page=menu&status=updateError");
}

exit;

?>