<?php

// Récupération de la Session en cours
session_name("SessionOnigirix");
session_start();

// Récupération des fichiers nécessaires
require_once '../config/db.php';
require_once '../users/users.php';
require_once '../utils/flash.php';

// Récupération des données
$trigramme = $_POST['trigramme'] ?? null;
$mdp = $_POST['mdp'] ?? null;

$user = User::getUserByTrigramme($pdo, $trigramme);

// Vérification de la connexion et redirection
if ($user && User::testPassword($user, $mdp)) {
    $_SESSION['loggedIn'] = true;
    $_SESSION['role'] = $user->role;
    $_SESSION['userId'] = $user->id;
    Flash::success("Heureux de vous revoir : $user->nom");
    header("Location: ../index.php?page=dashboardUser");
} else {
    Flash::error("Erreur d'identifiants");
    header("Location: ../index.php?page=login");
}

exit;

?>