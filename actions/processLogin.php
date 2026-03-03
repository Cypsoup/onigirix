<?php

session_name("SessionOnigirix");
session_start();

require_once '../config/db.php';
require_once '../users/users.php';
require_once '../utils/flash.php';

$login = $_POST['login'] ?? null;
$mdp = $_POST['mdp'] ?? null;

$user = User::getUtilisateur($pdo, $login);

if ($user && User::testMdp($user, $mdp)) {
    $_SESSION['loggedIn'] = true;
    $_SESSION['role'] = $user->role;
    $_SESSION['username'] = $user->nom;
    Flash::success("Heureux de vous revoir : $user->nom");
    header("Location: ../index.php?page=home");
} else {
    Flash::error("Erreur d'identifiants");
    header("Location: ../index.php?page=login");
}

exit;

?>