<?php

// Récupération de la Session en cours
session_name("SessionOnigirix");
session_start();

// Récupération des fichiers nécessaires
require_once '../config/db.php';
require_once '../users/users.php';
require_once '../users/printForms.php';
require_once '../utils/flash.php';

$trigramme = $_POST['trigramme'] ?? null;
$nom = $_POST['nom'] ?? null;
$email = $_POST['email'] ?? null;
$password = $_POST['password'] ?? null;
$role = $_POST['role'] ?? 'user';

$success = User::createUser($pdo, $trigramme, $nom, $email, $password, $role);

if ($success) {
    Flash::success('Utilisateur créé, veuillez vous connecter !');
} else {
    Flash::error("Impossible de créer l'utilisateur.");
}

header("Location: ../index.php?page=login");
exit;

?>