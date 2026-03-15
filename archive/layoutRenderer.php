<?php

require_once 'users/printForms.php';
require_once __DIR__ . '/flash.php';

class LayoutRenderer
{
    // On stocke la liste des pages en "private static" (sécurisé, accessible uniquement par cette classe)
    private static $page_list = [
        [
            "name" => "dashboardUser",
            "title" => "Suivi - Onigirix",
            "menutitle" => "Accueil",
            "icon" => "house",
            "visibility" => true,
            "access" => "user",
            "connected" => 0,
        ],
        [
            "name" => "menu",
            "title" => "La carte",
            "menutitle" => "Menu",
            "icon" => "utensils",
            "visibility" => true,
            "access" => "user",
            "connected" => 0,
        ],
        [
            "name" => "login",
            "title" => "Se connecter",
            "menutitle" => "Se Connecter",
            "icon" => "",
            "visibility" => false,
            "access" => "user",
            "connected" => 0,
        ],
        [
            "name" => "userProfile",
            "title" => "Mon Profil",
            "menutitle" => "Mon Profil",
            "icon" => "",
            "visibility" => false,
            "access" => "user",
            "connected" => 1,
        ],
        [
            "name" => "dashboardAdmin",
            "title" => "Tableau de bord",
            "menutitle" => "Tableau de bord",
            "icon" => "layout-dashboard",
            "visibility" => true,
            "access" => "admin",
            "connected" => 1,
        ],
        [
            "name" => "inventory",
            "title" => "Inventaire nourriture",
            "menutitle" => "Inventaire",
            "icon" => "package",
            "visibility" => true,
            "access" => "admin",
            "connected" => 1,
        ],
        [
            "name" => "history",
            "title" => "Historique des commandes",
            "menutitle" => "Historique",
            "icon" => "history",
            "visibility" => true,
            "access" => "admin",
            "connected" => 1,
        ],
        // Pages d'erreurs
        [
            "name" => "errorPage",
            "title" => "Erreur de chargement",
            "menutitle" => "Erreur de page",
            "icon" => "",
            "visibility" => false,
            "access" => "user",
            "connected" => 0,
        ],
        [
            "name" => "errorAccess",
            "title" => "Erreur d'accès",
            "menutitle" => "Historique",
            "icon" => "",
            "visibility" => false,
            "access" => "user",
            "connected" => 0,
        ],

        // Pages d'actions
        [
            "name" => "createUser",
            "title" => "Créer un compte",
            "menutitle" => "Créer un compte",
            "icon" => "",
            "visibility" => false,
            "access" => "user",
            "connected" => 0,
        ],
        [
            "name" => "editUser",
            "title" => "Modifier l'utilisateur",
            "menutitle" => "Modifier User",
            "icon" => "",
            "visibility" => false,
            "access" => "user",
            "connected" => 1,
        ],
        [
            "name" => "editRecipe",
            "title" => "Modifier la recette",
            "menutitle" => "Modifier",
            "icon" => "",
            "visibility" => false,
            "access" => "admin",
            "connected" => 1,
        ],
    ];

    public static function checkAccess($askedPage, $userAccess, $isLogged)
    {
        foreach (self::$page_list as $page) {
            if ($page["name"] == $askedPage && ($page["access"] == $userAccess || $userAccess == "admin") && $page["connected"] <= $isLogged) {
                return true;
            }
        }
        return false;
    }

    public static function checkPage($askedPage)
    {
        foreach (self::$page_list as $page) {
            if ($page["name"] == $askedPage)
                return true;
        }
        return false;
    }

    public static function getPageTitle($askedPage)
    {
        foreach (self::$page_list as $page) {
            if ($page["name"] == $askedPage)
                return $page["title"];
        }
        return "OnigiriX"; // Titre par défaut si la page n'est pas trouvée
    }

    public static function generateHTMLHeader($pageTitle)
    {
        $flash = Flash::get();
        $message = htmlspecialchars($flash['message'] ?? '', ENT_QUOTES, 'UTF-8');
        $type = htmlspecialchars($flash['type'] ?? '', ENT_QUOTES, 'UTF-8');

        echo <<<HTML
            <!DOCTYPE html>
            <html lang="fr" class="h-full">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>{$pageTitle}</title>
                <script src="https://cdn.tailwindcss.com"></script>
                <script>
                    tailwind.config = {
                        safelist: ['bg-[#22C55E]']
                    }
                </script>
                <link rel="stylesheet" href="css/style.css">
                <script src="https://unpkg.com/lucide@latest"></script>
                <script type="module" src="js/main.js"></script>
            </head>
            <body data-flash-message="{$message}" data-flash-type="{$type}" class="h-full bg-white text-black font-sans">
        HTML;
    }

    public static function generateMenu($askedPage, $userAccess, $isLogged)
    {
        echo <<<HTML
            <aside class="w-[60px] bg-black flex flex-col items-center py-6 justify-between z-20">
                <div class="flex flex-col items-center gap-8">
                    <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center overflow-hidden">
                        <img src="images/logo.jpg" alt="Logo" class="object-cover w-full h-full">
                    </div>
                    <nav class="flex flex-col gap-6">
        HTML;

        foreach (self::$page_list as $page) {
            $active = ($askedPage == $page["name"]) ? 'text-[#E60012] bg-white/10' : 'text-white/50';
            if (self::checkAccess($page["name"], $userAccess, $isLogged) && $page["visibility"]) {
                echo <<<HTML
                <a href="?page={$page['name']}" class="{$active} p-2 rounded-lg transition-colors hover:text-white">
                    <i data-lucide="{$page['icon']}"></i>
                </a>
                HTML;
            }
        }

        echo <<<HTML
                    </nav>
                </div>
                <div class="flex flex-col items-center gap-6 pb-2">
        HTML;

        if ($isLogged) {
            $active = ($askedPage == "userProfile") ? 'text-[#E60012] bg-white/10' : 'text-white/50';
            echo <<<HTML
            <a href="?page=userProfile" class="{$active} hover:text-[#E60012] transition-colors">
                <i data-lucide="circle-user-round"></i>
            </a>
            HTML;
            printLogoutForm();
        } else {
            echo <<<HTML
                <a href="?page=login" class="text-white/50 hover:text-[#E60012] transition-colors">
                    <i data-lucide="log-in"></i>
                </a>
            HTML;
        }

        echo "</div></aside>";
    }

    public static function generateHTMLFooter()
    {
        echo "</body></html>";
    }
}
?>