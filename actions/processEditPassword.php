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
$user = User::getUserById($pdo, $id);

// Vérification de la transmission des données
if (!$user) {
    Flash::error("Erreur dans la modification du mot de passe.");
    header('Location: ../index.php?page=editPasword');
    exit;
}

// Vérification de l'ancien mdp
$old = $_POST['oldPassword'] ?? '';
if (!User::testPassword($user, $old)) {
    Flash::error("L'ancien mot de passe doit correspondre");
    header('Location: ../index.php?page=editUser&todo=editPassword');
    exit;
}

// Vérification que le nouveau mdp est identique sur les deux inputs
$password = $_POST['password'];
$confirm = $_POST['passwordConfirm'];
if ($password !== $confirm) {
    Flash::error("Nouveaux mots de passe différents !");
    header("Location: ../index.php?page=editUser&todo=editPassword");
    exit;
}

// Mise à jour de la table
$success = User::updateUserPassword($pdo, $id, $password);

// Transmission des résultats
if ($success) {
    Flash::success("Mot de passe mis à jour !");
    header("Location: ../index.php?page=userProfile");
} else {
    Flash::error("Erreur de mise à jour du mot de passe.");
    header("Location: ../index.php?page=editUser&todo=editPassword");
}

exit;

?>