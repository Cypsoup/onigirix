<?php
// Démarrage de session
session_name("SessionOnigirix");
session_start();

if (!isset($_SESSION['initiated'])) {
    session_regenerate_id();
    $_SESSION['initiated'] = true;
}
var_dump($_SESSION);

// Importation des fichiers
require_once 'config/db.php';
require_once 'includes/functions.php';
require_once 'utils/pageGeneration.php';


$user_access = 0;
$isLogged = 0;

// Récupération de la page et du titre
if (array_key_exists("page", $_GET)) {
    $askedPage = $_GET["page"];
    $authorized = checkPage($askedPage, $user_access, $isLogged);
} else {
    $askedPage = "index.php";
    $authorized = false;
}
$pageTitle = $authorized ? getPageTitle($askedPage) : "Accueil OnigiriX";

// HTML Header
generateHTMLHeader($pageTitle);

// Sidebar
generateSidebar($askedPage, $user_access, $isLogged);

?>



<?php

# HTML Footer
generateHTMLFooter();

?>