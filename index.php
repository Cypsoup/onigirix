<?php
// --- GESTION DE LA SESSION ---
session_name("SessionOnigirix");
session_start();

if (!isset($_SESSION['initiated'])) {
    session_regenerate_id();
    $_SESSION['initiated'] = true;
}

// --- IMPORTATION ---
require_once 'config/db.php';
require_once 'utils/flash.php';
require_once 'pages/pagesManager.php';
require_once 'pages/pagesRenderer.php';

// --- RECUPERATION DES ACCES ---
// Récupération du rôle et de la connexion
$access = $_SESSION['role'] ?? "user";
$isLogged = $_SESSION['loggedIn'] ?? 0;

// Récupération de la page en fonction des accès
$askedPage = $_GET["page"] ?? "dashboardUser";
$askedPage = PagesManager::checkPageExists($askedPage) ? $askedPage : "errorPage";
$askedPage = PagesManager::checkAccess($askedPage, $access, $isLogged) ? $askedPage : "errorAccess";

// Récupération du titre de la page 
$pageTitle = PagesManager::getPageTitle($askedPage);

// --- GENERATION DE LA PAGE ---
PagesRenderer::generateHTMLHeader($pageTitle);

PagesRenderer::generateMenu($askedPage, $access, $isLogged);

require("pages/page_" . $askedPage . ".php");

PagesRenderer::generateHTMLFooter();
?>