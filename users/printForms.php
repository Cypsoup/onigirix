<?php
function printLoginForm()
{
    $action = "actions/processLogin.php";
    echo <<<HTML
        <div class="max-w-md mx-auto">
            <form action="{$action}" method="post" class="bg-white border-4 border-black p-8 shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] space-y-6">
                <h2 class="text-2xl font-black uppercase italic mb-6">Connexion Admin</h2>
                
                <div>
                    <label class="block font-black uppercase text-xs mb-1">Nom d'utilisateur</label>
                    <input type="text" name="login" placeholder="Ex: ChefOnigiri" required 
                        class="w-full border-2 border-black p-3 font-bold focus:bg-yellow-50 outline-none transition-colors">
                </div>

                <div>
                    <label class="block font-black uppercase text-xs mb-1">Mot de passe</label>
                    <input type="password" name="mdp" placeholder="••••••••" required 
                        class="w-full border-2 border-black p-3 font-bold focus:bg-yellow-50 outline-none transition-colors">
                </div>

                <button type="submit" class="w-full bg-black text-white font-black py-4 uppercase hover:bg-yellow-400 hover:text-black transition-all shadow-[4px_4px_0px_0px_rgba(0,0,0,0.3)] active:translate-x-1 active:translate-y-1 active:shadow-none flex items-center justify-center gap-2">
                    <i data-lucide="log-in" class="w-5 h-5"></i>
                    Valider l'accès
                </button>
            </form>
        </div>
    HTML;
}

function printLogoutForm()
{
    $action = "actions/processLogout.php";
    echo <<<HTML
        <form action="{$action}" method="post" class="mt-auto">
            <button type="submit" class="w-full flex items-center justify-center gap-2 bg-red-500 text-white border-2 border-white p-3 font-black uppercase text-xs tracking-widest hover:bg-black transition-colors shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] active:shadow-none active:translate-x-1 active:translate-y-1">
                <i data-lucide="log-out" class="w-4 h-4"></i>
            </button>
        </form>
    HTML;
}
?>