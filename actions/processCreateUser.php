<?php

// Récupération de la Session en cours
session_name("SessionOnigirix");
session_start();

// Récupération des fichiers nécessaires
require_once '../config/db.php';
require_once '../users/users.php';
require_once '../users/printForms.php';
require_once '../utils/flash.php';

// Vérification de l'unicité du trigramme
$trigramme = $_POST['trigramme'] ?? null;
$userTrigramme = User::getUserByTrigramme($pdo, $trigramme);
if ($userTrigramme) {
    Flash::error("Trigramme déjà utilisé !");
    header("Location : ../index.php?page=createUser");
    exit;
}

// Vérification des mots de passe
$password = $_POST['password'] ?? null;
$passwordConfirm = $_POST['passwordConfirm'] ?? null;

if ($password !== $passwordConfirm) {
    Flash::error("Mots de passe différents !");
    header("Location: ../index.php?page=createUser");
    exit;
}

// Récupération des variables de connexion
$nom = $_POST['nom'] ?? null;
$email = $_POST['email'] ?? null;
$role = $_POST['role'] ?? 'user';

$success = User::createUser($pdo, $trigramme, $nom, $email, $password, $role);

if ($success) {
    Flash::success('Utilisateur créé, veuillez vous connecter !');
    header("Location: ../index.php?page=login");
} else {
    Flash::error("Impossible de créer l'utilisateur.");
    header("Location: ../index.php?page=createUser");
}

exit;

?>