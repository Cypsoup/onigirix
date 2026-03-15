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
require_once 'utils/flash.php';
require_once 'pages/pagesManager.php';
require_once 'pages/pagesRenderer.php';

// Récupération des accès et de la connexion utilisateur
$access = $_SESSION['role'] ?? "user";
$isLogged = $_SESSION['loggedIn'] ?? 0;

// Récupération de la page en fonction des accès
$askedPage = $_GET["page"] ?? "dashboardUser";
$askedPage = PagesManager::checkPageExists($askedPage) ? $askedPage : "errorPage";
$askedPage = PagesManager::checkAccess($askedPage, $access, $isLogged) ? $askedPage : "errorAccess";

// Récupération du titre de la page 
$pageTitle = PagesManager::getPageTitle($askedPage);

// HTML Header
PagesRenderer::generateHTMLHeader($pageTitle);
// Sidebar
PagesRenderer::generateMenu($askedPage, $access, $isLogged);

// Chargement de la page
require("pages/page_" . $askedPage . ".php");

// HTML Footer
PagesRenderer::generateHTMLFooter();
?>