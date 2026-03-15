<?php

class PagesManager
{
    public const AUTH_ANY = 'any';    // Tout le monde
    public const AUTH_GUEST = 'guest';  // Uniquement visiteurs 
    public const AUTH_LOGGED = 'logged'; // Uniquement membres

    private static $pagesList = [
        // PAGES PUBLIQUES
        ["name" => "dashboardUser", "title" => "Suivi - Onigirix", "access" => "user", "auth" => self::AUTH_LOGGED, "visibility" => true],
        ["name" => "menu", "title" => "La carte", "access" => "user", "auth" => self::AUTH_GUEST, "visibility" => true],
        ["name" => "commandeUser", "title" => "Commander", "access" => "user", "auth" => self::AUTH_LOGGED, "visibility" => true],

        // PAGES ADMIN
        ["name" => "dashboardAdmin", "title" => "Tableau de bord", "access" => "admin", "auth" => self::AUTH_LOGGED, "visibility" => true],
        ["name" => "inventory", "title" => "Inventaire", "access" => "admin", "auth" => self::AUTH_LOGGED, "visibility" => true],
        ["name" => "history", "title" => "Historique", "access" => "admin", "auth" => self::AUTH_LOGGED, "visibility" => true],

        // PAGES DE CONNEXION
        ["name" => "login", "title" => "Se connecter", "access" => "user", "auth" => self::AUTH_GUEST, "visibility" => true],
        ["name" => "userProfile", "title" => "Mon Profil", "access" => "user", "auth" => self::AUTH_LOGGED, "visibility" => true],
        ["name" => "logout", "title" => "Déconnexion", "access" => "user", "auth" => self::AUTH_LOGGED, "visibility" => true],

        // PAGES MASQUEES 
        ["name" => "errorPage", "title" => "Erreur", "access" => "user", "auth" => self::AUTH_ANY, "visibility" => false],
        ["name" => "errorAccess", "title" => "Accès Refusé", "access" => "user", "auth" => self::AUTH_ANY, "visibility" => false],
        ["name" => "createUser", "title" => "Créer un compte", "access" => "user", "auth" => self::AUTH_GUEST, "visibility" => false],
        ["name" => "editUser", "title" => "Modifier User", "access" => "user", "auth" => self::AUTH_LOGGED, "visibility" => false],
        ["name" => "editRecipe", "title" => "Modifier Recette", "access" => "admin", "auth" => self::AUTH_LOGGED, "visibility" => false],
    ];

    public static function getPagesList()
    {
        return self::$pagesList;
    }

    public static function checkAccess($askedPage, $userAccess, $isLogged)
    {
        foreach (self::$pagesList as $page) {
            if ($page["name"] == $askedPage) {
                // Etat de la connecion
                $authCondition = match ($page['auth']) {
                    self::AUTH_ANY => true,
                    self::AUTH_GUEST => !$isLogged, // Vrai si NON Connecté
                    self::AUTH_LOGGED => $isLogged, // Vrai si Connecté
                };

                // Restriction de la visibilité des pages inutiles pour l'admin
                if ($page["access"] === "user" && $userAccess === "admin") {
                    if ($askedPage === "dashboardUser" || $askedPage === "commandeUser")
                        return false;
                }

                // Etat des droits
                $hasRight = ($page["access"] == $userAccess || $userAccess == "admin");
                return ($hasRight && $authCondition);
            }
        }
        return false;
    }

    public static function checkPageExists($askedPage)
    {
        foreach (self::$pagesList as $page) {
            if ($page["name"] == $askedPage)
                return true;
        }
        return false;
    }

    public static function getPageTitle($askedPage)
    {
        foreach (self::$pagesList as $page) {
            if ($page["name"] == $askedPage)
                return $page["title"];
        }
        return "OnigiriX";
    }

    public static function isVisible($page, $isLogged)
    {
        if ($isLogged && isset($page['hide_if_logged']) && $page['hide_if_logged']) {
            return false;
        }
        return $page['visibility'];
    }
}