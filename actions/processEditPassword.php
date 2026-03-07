<?php

// Récupération de la Session en cours
session_name("SessionOnigirix");
session_start();

require_once '../config/db.php';
require_once '../users/users.php';
require_once '../users/usersRenderer.php';
require_once '../users/printForms.php';
require_once '../utils/flash.php';

$id = $_POST['id'] ?? null;
$user = User::getUserById($pdo, $id);

if (!$user) {
    Flash::error("Erreur dans la modification du mot de passe.");
    header('Location: ../index.php?page=editPasword');
    exit;
}

$old = $_POST['oldPassword'] ?? '';

if (!User::testPassword($user, $old)) {
    Flash::error("L'ancien mot de passe doit correspondre");
    header('Location: ../index.php?page=editUser&todo=editPassword');
    exit;
}

$password = $_POST['password'];
$confirm = $_POST['passwordConfirm'];

if ($password !== $confirm) {
    Flash::error("Nouveaux mots de passe différents !");
    header("Location: ../index.php?page=editUser&todo=editPassword");
    exit;
}

$success = User::updateUserPassword($pdo, $id, $password);

if ($success) {
    Flash::success("Mot de passe mis à jour !");
    header("Location: ../index.php?page=userProfile");
} else {
    Flash::error("Erreur de mise à jour du mot de passe.");
    header("Location: ../index.php?page=editUser&todo=editPassword");
}

exit;

?>