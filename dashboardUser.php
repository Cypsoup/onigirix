<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suivi de Commande - Onigiri</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white min-h-screen pb-20 font-sans">
    
    <!-- Header -->
    <header class="flex items-center justify-between p-6 border-b-2 border-black">
        <div class="flex flex-col gap-1">
            <h3 class="text-[10px] text-black/50">BIENVENUE</h3>
            <h1 class="text-xl font-black italic text-black">KON'NICHIWA, Alex!</h1>
        </div>
        <div class="w-16 h-12 bg-white flex items-center justify-center">
            <img src="images/logo.jpg" alt="Logo" class="object-cover w-full h-full">
        </div>
  
    </header>
    
    <main class="p-6">
        <div class="opacity-1">
            <section id="bloc-commande-en-cours" class="border-2 border-black p-4 shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] relative mb-10">
                <div class="flex justify-between items-start mb-6">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 bg-[#E60012] animate-pulse"></div>
                        <span class="font-mono uppercase tracking-tighter text-sm font-bold">EN ATTENTE</span>
                    </div>
                    <span class="font-mono uppercase tracking-tighter text-sm text-zinc-400">CUC</span>
                </div>

                <div class="flex justify-between mb-8 border-b-2 border-black border-dotted pb-6 px-2">
                    <div class="flex flex-col items-center gap-2 opacity-100 text-black">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock" aria-hidden="true">
                            <path d="M12 6v6l4 2"></path>
                            <circle cx="12" cy="12" r="10"></circle>
                        </svg>
                        <div class="h-1 w-full bg-black"></div>
                    </div>
                    <div class="flex flex-col items-center gap-2 text-zinc-300">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chef-hat" aria-hidden="true">
                            <path d="M17 21a1 1 0 0 0 1-1v-5.35c0-.457.316-.844.727-1.041a4 4 0 0 0-2.134-7.589 5 5 0 0 0-9.186 0 4 4 0 0 0-2.134 7.588c.411.198.727.585.727 1.041V20a1 1 0 0 0 1 1Z"></path>
                            <path d="M6 17h12"></path>
                        </svg>
                        <div class="h-1 w-full bg-zinc-200"></div>
                    </div>
                    <div class="flex flex-col items-center gap-2 text-zinc-300">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-check text-zinc-300" aria-hidden="true">
                            <circle cx="12" cy="12" r="10"></circle>
                            <path d="m9 12 2 2 4-4"></path>
                        </svg>
                        <div class="h-1 w-full bg-zinc-200"></div>
                    </div>
                </div>

                <ul class="space-y-2 mb-12">
                    <li class="flex justify-between font-mono text-sm">
                        <span>2X SAUMON GRILLÉ</span>
                        <span class="font-bold">12.00€</span>
                    </li>
                    <li class="flex justify-between font-mono text-sm">
                        <span>1X THON MAYO</span>
                        <span class="font-bold">5.50€</span>
                    </li>
                </ul>

                <div class="absolute bottom-4 right-4 text-right">
                    <span class="font-mono uppercase tracking-tighter text-[10px] block text-zinc-400">POSITION</span>
                    <span class="text-5xl font-black leading-none">#12</span>
                </div>

                <button class="border-2 border-black border-dotted px-4 py-2 text-xs font-bold flex items-center gap-2 hover:bg-zinc-50">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x" aria-hidden="true">
                        <path d="M18 6 6 18"></path>
                        <path d="m6 6 12 12"></path>
                    </svg> 
                    ANNULER
                </button>
            </section>

            <section id="bloc-aucune-commande" class="border-2 border-black p-6 shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] mb-10 bg-white flex flex-col gap-8">
                <div class="h-40 flex items-center justify-center relative">
                    <img src="./images/logo.png" alt="Logo" class="max-h-full w-auto object-contain">
                </div>
                
                <div class="flex flex-col gap-6">
                    <div class="text-center flex flex-col gap-1">
                        <h3 class="text-2xl font-black italic uppercase">ONIGIRIX EST OUVERT</h3>
                        <p class="text-sm text-zinc-500">C'est le moment de commander !</p>
                    </div>
                    
                    <div class="flex items-stretch gap-3">
                        <button class="grow bg-white text-black font-bold py-4 border-2 border-black flex items-center justify-center">
                            COMMANDER MAINTENANT 
                        </button>
                        
                        <div class="bg-[#E60012] text-white px-6 flex items-center justify-center animate-pulse">
                            <span class="font-mono uppercase tracking-tighter text-sm font-bold">LIVE</span>
                        </div>
                    </div>
                </div>
            </section>

            <section class="mb-10">
                <h3 class="font-black text-xl italic uppercase tracking-tighter mb-4">Vos statistiques</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div class="border-2 border-black bg-white p-4 shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] col-span-1 py-6 flex flex-col justify-center items-center text-center">
                        <span class="uppercase tracking-tighter text-3xl font-black mb-1">12</span>
                        <p class="text-[10px] font-bold text-zinc-400 uppercase leading-none">Onigiris<br>Consommés</p>
                    </div>
                    <div class="border-2 border-black bg-white p-4 shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] col-span-1 py-6 flex flex-col justify-center items-center text-center">
                        <span class="uppercase tracking-tighter text-3xl font-black mb-1">04</span>
                        <p class="text-[10px] font-bold text-zinc-400 uppercase leading-none">Commandes<br>Passées</p>
                    </div>
                    
                    <div class="border-2 border-black bg-white p-4 shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] col-span-2 py-4 flex items-center justify-between px-6">
                        <div class="text-left">
                            <span class="font-mono uppercase tracking-tighter text-[10px] text-zinc-400 uppercase">Onigiri préféré</span>
                            <h4 class="font-black text-lg italic uppercase">Saumon Spicy</h4>
                        </div>
                        <div class="w-10 h-10 bg-[#E60012] flex items-center justify-center">
                            <div class="w-4 h-4 bg-white rotate-45"></div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="mb-10">
                <div class="flex justify-between items-end mb-4">
                    <h3 class="font-black text-xl italic uppercase tracking-tighter">Dernières Commandes</h3>
                    <span class="font-mono uppercase tracking-tighter text-[10px] text-zinc-400">VOIR TOUT</span>
                </div>
                
                <div class="space-y-4">
                    <div class="flex justify-between items-center border-b-2 border-black pb-4">
                        <div>
                        <span class="font-mono uppercase tracking-tighter text-[10px] text-zinc-400">Hier</span>
                        <p class="font-bold text-sm uppercase">2x Saumon, 1x Thon</p>
                        </div>
                        <div class="flex items-center gap-4">
                        <span class="font-mono uppercase tracking-tighter font-bold">17.50€</span>
                        <button class="w-10 h-10 border-2 border-black flex items-center justify-center bg-white active:bg-zinc-100">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-rotate-ccw" aria-hidden="true">
                            <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path>
                            <path d="M3 3v5h5"></path>
                            </svg>
                        </button>
                        </div>
                    </div>
                    
                    <div class="flex justify-between items-center border-b-2 border-black pb-4">
                        <div>
                        <span class="font-mono uppercase tracking-tighter text-[10px] text-zinc-400">12 Mars</span>
                        <p class="font-bold text-sm uppercase">1x Prune Umeboshi</p>
                        </div>
                        <div class="flex items-center gap-4">
                        <span class="font-mono uppercase tracking-tighter font-bold">4.50€</span>
                        <button class="w-10 h-10 border-2 border-black flex items-center justify-center bg-white active:bg-zinc-100">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-rotate-ccw" aria-hidden="true">
                            <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path>
                            <path d="M3 3v5h5"></path>
                            </svg>
                        </button>
                        </div>
                    </div>
                    
                    <div class="flex justify-between items-center border-b-2 border-black pb-4">
                        <div>
                            <span class="font-mono uppercase tracking-tighter text-[10px] text-zinc-400">05 Mars</span>
                            <p class="font-bold text-sm uppercase">3x Saumon Spicy</p>
                        </div>
                        <div class="flex items-center gap-4">
                            <span class="font-mono uppercase tracking-tighter font-bold">18.00€</span>
                            <button class="w-10 h-10 border-2 border-black flex items-center justify-center bg-white active:bg-zinc-100">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-rotate-ccw" aria-hidden="true">
                                <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path>
                                <path d="M3 3v5h5"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>


    <!-- Navigation Bottom Fixed -->
    <nav class="fixed bottom-0 left-0 right-0 bg-white border-t-2 border-black flex justify-around items-center h-20 px-4 z-50">
        <button class="flex flex-col items-center gap-1 text-[#E60012]">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-house" aria-hidden="true">
                <path d="M15 21v-8a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v8"></path>
                <path d="M3 10a2 2 0 0 1 .709-1.528l7-5.999a2 2 0 0 1 2.582 0l7 5.999A2 2 0 0 1 21 10v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
            </svg>
            <span class="uppercase tracking-tighter text-[8px] font-black">ACCUEIL</span>
        </button>

        <button class="flex flex-col items-center gap-1 text-zinc-400">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-utensils-crossed" aria-hidden="true">
                <path d="m16 2-2.3 2.3a3 3 0 0 0 0 4.2l1.8 1.8a3 3 0 0 0 4.2 0L22 8"></path>
                <path d="M15 15 3.3 3.3a4.2 4.2 0 0 0 0 6l7.3 7.3c.7.7 2 .7 2.8 0L15 15Zm0 0 7 7"></path>
                <path d="m2.1 21.8 6.4-6.3"></path>
                <path d="m19 5-7 7"></path>
            </svg>
            <span class="uppercase tracking-tighter text-[8px] font-black">COMMANDER</span>
        </button>

        <button class="flex flex-col items-center gap-1 text-zinc-400">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-history" aria-hidden="true">
                <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path>
                <path d="M3 3v5h5"></path>
                <path d="M12 7v5l4 2"></path>
            </svg>
            <span class="uppercase tracking-tighter text-[8px] font-black">HISTORIQUE</span>
        </button>

        <button class="flex flex-col items-center gap-1 text-zinc-400">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user" aria-hidden="true">
                <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                <circle cx="12" cy="7" r="4"></circle>
            </svg>
            <span class="uppercase tracking-tighter text-[8px] font-black">PROFIL</span>
        </button>
    </nav>

</body>
</html>
