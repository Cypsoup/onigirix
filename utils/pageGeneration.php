<!DOCTYPE html>
<?php
$page_list = array(
    array(
        "name" => "index",
        "title" => "Bienvenue chez Onigirix",
        "menutitle" => "Accueil",
        "icon" => "house",
        "access" => 0,
        "connected" => 0,
    ),
    array(
        "name" => "menu",
        "title" => "La carte",
        "menutitle" => "Menu",
        "icon" => "utensils",
        "access" => 0,
        "connected" => 0,
    ),
    array(
        "name" => "dashboardAdmin",
        "title" => "Tableau de bord",
        "menutitle" => "Tableau de bord",
        "icon" => "layout-dashboard",
        "access" => 1,
        "connected" => 1,
    ),
    array(
        "name" => "inventory",
        "title" => "Inventaire nourriture",
        "menutitle" => "Inventaire",
        "icon" => "package",
        "access" => 1,
        "connected" => 1,
    ),
    array(
        "name" => "history",
        "title" => "Historique des commandes",
        "menutitle" => "Historique",
        "icon" => "history",
        "access" => 1,
        "connected" => 1,
    )
);

function getPageTitle($askedPage)
{
    global $page_list;
    foreach ($page_list as $page) {
        if ($page["name"] == $askedPage)
            return $page["title"];
    }
}

function generateSidebar($askedPage, $user_access, $user_connected)
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
        if ($page["access"] <= $user_access && $page["connected"] <= $user_connected) {
            echo <<<end
            <a href="{$page["name"]}.php" class="{$active} p-2 rounded-lg transition-colors hover:text-white">
                <i data-lucide="{$page["icon"]}"></i>
            </a>
            end;
        }
    }
    echo <<<end
                </nav>
            </div>
    end;
    if ($user_connected) {
        echo '<a href="logout.php" class="text-white/50 hover:text-[#E60012] transition-colors"><i data-lucide="log-out"></i></a>';
    } else {
        echo '<a href="login.php" class="text-white/50 hover:text-[#E60012] transition-colors"><i data-lucide="log-in"></i></a>';
    }
    echo "</aside>";
}

function generateHTMLHeader($pageTitle)
{
    echo <<<end
        <!DOCTYPE html>
        <html lang="fr" class="h-full">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>$pageTitle</title>
            <!-- Tailwind CSS -->
            <script src="https://cdn.tailwindcss.com"></script>
            <!-- Custom CSS -->
            <link rel="stylesheet" href="css/style.css">
            <!-- Lucide Icons -->
            <script src="https://unpkg.com/lucide@latest"></script>
        </head>

        <body class="h-full bg-white text-black font-sans">

            <div class="flex h-full w-full">
    end;
}

function generateHTMLFooter()
{
    echo <<<end
        <!-- Javascript -->
        <script src="js/main.js"></script>
            </body>
        </html>
    end;
}

?>