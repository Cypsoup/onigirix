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
require_once 'utils/flash.php';
require_once 'utils/LayoutRenderer.php';


// Debug : à enlever
var_dump($_SESSION);

// Récupération des accès et de la connexion utilisateur
$access = $_SESSION['role'] ?? "user";
$isLogged = $_SESSION['loggedIn'] ?? 0;

// Récupération de la page en fonction des accès
$askedPage = $_GET["page"] ?? "home";
$askedPage = LayoutRenderer::checkPage($askedPage) ? $askedPage : "errorPage";
$askedPage = LayoutRenderer::checkAccess($askedPage, $access, $isLogged) ? $askedPage : "errorAccess";

// Récupération du titre de la page 
$pageTitle = getPageTitle($askedPage);

// HTML Header
LayoutRenderer::generateHTMLHeader(LayoutRenderer::getPageTitle($askedPage));
// Sidebar
LayoutRenderer::generateSidebar($askedPage, $access, $isLogged);

// Chargement de la page
require("pages/page_" . $askedPage . ".php");

// HTML Footer
LayoutRenderer::generateHTMLFooter();
?>