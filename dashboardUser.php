<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suivi de Commande - Onigiri</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        
        .gradient-red {
            background: linear-gradient(135deg, #E60012 0%, #B80010 100%);
        }
        
        .gradient-red-subtle {
            background: linear-gradient(180deg, #E60012 0%, #CC0010 100%);
        }
        
        .stepper-line {
            background: linear-gradient(to right, white 0%, white 50%, rgba(255,255,255,0.3) 50%);
        }
        
        .scroll-container {
            scrollbar-width: none;
            -ms-overflow-style: none;
        }
        
        .scroll-container::-webkit-scrollbar {
            display: none;
        }
    </style>
</head>
<body class="bg-white min-h-screen pb-20 font-sans">
    
    <!-- Header Sticky -->
    <header class="sticky top-0 bg-white z-50 px-4 py-4 flex items-center justify-between border-b border-black/5">
        <h1 class="text-lg font-semibold text-black">Salut, Alex!</h1>
        <div class="flex items-center gap-1.5 bg-black/5 rounded-full px-3 py-1.5">
            <span class="text-base">🍙</span>
            <span class="text-sm font-semibold text-black">12 pts</span>
        </div>
    </header>

    <!-- Section Hero - Suivi de Commande -->
    <section class="px-4 py-5">
        <div class="gradient-red rounded-2xl p-5 text-white shadow-lg">
            <!-- En-tête -->
            <div class="mb-4">
                <h2 class="text-xl font-bold mb-1">Préparation en cours !</h2>
            </div>
            
            <!-- Stepper de progression -->
            <div class="mb-6">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-semibold">Reçue</span>
                    <span class="text-xs font-semibold">Préparation</span>
                    <span class="text-xs font-semibold text-white/60">Prête</span>
                </div>
                <div class="relative h-1.5 bg-white/20 rounded-full overflow-hidden">
                    <div class="absolute left-0 top-0 h-full w-1/2 bg-white rounded-full"></div>
                </div>
            </div>
            
            <!-- Informations clés -->
            <div class="flex items-center justify-between mb-5 bg-white/10 rounded-xl p-4 backdrop-blur-sm">
                <div>
                    <p class="text-xs text-white/80 mb-1">Récupération estimée</p>
                    <p class="text-2xl font-bold">12:15</p>
                </div>
                <div class="text-right">
                    <p class="text-xs text-white/80 mb-1">Ta position</p>
                    <p class="text-2xl font-bold">#4</p>
                </div>
            </div>
            
            <!-- Liste des articles -->
            <div class="bg-white/15 rounded-xl p-4 backdrop-blur-sm mb-4">
                <p class="text-xs font-semibold text-white/90 mb-3 uppercase tracking-wide">Onigiris</p>
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-sm">Thon Épicé</span>
                        <span class="text-sm font-semibold">×2</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm">Saumon Onigiri</span>
                        <span class="text-sm font-semibold">×1</span>
                    </div>
                </div>
            </div>
            
            <!-- Bouton Cancel -->
            <button class="w-full bg-white/20 hover:bg-white/30 backdrop-blur-sm text-white font-semibold py-3.5 rounded-xl transition-all duration-200">
                Annuler la commande
            </button>
        </div>
    </section>

    <!-- Section Quick Re-order -->
    <section class="px-4 py-3">
        <h3 class="text-base font-bold text-black mb-4">Recommander rapidement</h3>
        <div class="flex gap-3 overflow-x-auto scroll-container pb-2">
            <!-- Card 1 -->
            <div class="flex-shrink-0 w-36 bg-black/5 rounded-xl p-4 flex flex-col items-center">
                <div class="w-16 h-16 bg-white rounded-xl flex items-center justify-center mb-3 shadow-sm">
                    <span class="text-3xl">🍙</span>
                </div>
                <p class="text-sm font-semibold text-black text-center mb-3">Set Thon Épicé</p>
                <button class="gradient-red-subtle w-full text-white text-xs font-semibold py-2 rounded-lg hover:shadow-md transition-all duration-200">
                    Commander
                </button>
            </div>
            
            <!-- Card 2 -->
            <div class="flex-shrink-0 w-36 bg-black/5 rounded-xl p-4 flex flex-col items-center">
                <div class="w-16 h-16 bg-white rounded-xl flex items-center justify-center mb-3 shadow-sm">
                    <span class="text-3xl">🍙</span>
                </div>
                <p class="text-sm font-semibold text-black text-center mb-3">Bœuf & Fromage</p>
                <button class="gradient-red-subtle w-full text-white text-xs font-semibold py-2 rounded-lg hover:shadow-md transition-all duration-200">
                    Commander
                </button>
            </div>
            
            <!-- Card 3 -->
            <div class="flex-shrink-0 w-36 bg-black/5 rounded-xl p-4 flex flex-col items-center">
                <div class="w-16 h-16 bg-white rounded-xl flex items-center justify-center mb-3 shadow-sm">
                    <span class="text-3xl">🍙</span>
                </div>
                <p class="text-sm font-semibold text-black text-center mb-3">Végétarien Mix</p>
                <button class="gradient-red-subtle w-full text-white text-xs font-semibold py-2 rounded-lg hover:shadow-md transition-all duration-200">
                    Commander
                </button>
            </div>
        </div>
    </section>

    <!-- Section Recent Activity -->
    <section class="px-4 py-3 mb-6">
        <h3 class="text-base font-bold text-black mb-4">Activité récente</h3>
        <div class="bg-black/5 rounded-xl p-4">
            <div class="flex items-start justify-between mb-3">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-white rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-black/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-black">Dernière commande</p>
                        <p class="text-xs text-black/50">Hier, 12:30</p>
                    </div>
                </div>
                <p class="text-sm font-bold text-black">€12,50</p>
            </div>
            
            <div class="space-y-1.5 mb-3 pl-1">
                <div class="flex items-center gap-2">
                    <span class="text-black/50 text-xs">•</span>
                    <span class="text-sm text-black/70">Thon Épicé</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-black/50 text-xs">•</span>
                    <span class="text-sm text-black/70">Saumon Onigiri ×2</span>
                </div>
            </div>
            
            <button class="text-sm font-semibold flex items-center gap-1 hover:gap-2 transition-all duration-200" style="color: #E60012;">
                Voir l'historique complet
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
        </div>
    </section>

    <!-- Navigation Bottom Fixed -->
    <nav class="fixed bottom-0 left-0 right-0 bg-white border-t border-black/10 px-2 py-2 z-50">
        <div class="flex items-center justify-around">
            <!-- Home - Active -->
            <button class="flex flex-col items-center gap-1 px-4 py-2 rounded-lg transition-all duration-200" style="color: #E60012;">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/>
                </svg>
                <span class="text-xs font-semibold">Accueil</span>
            </button>
            
            <!-- Menu -->
            <button class="flex flex-col items-center gap-1 px-4 py-2 rounded-lg text-black/40 hover:text-black/70 transition-all duration-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
                <span class="text-xs font-medium">Menu</span>
            </button>
            
            <!-- Orders -->
            <button class="flex flex-col items-center gap-1 px-4 py-2 rounded-lg text-black/40 hover:text-black/70 transition-all duration-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="text-xs font-medium">Commandes</span>
            </button>
            
            <!-- Profile -->
            <button class="flex flex-col items-center gap-1 px-4 py-2 rounded-lg text-black/40 hover:text-black/70 transition-all duration-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <span class="text-xs font-medium">Profil</span>
            </button>
        </div>
    </nav>

</body>
</html>
