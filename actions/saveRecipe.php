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

if ($id == null) {
    header('Location: ../index.php?page=menu');
    exit;
}

$isEdit = ($id == "new") ? false : true;
$nom = $_POST['nom'] ?? '';
$prix = $_POST['prix'] ?? 0;
$description = $_POST['description'] ?? '';
$fileName = null;
if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
    $tempName = $_FILES['image']['tmp_name'];
    $fileName = time() . '_' . $_FILES['image']['name']; // Nom unique
    move_uploaded_file($tempName, "../images/recipeImages/" . $fileName);
}

if ($isEdit) {
    $success = Recipe::updateRecipe($pdo, $id, $nom, $fileName, $description, $prix);
} else if (!Recipe::getRecipeByName($pdo, $nom)) {
    $success = Recipe::insertRecipe($pdo, $nom, $fileName, $description, $prix);
}

if ($success) {
    header("Location: ../index.php?page=menu&status=updated");
} else {
    header("Location: ../index.php?page=menu&status=updateError");
}

exit;

?>