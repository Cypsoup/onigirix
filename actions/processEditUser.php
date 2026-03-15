<?php

// Récupération de la Session en cours
session_name("SessionOnigirix");
session_start();

// Récupération des fichiers nécessaires
require_once '../config/db.php';
require_once '../users/users.php';
require_once '../users/usersRenderer.php';
require_once '../users/printForms.php';
require_once '../utils/flash.php';

$id = $_POST['id'] ?? null;

// Vérification de la transmission des données
if ($id == null) {
    Flash::error("Erreur dans la modification des paramètres utilisateurs.");
    header('Location: ../index.php?page=editUser&todo=editInfo');
    exit;
}

// Vérification de l'unicité du trigramme
$trigramme = $_POST['trigramme'] ?? null;
$userTrigramme = User::getUserByTrigramme($pdo, $trigramme);
if ($userTrigramme && $userTrigramme->id !== $id) {
    Flash::error("Trigramme déjà utilisé !");
    header("Location : ../index.php?page=createUser");
    exit;
}

// Récupération des données du formulaire
$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';

// Mise à jour de la table
$success = User::updateUserInfo($pdo, $id, $trigramme, $name, $email);

if ($success) {
    Flash::success("Informations mises à jour !");
    header("Location: ../index.php?page=userProfile");
} else {
    Flash::error("Erreur de mise à jour des informations.");
    header('Location: ../index.php?page=editUser&todo=editInfo');
}

exit;

?>