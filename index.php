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
require_once 'events/event.php';

// Debug : à enlever
// var_dump($_SESSION);

// --- RECUPERATION DES ACCES ET SESSION ---
// Récupération des accès et de la connexion utilisateur
$access = $_SESSION['role'] ?? "user";
$isLogged = $_SESSION['loggedIn'] ?? 0;

// Récupération de l'event en cours, s'il existe
$openEvent = Event::getOpenEvent($pdo) ?? null;
$_SESSION['eventId'] = $openEvent?->id ?? null;

// Définition de la page par défaut si aucune page n'est demandée dans l'URL
if (!isset($_GET["page"])) {
    if (!$isLogged) {
        $askedPage = "login"; // Visiteur non connecté -> direct à la connexion
    } else {
        // Utilisateur connecté -> direct sur son tableau de bord respectif
        $askedPage = ($access === 'admin') ? "eventManager" : "dashboardUser";
    }
} else {
    // Si l'utilisateur a cliqué sur un lien spécifique (ex: ?page=menu)
    $askedPage = $_GET["page"];
}

// Vérification que la page existe
$askedPage = PagesManager::checkPageExists($askedPage) ? $askedPage : "errorPage";

// Gestion des erreurs d'accès
if (!PagesManager::checkAccess($askedPage, $access, $isLogged)) {
    // Si un visiteur essaie de forcer l'accès à une page privée, on le renvoie vers login
    $askedPage = (!$isLogged) ? "login" : "errorAccess";
}

// Récupération du titre de la page 
$pageTitle = PagesManager::getPageTitle($askedPage);

// --- GENERATION DE LA PAGE ---
PagesRenderer::generateHTMLHeader($pageTitle);

PagesRenderer::generateMenu($askedPage, $access, $isLogged);

require("pages/page_" . $askedPage . ".php");

// HTML Footer
PagesRenderer::generateHTMLFooter($access);
?>