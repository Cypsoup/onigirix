<?php
// Démarrage de session
session_name("SessionOnigirix");
session_start();

if (!isset($_SESSION['initiated'])) {
    session_regenerate_id();
    $_SESSION['initiated'] = true;
}

// Importation des fichiers
require_once 'config/db.php';
require_once 'includes/functions.php';
require_once 'utils/pageGeneration.php';
require 'users/printForms.php';
require 'users/logInOut.php';

// Traitement de la connexion et déconnexion
if (isset($_POST['login']) && isset($_POST['mdp'])) {
    logIn($pdo);
}
if (isset($_GET['todo']) && $_GET['todo'] == 'logOut') {
    logOut();
}
var_dump($_SESSION);

$access = isset($_SESSION['role']) ? $_SESSION['role'] : "user";
$isLogged = isset($_SESSION['loggedIn']) ? $_SESSION['loggedIn'] : 0;

// Récupération de la page en fonction des accès
$askedPage = array_key_exists("page", $_GET) ? $_GET["page"] : "home";
$askedPage = checkPage($askedPage) ? $askedPage : "errorPage";
$authorized = checkAccess($askedPage, $access, $isLogged);
$askedPage = $authorized ? $askedPage : "errorAccess";

$pageTitle = getPageTitle($askedPage);


// HTML Header
generateHTMLHeader($pageTitle);

// Sidebar
generateSidebar($askedPage, $access, $isLogged);

// Chargement de la page
require("pages/page_" . $askedPage . ".php");

?>



<?php

# HTML Footer
generateHTMLFooter();

?>