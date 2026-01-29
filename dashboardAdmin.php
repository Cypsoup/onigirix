<?php 
require_once 'config/db.php';
require_once 'includes/functions.php';
$activePage = 'dashboard'; 
?>

<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OnigiriX Admin - Dashboard</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/style.css">
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="h-full bg-white text-black font-sans">

    <div class="flex h-full w-full">

        <!-- SIDEBAR -->
        <?php include 'includes/sidebar.php'; ?>

        <main class="flex-1 grid grid-cols-4 h-full"> <!-- flex-1 pour prendre tout l'espace disponible -->
            
            <section class="flex flex-col column-divider h-full min-h-0"> <!-- min-h-0 car par défaut, min-height:auto et donc la colonne s'agrandit et on ne peut pas scroller -->
            <?php
                $pendingOrders = getOrdersByStatus($pdo, 'attente');
            ?>    
            <div class="p-4 flex justify-between items-center border-b border-black/5">
                    <h2 class="font-bold uppercase tracking-widest text-sm">En Attente</h2> <!-- tracking-widest pour étirer le texte, text-sm pour reduire la taille -->
                    <span class="font-black text-5xl"><?= count($pendingOrders) ?></span>
                </div>
                <div class="flex-1 overflow-y-auto p-4 space-y-4 no-scrollbar">
                    <?php
                        foreach ($pendingOrders as $order) {
                            renderOrderCard($pdo, $order);
                        }
                    ?>
                </div>
            </section>

            <section class="flex flex-col column-divider h-full min-h-0"> <!-- min-h-0 car par défaut, min-height:auto et donc la colonne s'agrandit et on ne peut pas scroller -->
            <?php
                $pendingOrders = getOrdersByStatus($pdo, 'prepa');
            ?>    
            <div class="p-4 flex justify-between items-center border-b border-black/5">
                    <h2 class="font-bold uppercase tracking-widest text-sm">En Préparation</h2> <!-- tracking-widest pour étirer le texte, text-sm pour reduire la taille -->
                    <span class="font-black text-5xl"><?= count($pendingOrders) ?></span>
                </div>
                <div class="flex-1 overflow-y-auto p-4 space-y-4 no-scrollbar">
                    <?php
                        foreach ($pendingOrders as $order) {
                            renderOrderCard($pdo, $order);
                        }
                    ?>
                </div>
            </section>

            <section class="flex flex-col column-divider h-full min-h-0"> <!-- min-h-0 car par défaut, min-height:auto et donc la colonne s'agrandit et on ne peut pas scroller -->
            <?php
                $pendingOrders = getOrdersByStatus($pdo, 'pret');
            ?>    
            <div class="p-4 flex justify-between items-center border-b border-black/5">
                    <h2 class="font-bold uppercase tracking-widest text-sm text-[#E60012]">Prêts</h2> <!-- tracking-widest pour étirer le texte, text-sm pour reduire la taille -->
                    <span class="font-black text-[#E60012] text-5xl"><?= count($pendingOrders) ?></span>
                </div>
                <div class="flex-1 overflow-y-auto p-4 space-y-4 no-scrollbar">
                    <?php
                        foreach ($pendingOrders as $order) {
                            renderOrderCard($pdo, $order);
                        }
                    ?>
                </div>
            </section>

            <aside class="bg-gray-50 flex flex-col p-4 gap-4 h-full border-l border-black/5">
                <div class="bg-white border border-black p-4">
                    <div class="flex gap-4 border-b border-black/10 mb-4 text-xs font-bold">
                        <button id="btn-next" onclick="switchStats('next')" class="pb-2 text-black border-b-2 border-black transition-colors">
                            À PRÉPARER
                        </button>
                        
                        <button id="btn-total" onclick="switchStats('total')" class="pb-2 text-black/40 border-b-2 border-transparent hover:text-black transition-colors">
                            EN ATTENTE
                        </button>
                    </div>

                    <div id="stats-container">
                        <div id="content-next" class="space-y-2 text-sm overflow-y-auto">
                            <?php
                                $stats = getStatsByStatus($pdo, 'prepa');
                                renderStats($stats);
                            ?>
                        </div>

                        <div id="content-total" class="hidden space-y-2 text-sm overflow-y-auto">
                            <?php
                                $stats = getStatsByStatus($pdo, 'attente');
                                renderStats($stats);
                            ?>
                        </div>
                    </div>
                </div>


                <div id="archiveContainer" class="bg-white border border-black p-4 flex-none overflow-hidden flex flex-col transition-all duration-300">
                    <button onclick="toggleArchives()" class="flex justify-between items-center w-full font-bold text-xs uppercase mb-0 group">
                        Commandes retirées
                        <i id="archiveIcon" data-lucide="chevron-down" class="w-5 h-5 transition-transform duration-300"></i>
                    </button>
                    <div id="archiveList" class="hidden text-xs text-black/40 space-y-2 overflow-y-auto mt-4">
                        <?php
                            $archivedOrders = getOrdersByStatus($pdo, 'archive');
                            if ($archivedOrders) {
                                foreach ($archivedOrders as $order) {
                                    renderArchivedOrder($order);
                                }
                            }  
                        ?>
                    </div>
                </div>

                <div class="bg-white border border-black p-4 space-y-3">
                    <button onclick="togglePanel()" class="w-full py-3 bg-zinc-800 text-white font-bold text-sm rounded flex items-center justify-center gap-2 hover:bg-black transition-colors">
                        <i data-lucide="plus-circle" class="w-4 h-4 mt-0.5"></i> AJOUTER COMMANDE
                    </button>
                    <div class="flex gap-2">
                        <button class="flex-1 py-2 border border-black flex justify-center hover:bg-gray-100"><i data-lucide="pause" class="w-4 h-4"></i></button>
                        <button class="flex-1 py-2 bg-black text-white flex justify-center hover:bg-zinc-800"><i data-lucide="power" class="w-4 h-4"></i></button>
                    </div>
                </div>
            </aside>
        </main>
    </div>

    <div id="overlay" class="fixed inset-0 bg-black/40 backdrop-blur-sm z-30 hidden opacity-0 transition-opacity duration-300"></div>
    
    <div id="slideOver" class="fixed top-0 right-0 h-full w-[400px] bg-white z-40 translate-x-full transition-transform duration-300 ease-in-out border-l-4 border-black shadow-2xl p-8">
        <div class="flex justify-between items-center mb-10">
            <h2 class="text-2xl font-black uppercase italic">Nouvelle Commande</h2>
            <button onclick="togglePanel()" class="hover:rotate-90 transition-transform"><i data-lucide="x"></i></button>
        </div>

        <form id="orderForm" class="space-y-8">
            <div>
                <label class="block text-xs font-bold uppercase mb-2">Trigramme</label>
                <input id="trigramme" type="text" maxlength="3" required class="w-full border-b-2 border-black text-3xl font-black focus:outline-none focus:border-[#E60012] uppercase placeholder-black/20" placeholder="ABC">
            </div>

            <div class="space-y-4">
                <label class="block text-xs font-bold uppercase">Onigiris</label>
                
                <div class="flex justify-between items-center py-2 border-b border-black/5 text-sm">
                    <span>Thon Mayo</span>
                    <div class="flex items-center gap-4">
                        <button type="button" onclick="document.getElementById('qty-thon').stepDown()" class="w-8 h-8 border border-black flex items-center justify-center hover:bg-black hover:text-white transition-colors">-</button>
                        
                        <input type="number" id="qty-thon" value="0" min="0" class="w-4 text-center font-bold outline-none appearance-none m-0 bg-transparent">
                        
                        <button type="button" onclick="document.getElementById('qty-thon').stepUp()" class="w-8 h-8 border border-black flex items-center justify-center hover:bg-black hover:text-white transition-colors">+</button>
                    </div>
                </div>

                <div class="flex justify-between items-center py-2 border-b border-black/5 text-sm">
                    <span>Poulet Teriyaki</span>
                    <div class="flex items-center gap-4">
                        <button type="button" onclick="document.getElementById('qty-poulet').stepDown()" class="w-8 h-8 border border-black flex items-center justify-center hover:bg-black hover:text-white">-</button>
                        <input type="number" id="qty-poulet" value="0" min="0" class="w-4 text-center font-bold outline-none appearance-none m-0 bg-transparent">
                        <button type="button" onclick="document.getElementById('qty-poulet').stepUp()" class="w-8 h-8 border border-black flex items-center justify-center hover:bg-black hover:text-white">+</button>
                    </div>
                </div>

                <div class="flex justify-between items-center py-2 border-b border-black/5 text-sm">
                    <span>Boeuf Gyudon</span>
                    <div class="flex items-center gap-4">
                        <button type="button" onclick="document.getElementById('qty-boeuf').stepDown()" class="w-8 h-8 border border-black flex items-center justify-center hover:bg-black hover:text-white">-</button>
                        <input type="number" id="qty-boeuf" value="0" min="0" class="w-4 text-center font-bold outline-none appearance-none m-0 bg-transparent">
                        <button type="button" onclick="document.getElementById('qty-boeuf').stepUp()" class="w-8 h-8 border border-black flex items-center justify-center hover:bg-black hover:text-white">+</button>
                    </div>
                </div>

            </div>

            <button type="submit" class="w-full py-4 bg-black text-white font-bold tracking-widest mt-10 hover:bg-[#E60012] transition-colors">
                VALIDER LA COMMANDE
            </button>
        </form>
    </div>

    <!-- Javascript -->
    <script src="js/main.js"></script>
</body>
</html>