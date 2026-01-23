<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OnigiriX Admin - Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        /* Effet de trait dégradé pour les séparateurs de colonnes */
        .brush-divider {
            border-right: 1px solid transparent;
            border-image: linear-gradient(to bottom, transparent, black, transparent) 1;
        }
        /* Masquage de la scrollbar */
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

        /* Enlève les flèches des input number sur Chrome/Safari/Edge */
        input[type=number]::-webkit-inner-spin-button, 
        input[type=number]::-webkit-outer-spin-button { 
            -webkit-appearance: none; 
            margin: 0; 
        }
        /* Pareil pour Firefox */
        input[type=number] {
            -moz-appearance: textfield;
        }
    </style>
</head>
<body class="h-full bg-white text-black font-sans">

    <div class="flex h-full w-full">

        <aside class="w-[60px] bg-black flex flex-col items-center py-6 justify-between z-20">
            <div class="flex flex-col items-center gap-8">
                <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center overflow-hidden">
                    <img src="images/logo.jpg" alt="Logo" class="object-cover w-full h-full">
                </div>
                <nav class="flex flex-col gap-6">
                    <a href="#" class="text-[#E60012] p-2 bg-white/10 rounded-lg"><i data-lucide="layout-dashboard"></i></a>
                    <a href="#" class="text-white/50 hover:text-white transition-colors p-2"><i data-lucide="package"></i></a>
                    <a href="#" class="text-white/50 hover:text-white transition-colors p-2"><i data-lucide="history"></i></a>
                </nav>
            </div>
            <a href="#" class="text-white/50 hover:text-[#E60012] transition-colors"><i data-lucide="log-out"></i></a>
        </aside>

        <main class="flex-1 grid grid-cols-4 h-full"> <!-- flex-1 pour prendre tout l'espace disponible -->
            
            <section class="flex flex-col brush-divider h-full min-h-0"> <!-- min-h-0 car par défaut, min-height:auto et donc la colonne s'agrandit et on ne peut pas scroller -->
                <div class="p-4 flex justify-between items-center border-b border-black/5">
                    <h2 class="font-bold uppercase tracking-widest text-sm">En Attente</h2> <!-- tracking-widest pour étirer le texte, text-sm pour reduire la taille -->
                    <span class="font-black text-5xl">12</span>
                </div>
                <div class="flex-1 overflow-y-auto p-4 space-y-4 no-scrollbar">
                    <div class="border border-black p-4 relative hover:shadow-lg transition-shadow bg-white group">
                        <button class="absolute top-2 right-2 text-black/20 hover:text-[#E60012] opacity-0 group-hover:opacity-100 transition-opacity duration-100"><i data-lucide="x" class="w-4 h-4"></i></button>
                        <div class="flex justify-between mb-3">
                            <div class="text-2xl font-black">JDO</div>
                            <div class="text-xs text-black/40">10 min</div>
                        </div>
                        <ul class="text-sm space-y-1 mb-5">
                            <li class="flex justify-between items-center">
                                <span>Poulet Teriyaki</span>
                                <span class="font-bold">x3</span>
                            </li>
                            <li class="flex justify-between items-center">
                                <span>Boeuf Gyudon</span>
                                <span class="font-bold">x1</span>
                            </li>
                        </ul>
                        <button class="uppercase w-full py-2 border border-black text-sm hover:bg-black hover:text-white transition-colors tracking-widest font-bold">Préparer</button>
                    </div>
                </div>
            </section>

            <section class="flex flex-col brush-divider h-full min-h-0">
                <div class="p-4 flex justify-between items-center border-b border-black/5">
                    <h2 class="font-bold uppercase tracking-widest text-sm">En Préparation</h2>
                    <span class="font-black text-5xl">5</span>
                </div>
                <div class="flex-1 overflow-y-auto p-4 space-y-4 no-scrollbar"> <!-- space-y-4 pour ajouter de l'espace entre les éléments -->
                    <div class="border-2 border-black p-4 bg-white shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">
                        <div class="flex justify-between mb-3">
                            <div class="text-2xl font-black">ABC</div>
                            <div class="text-xs text-black/40">3 min</div>
                        </div>
                        <ul class="text-sm mb-5">
                            <li class="flex justify-between items-center">
                                <span>Poulet Teriyaki</span>
                                <span class="font-bold">x3</span>
                            </li>
                        </ul>
                        <!-- <button class="w-full py-2 bg-white border border-[#E60012] text-[#E60012] hover:text-white hover:bg-[#E60012] text-sm font-bold tracking-widest transition-colors">PRÊT !</button> -->
                        <button class="w-full py-2 bg-green-500/25 border border-black hover:bg-green-500/70 text-sm font-bold tracking-widest transition-colors">PRÊT !</button>
                    </div>
                </div>
            </section>

            <section class="flex flex-col brush-divider h-full min-h-0">
                <div class="p-4 flex justify-between items-center border-b border-black/5">
                    <h2 class="font-bold uppercase tracking-widest text-sm text-[#E60012]">Prêts</h2>
                    <span class="font-black text-[#E60012] text-5xl">3</span>
                </div>
                <div class="flex-1 overflow-y-auto p-4 space-y-4 no-scrollbar">
                    <div class="border border-[#E60012] p-4 bg-white relative">
                        <div class="absolute top-0 right-0 w-10 h-10 bg-[#E60012]/10 [clip-path:polygon(0_0,100%_0,100%_100%)]"></div>
                        <div class="text-2xl font-black text-[#E60012] mb-3">XYZ</div>
                        <ul class="text-sm mb-5">
                            <li class="flex justify-between items-center">
                                <span class="text-[#99000C]">Poulet Teriyaki</span>
                                <span class="font-bold text-[#99000C]">x3</span>
                            </li>
                        </ul>
                        <button class="w-full py-2 bg-black hover:bg-[#E60012] text-white font-bold tracking-widest text-sm transition-colors">ARCHIVER</button>
                    </div>
                </div>
            </section>

            <aside class="bg-gray-50 flex flex-col p-4 gap-4 h-full border-l border-black/5">
                <div class="bg-white border border-black p-4">
                    <div class="flex gap-4 border-b border-black/10 mb-4 text-xs font-bold">
                        <button id="btn-next" onclick="switchStats('next')" class="pb-2 text-black border-b-2 border-black transition-colors">
                            NEXT 10
                        </button>
                        
                        <button id="btn-total" onclick="switchStats('total')" class="pb-2 text-black/40 border-b-2 border-transparent hover:text-black transition-colors">
                            TOTAL
                        </button>
                    </div>

                    <div class="relative h-20 overflow-hidden">
                        <div id="content-next" class="absolute inset-0 space-y-2 text-sm overflow-y-auto transition-opacity duration-200">
                            <div class="flex justify-between"><span>Thon Mayo</span><strong>14</strong></div>
                            <div class="flex justify-between"><span>Boeuf Gyudon</span><strong>8</strong></div>
                            <div class="flex justify-between"><span>Poulet Teriyaki</span><strong>5</strong></div>
                            <div class="flex justify-between"><span>Poulet Teriyaki</span><strong>5</strong></div>
                            <div class="flex justify-between"><span>Poulet Teriyaki</span><strong>5</strong></div>
                        </div>

                        <div id="content-total" class="absolute inset-0 space-y-2 text-sm overflow-y-auto opacity-0 pointer-events-none transition-opacity duration-200">
                            <div class="flex justify-between text-black"><span>Poulet</span><strong>2</strong></div>
                            <div class="flex justify-between text-black"><span>Thon</span><strong>1</strong></div>
                            <div class="flex justify-between text-black"><span>Saumon</span><strong>4</strong></div>
                        </div>
                    </div>
                </div>


                <div id="archiveContainer" class="bg-white border border-black p-4 flex-none overflow-hidden flex flex-col transition-all duration-300">
                    <button onclick="toggleArchives()" class="flex justify-between items-center w-full font-bold text-xs uppercase mb-0 group">
                        Archives
                        <i id="archiveIcon" data-lucide="chevron-down" class="w-5 h-5 transition-transform duration-300"></i>
                    </button>
                    <div id="archiveList" class="hidden text-xs text-black/40 space-y-2 overflow-y-auto mt-4">
                        <div class="flex justify-between border-b border-black/10 pb-1">
                            <span>#102 - ABC</span><span>12,50€</span>
                        </div>
                        <div class="flex justify-between border-b border-black/10 pb-1">
                            <span>#101 - JDO</span><span>8,00€</span>
                        </div>
                        <div class="flex justify-between border-b border-black/10 pb-1">
                            <span>#100 - XYZ</span><span>15,50€</span>
                        </div>
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

    <script>
        // Initialisation des icônes
        lucide.createIcons();

        // Logique d'ouverture du panel
        function togglePanel() {
            const panel = document.getElementById('slideOver');
            const overlay = document.getElementById('overlay');
            
            // Si le panneau est "caché", on l'ouvre
            if (panel.classList.contains('translate-x-full')) {
                panel.classList.remove('translate-x-full');
                overlay.classList.remove('hidden');
                setTimeout(() => overlay.classList.add('opacity-100'), 10); // on attend 10 ms pour laisser le temps au CSS de s'appliquer
            } else {
                panel.classList.add('translate-x-full');
                overlay.classList.remove('opacity-100');
                setTimeout(() => overlay.classList.add('hidden'), 300);
            }
        }

        // Logique d'ouverture du menu déroulant des commandes archivées
        function toggleArchives() {
            const container = document.getElementById('archiveContainer');
            const list = document.getElementById('archiveList');
            const icon = document.getElementById('archiveIcon');

            // On bascule la visibilité de la liste
            list.classList.toggle('hidden');

            // On fait tourner l'icône
            icon.classList.toggle('rotate-180');

            // Si la liste est visible (donc menu ouvert), on donne toute la place disponible au conteneur (flex-1)
            // Sinon, on le rend rigide (flex-none) pour qu'il ne prenne que la place du titre
            if (!list.classList.contains('hidden')) {
                container.classList.remove('flex-none');
                container.classList.add('flex-1');
                // Petit hack pour forcer le titre à garder sa marge quand c'est ouvert
                // (optionnel selon tes préférences de design)
            } else {
                container.classList.remove('flex-1');
                container.classList.add('flex-none');
            }
        }

        // Fonction pour changer d'onglet de stats
        function switchStats(tab) {
            // Récupération des éléments
            const btnTotal = document.getElementById('btn-total');
            const btnNext = document.getElementById('btn-next');
            const contentTotal = document.getElementById('content-total');
            const contentNext = document.getElementById('content-next');

            // Classes pour l'état ACTIF des boutons
            const activeClasses = ['text-black', 'border-black'];
            // Classes pour l'état INACTIF des boutons
            const inactiveClasses = ['text-black/40', 'border-transparent'];

            if (tab === 'total') {
                btnTotal.classList.add(...activeClasses);
                btnTotal.classList.remove(...inactiveClasses);
                
                btnNext.classList.add(...inactiveClasses);
                btnNext.classList.remove(...activeClasses);

                contentTotal.classList.remove('opacity-0', 'pointer-events-none');
                contentNext.classList.add('opacity-0', 'pointer-events-none');

            } else {
                // 1. Mise à jour des BOUTONS
                btnNext.classList.add(...activeClasses);
                btnNext.classList.remove(...inactiveClasses);

                btnTotal.classList.add(...inactiveClasses);
                btnTotal.classList.remove(...activeClasses);

                // 2. Mise à jour du CONTENU
                contentNext.classList.remove('opacity-0', 'pointer-events-none');
                contentTotal.classList.add('opacity-0', 'pointer-events-none');
            }
        }
    </script>
</body>
</html>