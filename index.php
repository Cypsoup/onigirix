<!DOCTYPE html>
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
require_once 'utils/pageGeneration.php';
require 'utils/flash.php';


var_dump($_SESSION);

// Récupération des accès et de la connexion
$access = $_SESSION['role'] ?? "user";
$isLogged = $_SESSION['loggedIn'] ?? 0;

// Récupération de la page en fonction des accès
$askedPage = array_key_exists("page", $_GET) ? $_GET["page"] : "home";
$askedPage = checkPage($askedPage) ? $askedPage : "errorPage";
$authorized = checkAccess($askedPage, $access, $isLogged);
$askedPage = $authorized ? $askedPage : "errorAccess";

// Récupération des affichages nécessaires : titre de la page et message flash si existant
$flash = Flash::get();
$pageTitle = getPageTitle($askedPage);


// HTML Header
generateHTMLHeader($pageTitle, $flash);

// Sidebar
generateSidebar($askedPage, $access, $isLogged);

// Chargement de la page
require("pages/page_" . $askedPage . ".php");

?>



<?php

# HTML Footer
generateHTMLFooter();

?>