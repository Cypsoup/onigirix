<?php
require_once 'users/printForms.php';
require_once __DIR__ . '/../utils/flash.php';

class PagesRenderer
{
    private static $navItems = [
        // PAGES PUBLIQUES 
        'dashboardUser' => [
            'label' => 'ACCUEIL',
            'icon' => 'house',
            'svgPath' => '<path d="M15 21v-8a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v8"></path><path d="M3 10a2 2 0 0 1 .709-1.528l7-5.999a2 2 0 0 1 2.582 0l7 5.999A2 2 0 0 1 21 10v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>'
        ],
        'menu' => [
            'label' => 'LA CARTE',
            'icon' => 'utensils',
            'svgPath' => '<path d="M3 2v7c0 1.1.9 2 2 2h4a2 2 0 0 0 2-2V2"></path><path d="M7 2v20"></path><path d="M21 15V2v0a5 5 0 0 0-5 5v6c0 1.1.9 2 2 2h3Zm0 0v7"></path>'
        ],
        'commandeUser' => [
            'label' => 'COMMANDER',
            'icon' => 'utensils-crossed',
            'svgPath' => '<path d="m16 2-2.3 2.3a3 3 0 0 0 0 4.2l1.8 1.8a3 3 0 0 0 4.2 0L22 8"></path><path d="M15 15 3.3 3.3a4.2 4.2 0 0 0 0 6l7.3 7.3c.7.7 2 .7 2.8 0L15 15Zm0 0 7 7"></path><path d="m2.1 21.8 6.4-6.3"></path><path d="m19 5-7 7"></path>'
        ],

        // PAGES ADMIN
        'eventManager' => [
            'label' => 'SERVICES',
            'icon' => 'calendar-days',
            'svgPath' => '<path d="M8 2v4"></path><path d="M16 2v4"></path><rect width="18" height="18" x="3" y="4" rx="2"></rect><path d="M3 10h18"></path><path d="M8 14h.01"></path><path d="M12 14h.01"></path><path d="M16 14h.01"></path><path d="M8 18h.01"></path><path d="M12 18h.01"></path><path d="M16 18h.01"></path>'
        ],
        'dashboardAdmin' => [
            'label' => 'DASHBOARD',
            'icon' => 'layout-dashboard',
            'svgPath' => '<rect width="7" height="9" x="3" y="3" rx="1"></rect><rect width="7" height="5" x="14" y="3" rx="1"></rect><rect width="7" height="9" x="14" y="12" rx="1"></rect><rect width="7" height="5" x="3" y="16" rx="1"></rect>'
        ],
        'inventory' => [
            'label' => 'INVENTAIRE',
            'icon' => 'package',
            'svgPath' => '<path d="M16.5 9.4 7.5 4.21"></path><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.29 7 12 12 20.71 7"></polyline><line x1="12" y1="22" x2="12" y2="12"></line>'
        ],
        'history' => [
            'label' => 'HISTORIQUE',
            'icon' => 'history',
            'svgPath' => '<path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path><path d="M3 3v5h5"></path><path d="M12 7v5l4 2"></path>'
        ],

        // PAGES DE CONNEXION
        'login' => [
            'label' => 'CONNEXION',
            'icon' => 'log-in',
            'svgPath' => '<path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path><polyline points="10 17 15 12 10 7"></polyline><line x1="15" y1="12" x2="3" y2="12"></line>'
        ],
        'userProfile' => [
            'label' => 'MON PROFIL',
            'icon' => 'circle-user-round',
            'svgPath' => '<path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle>'
        ],
        'logout' => [
            'label' => 'DÉCONNEXION',
            'icon' => 'log-out',
            'url' => 'actions/processLogout.php',
            'svgPath' => '<path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line>'
        ],

        // PAGES MASQUÉES 
        'errorPage' => [
            'label' => 'ERREUR PAGE',
            'icon' => 'octagon-alert',
            'svgPath' => '<path d="M12 16h.01"></path><path d="M12 8v4"></path><path d="M15.312 2a2 2 0 0 1 1.414.586l4.688 4.688A2 2 0 0 1 22 8.688v6.624a2 2 0 0 1-.586 1.414l-4.688 4.688a2 2 0 0 1-1.414.586H8.688a2 2 0 0 1-1.414-.586l-4.688-4.688A2 2 0 0 1 2 15.312V8.688a2 2 0 0 1 .586-1.414l4.688-4.688A2 2 0 0 1 8.688 2z"></path>'
        ],
        'errorAccess' => [
            'label' => 'ACCÈS REFUSÉ',
            'icon' => 'shield-alert',
            'svgPath' => '<path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"></path><path d="M12 8v4"></path><path d="M12 16h.01"></path>'
        ],
        'createUser' => [
            'label' => 'NOUVEAU COMPTE',
            'icon' => 'user-plus',
            'svgPath' => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><line x1="19" y1="8" x2="19" y2="14"></line><line x1="22" y1="11" x2="16" y2="11"></line>'
        ],
        'editUser' => [
            'label' => 'PARAMÈTRES USER',
            'icon' => 'user-cog',
            'svgPath' => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><circle cx="19" cy="11" r="3"></circle><path d="m19 8v1"></path><path d="m19 13v1"></path><path d="m21.6 9.5-.86.5"></path><path d="m17.26 12-.86.5"></path><path d="m21.6 12.5-.86-.5"></path><path d="m17.26 10-.86-.5"></path>'
        ],
        'editRecipe' => [
            'label' => 'MODIFIER',
            'icon' => 'pencil',
            'svgPath' => '<path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"></path><path d="m15 5 4 4"></path>'
        ],
    ];

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
                <title>{$pageTitle}</title>
                <script src="https://cdn.tailwindcss.com"></script>
                <link rel="stylesheet" href="css/style.css">
                <script src="https://unpkg.com/lucide@latest"></script>
                <script type="module" src="js/main.js"></script>
            </head>
            <body data-flash-message="{$message}" data-flash-type="{$type}" class="h-full bg-white text-black font-sans">
        HTML;
    }

    public static function generateMenu($askedPage, $userAccess, $isLogged)
    {
        // SI C'EST UN ADMIN, ON OUVRE LA DIV FLEX POUR ALIGNER LE MENU ET LE CONTENU
        if ($userAccess === 'admin' && $isLogged) {
            echo '<div class="flex h-full w-full">';
            self::renderSidebar($askedPage, $userAccess, $isLogged);
        } else {
            self::renderBottomNav($askedPage, $userAccess, $isLogged);
        }
    }

    public static function renderSidebar($askedPage, $userAccess, $isLogged)
    {
        echo <<<HTML
            <aside class="w-[60px] bg-black flex flex-col items-center py-6 justify-between z-20">
                <div class="flex flex-col items-center gap-8">
                    <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center overflow-hidden">
                        <img src="images/logo.jpg" alt="Logo" class="object-cover w-full h-full">
                    </div>
                    <nav class="flex flex-col gap-6">
        HTML;

        foreach (PagesManager::getPagesList() as $page) {
            $name = $page['name'];
            if ($page['visibility'] && PagesManager::checkAccess($name, $userAccess, $isLogged)) {
                // Récupération de l'item
                if (!isset(self::$navItems[$name]))
                    continue;
                $item = self::$navItems[$name];

                // Récupération des données à afficher
                $url = isset($item['url']) ? $item['url'] : "?page={$name}";
                $icon = $item['icon'] ?? 'circle';
                $active = ($askedPage == $name) ? 'text-[#E60012] bg-white/10' : 'text-white/50';

                // Affichage
                echo <<<HTML
                    <a href="{$url}" class="{$active} p-2 rounded-lg transition-colors hover:text-white">
                        <i data-lucide="{$icon}"></i>
                    </a>
                HTML;
            }
        }
        echo <<<HTML
                    </nav>
                </div>
            </aside>
        HTML;
    }

    public static function renderBottomNav($askedPage, $userAccess, $isLogged)
    {
        echo '<nav class="fixed bottom-0 left-0 right-0 bg-white border-t-2 border-black flex justify-around items-center h-20 px-4 z-[60]">';

        foreach (PagesManager::getPagesList() as $page) {
            $name = $page['name'];

            if ($page['visibility'] && PagesManager::checkAccess($name, $userAccess, $isLogged)) {
                // Récupération de l'item
                if (!isset(self::$navItems[$name]))
                    continue;
                $item = self::$navItems[$name];

                // Récupération des données de l'affichage
                $url = isset($item['url']) ? $item['url'] : "?page={$name}";
                $isActive = ($name === $askedPage);
                $color = $isActive ? 'text-[#E60012]' : 'text-zinc-400';
                $strokeWidth = $isActive ? '2.5' : '2';

                // Affichage
                echo <<<HTML
                    <a href="{$url}" class="flex flex-col items-center gap-1 {$color} hover:text-[#E60012] transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" 
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" 
                            stroke-width="{$strokeWidth}" stroke-linecap="round" stroke-linejoin="round" 
                            class="lucide" aria-hidden="true">
                            {$item['svgPath']}
                        </svg>
                        <span class="uppercase tracking-tighter text-[8px] font-black">{$item['label']}</span>
                    </a>
                HTML;
            }
        }

        echo '</nav>';
    }
    public static function generateHTMLFooter($userAccess)
    {
        if ($userAccess === 'admin') {
            echo '</div>'; // ON FERME LA DIV FLEX DE L'ADMIN
        }
        echo "</body></html>";
    }
}