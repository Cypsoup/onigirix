<?php

// Récupération de la Session en cours
session_name("SessionOnigirix");
session_start();

require_once '../config/db.php';
require_once '../Recipes/Recipe.php';
require_once '../utils/flash.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../index.php?page=menu&error=access_denied');
    exit;
}

$id = $_POST['id'] ?? null;

if ($id == null) {
    Flash::error("Erreur dans l'enregistrement de la recette.");
    header('Location: ../index.php?page=menu');
    exit;
}

$isEdit = ($id == "new") ? false : true;
$name = $_POST['name'] ?? '';
$price = $_POST['price'] ?? 0;
$description = $_POST['description'] ?? '';
$fileName = null;
if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
    $tempName = $_FILES['image']['tmp_name'];
    $fileName = time() . '_' . $_FILES['image']['name']; // Nom unique
    move_uploaded_file($tempName, "../images/recipeImages/" . $fileName);
}

if ($isEdit) {
    $success = Recipe::updateRecipe($pdo, $id, $name, $fileName, $description, $price);
} else if (!Recipe::getRecipeByName($pdo, $name)) {
    $success = Recipe::insertRecipe($pdo, $name, $fileName, $description, $price);
}

if ($success) {
    Flash::success("Recette mise à jour !");
} else {
    Flash::error("Erreur lors de la mise à jour.");
}

header("Location: ../index.php?page=menu");
exit;

?>