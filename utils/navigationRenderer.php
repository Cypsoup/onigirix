<?php

class NavigationRenderer {
    // On stocke la configuration des boutons en propriété de classe
    private static $navItems = [
        'dashboardUser' => [
            'label' => 'ACCUEIL',
            'url' => 'dashboardUser.php',
            'svgClass' => 'lucide lucide-house',
            'svgPath' => '<path d="M15 21v-8a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v8"></path><path d="M3 10a2 2 0 0 1 .709-1.528l7-5.999a2 2 0 0 1 2.582 0l7 5.999A2 2 0 0 1 21 10v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>'
        ],
        'orderUser' => [
            'label' => 'COMMANDER',
            'url' => 'commandeUser.php',
            'svgClass' => 'lucide lucide-utensils-crossed',
            'svgPath' => '<path d="m16 2-2.3 2.3a3 3 0 0 0 0 4.2l1.8 1.8a3 3 0 0 0 4.2 0L22 8"></path><path d="M15 15 3.3 3.3a4.2 4.2 0 0 0 0 6l7.3 7.3c.7.7 2 .7 2.8 0L15 15Zm0 0 7 7"></path><path d="m2.1 21.8 6.4-6.3"></path><path d="m19 5-7 7"></path>'
        ],
        'historyUser' => [
            'label' => 'HISTORIQUE',
            'url' => '#', 
            'svgClass' => 'lucide lucide-history',
            'svgPath' => '<path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path><path d="M3 3v5h5"></path><path d="M12 7v5l4 2"></path>'
        ],
        'profileUser' => [
            'label' => 'PROFIL',
            'url' => '#', 
            'svgClass' => 'lucide lucide-user',
            'svgPath' => '<path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle>'
        ]
    ];

    // Méthode qui génère le HTML
    public static function renderBottomNav($activePage = 'dashboardUser') {
        echo '<nav class="fixed bottom-0 left-0 right-0 bg-white border-t-2 border-black flex justify-around items-center h-20 px-4 z-[60]">';
        
        // On utilise self::$navItems pour lire la propriété de la classe
        foreach (self::$navItems as $key => $item) {
            
            $isActive = ($key === $activePage);
            $textColor = $isActive ? 'text-[#E60012]' : 'text-zinc-400';
            $strokeWidth = $isActive ? '2.5' : '2';
            
            echo <<<HTML
            <a href="{$item['url']}" class="flex flex-col items-center gap-1 {$textColor} hover:text-[#E60012] transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="{$strokeWidth}" stroke-linecap="round" stroke-linejoin="round" class="{$item['svgClass']}" aria-hidden="true">
                    {$item['svgPath']}
                </svg>
                <span class="uppercase tracking-tighter text-[8px] font-black">{$item['label']}</span>
            </a>
            HTML;
        }
        
        echo '</nav>';
    }
}
?>