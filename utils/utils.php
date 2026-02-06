<!DOCTYPE html>
<?php
$page_list = array(
    array(
        "name" => "index",
        "title" => "Bienvenue chez Onigirix",
        "menutitle" => "Accueil",
        "icon" => "",
        "access" => "all",
    ),
    array(
        "name" => "menu",
        "title" => "La carte",
        "menutitle" => "Menu",
        "icon" => "utensils",
        "access" => "all",
    ),
    array(
        "name" => "connexion",
        "title" => "Se connecter à votre compte",
        "menutitle" => "Se connecter",
        "icon" => "log-in",
        "access" => "all",
    ),
    array(
        "name" => "dashboardAdmin",
        "title" => "Tableau de bord",
        "menutitle" => "Tableau de bord",
        "icon" => "layout-dashboard",
        "access" => "admin",
    ),
    array(
        "name" => "inventory",
        "title" => "Inventaire nourriture",
        "menutitle" => "Inventaire",
        "icon" => "package",
        "access" => "admin",
    ),
    array(
        "name" => "history",
        "title" => "Historique des commandes",
        "menutitle" => "Historique",
        "icon" => "history",
        "access" => "admin",
    )
);

function checkPage($askedPage)
{
    global $page_list;
    foreach ($page_list as $page) {
        if ($page["name"] == $askedPage)
            return true;
    }
    return false;
}

function getPageTitle($askedPage)
{
    global $page_list;
    foreach ($page_list as $page) {
        if ($page["name"] == $askedPage)
            return $page["title"];
    }
}

?>