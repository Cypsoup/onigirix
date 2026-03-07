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

if ($id == null) {
    Flash::error("Erreur dans la modification des paramètres utilisateurs.");
    header('Location: ../index.php?page=editUser&todo=editInfo');
    exit;
}

$nom = $_POST['nom'] ?? '';
$trigramme = $_POST['trigramme'] ?? '';
$email = $_POST['email'] ?? '';

$success = User::updateUserInfo($pdo, $id, $trigramme, $nom, $email);

if ($success) {
    Flash::success("Informations mises à jour !");
    header("Location: ../index.php?page=userProfile");
} else {
    Flash::error("Erreur de mise à jour des informations.");
    header('Location: ../index.php?page=editUser&todo=editInfo');
}

exit;

?>