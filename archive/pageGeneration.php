<?php

require_once 'users/printForms.php';
require_once __DIR__ . '/flash.php';

$page_list = array(
    array(
        "name" => "home",
        "title" => "Bienvenue chez Onigirix",
        "menutitle" => "Accueil",
        "icon" => "house",
        "visibility" => true,
        "access" => "user",
        "connected" => 0,
    ),
    array(
        "name" => "menu",
        "title" => "La carte",
        "menutitle" => "Menu",
        "icon" => "utensils",
        "visibility" => true,
        "access" => "user",
        "connected" => 0,
    ),
    array(
        "name" => "login",
        "title" => "Se connecter",
        "menutitle" => "Se Connecter",
        "icon" => "",
        "visibility" => false,
        "access" => "user",
        "connected" => 0,
    ),
    array(
        "name" => "userProfile",
        "title" => "Mon Profil",
        "menutitle" => "Mon Profil",
        "icon" => "",
        "visibility" => false,
        "access" => "user",
        "connected" => 1,
    ),
    array(
        "name" => "dashboardAdmin",
        "title" => "Tableau de bord",
        "menutitle" => "Tableau de bord",
        "icon" => "layout-dashboard",
        "visibility" => true,
        "access" => "admin",
        "connected" => 1,
    ),
    array(
        "name" => "inventory",
        "title" => "Inventaire nourriture",
        "menutitle" => "Inventaire",
        "icon" => "package",
        "visibility" => true,
        "access" => "admin",
        "connected" => 1,
    ),
    array(
        "name" => "history",
        "title" => "Historique des commandes",
        "menutitle" => "Historique",
        "icon" => "history",
        "visibility" => true,
        "access" => "admin",
        "connected" => 1,
    ),
    // Pages d'erreurs
    array(
        "name" => "errorPage",
        "title" => "Erreur de chargement",
        "menutitle" => "Erreur de page",
        "icon" => "",
        "visibility" => false,
        "access" => "user",
        "connected" => 0,
    ),
    array(
        "name" => "errorAccess",
        "title" => "Erreur d'accès",
        "menutitle" => "Historique",
        "icon" => "",
        "visibility" => false,
        "access" => "user",
        "connected" => 0,
    ),
    // Pages d'actions
    array(
        "name" => "createUser",
        "title" => "Créer un compte",
        "menutitle" => "Créer un compte",
        "icon" => "",
        "visibility" => false,
        "access" => "user",
        "connected" => 0,
    ),
    array(
        "name" => "editUser",
        "title" => "Modifier l'utilisateur",
        "menutitle" => "Modifier User",
        "icon" => "",
        "visibility" => false,
        "access" => "user",
        "connected" => 1,
    ),
    array(
        "name" => "editRecipe",
        "title" => "Modifier la recette",
        "menutitle" => "Modifier",
        "icon" => "",
        "visibility" => false,
        "access" => "admin",
        "connected" => 1,
    ),
);

function checkAccess($askedPage, $userAccess, $isLogged)
{
    global $page_list;
    foreach ($page_list as $page) {
        if ($page["name"] == $askedPage && ($page["access"] == $userAccess || $userAccess == "admin") && $page["connected"] <= $isLogged)
            return true;
    }
    return false;
}

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

function generateSidebar($askedPage, $userAccess, $isLogged)
{
    global $page_list;
    echo <<<end
        <aside class="w-[60px] bg-black flex flex-col items-center py-6 justify-between z-20">
            <div class="flex flex-col items-center gap-8">
                <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center overflow-hidden">
                    <img src="images/logo.jpg" alt="Logo" class="object-cover w-full h-full">
                </div>

                <nav class="flex flex-col gap-6">
    end;
    foreach ($page_list as $page) {
        $active = ($askedPage == $page["name"]) ? 'text-[#E60012] bg-white/10' : 'text-white/50';
        if (checkAccess($page["name"], $userAccess, $isLogged) && $page["visibility"]) {
            echo <<<end
            <a href="?page={$page["name"]}" class="{$active} p-2 rounded-lg transition-colors hover:text-white">
                <i data-lucide="{$page["icon"]}"></i>
            </a>
            end;
        }
    }
    echo <<<end
                </nav>
            </div>
    end;

    echo '<div class="flex flex-col items-center gap-6 pb-2">';

    if ($isLogged) {
        $active = ($askedPage == "userProfile") ? 'text-[#E60012] bg-white/10' : 'text-white/50';
        echo <<<end
        <a href="?page=userProfile" class="{$active} hover:text-[#E60012] transition-colors">
            <i data-lucide="circle-user-round"></i>
            </a>
        end;
    }

    if ($isLogged) {
        printLogoutForm();
    } else {
        echo <<<end
            <a href="?page=login" class="text-white/50 hover:text-[#E60012] transition-colors">
                <i data-lucide="log-in"></i>
            </a>
        end;
    }
    echo "</div>";
    echo "</aside>";
}

function generateHTMLHeader($pageTitle)
{
    $flash = Flash::get();
    $message = htmlspecialchars($flash['message'] ?? '', ENT_QUOTES, 'UTF-8');
    $type = htmlspecialchars($flash['type'] ?? '', ENT_QUOTES, 'UTF-8');

    echo <<<end
        <!DOCTYPE html>
        <html lang="fr" class="h-full">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>{$pageTitle}</title>
            <!-- Tailwind CSS -->
            <script src="https://cdn.tailwindcss.com"></script>
            <script>
                tailwind.config = {
                    safelist: [
                        'bg-[#22C55E]',
                    ]
                }
            </script>

            <!-- Custom CSS -->
            <link rel="stylesheet" href="css/style.css">
            <!-- Lucide Icons -->
            <script src="https://unpkg.com/lucide@latest"></script>
            <!-- Javascript -->
            <script type="module" src="js/main.js"></script>
        </head>

        <body
            data-flash-message="{$message}"
            data-flash-type="{$type}"
            class="h-full bg-white text-black font-sans">
    end;
}

function generateHTMLFooter()
{
    echo <<<end
            </body>
        </html>
    end;
}

?>