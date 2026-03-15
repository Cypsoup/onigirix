<?php

// Récupération de la Session en cours
session_name("SessionOnigirix");
session_start();

// Récupération des fichiers nécessaires
require_once '../utils/flash.php';

// Destruction de la session
$_SESSION = array();
session_destroy();

// Création d'une nouvelle session
session_name("SessionOnigirix");
session_start();
Flash::success("Vous avez été déconnecté. À bientôt !");

// Redirection
header("Location: ../index.php?page=dashboardUser");
exit;

?>