<?php
function printLoginForm()
{
    $action = "actions/processLogin.php";
    echo <<<HTML
        <div class="max-w-md mx-auto">
            <form action="{$action}" method="post" class="bg-white border-4 border-black p-8 shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] space-y-6">
                <h2 class="text-2xl font-black uppercase italic mb-6">Connexion</h2>
                
                <div>
                    <label class="block font-black uppercase text-xs mb-1">Trigramme</label>
                    <input type="text" name="trigramme" placeholder="Ex: ENO" required 
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

function printCreateUserForm()
{
    $action = "actions/processCreateUser.php";
    echo <<<HTML
    <div class="max-w-2xl mx-auto my-10">
        <form id="createUserForm" action="{$action}" method="post" class="bg-white border-4 border-black p-8 shadow-[12px_12px_0px_0px_rgba(0,0,0,1)] space-y-6">
            <div class="border-b-4 border-black pb-4 mb-6">
                <h2 class="text-3xl font-black uppercase italic">Inscrivez-vous</h2>
                <p class="text-xs font-bold text-gray-500 uppercase">Le trigramme sera votre identifiant de connexion.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block font-black uppercase text-xs mb-1">Nom Complet</label>
                    <input type="text" name="nom" placeholder="ex: Jean Onigiri" required 
                        class="w-full border-2 border-black p-3 font-bold focus:bg-yellow-50 outline-none">
                </div>

                <div>
                    <label class="block font-black uppercase text-xs mb-1">Trigramme (Identifiant)</label>
                    <input type="text" name="trigramme" maxlength="3" minlength="3" placeholder="ABC" required 
                        class="w-full border-2 border-black p-3 font-black text-2xl tracking-widest focus:bg-yellow-50 outline-none uppercase text-center">
                    <p class="text-[10px] mt-1 font-bold italic">3 lettres exactement.</p>
                </div>

                <div>
                    <label class="block font-black uppercase text-xs mb-1">Adresse Email</label>
                    <input type="email" name="email" placeholder="chef@onigirix.fr" required 
                        class="w-full border-2 border-black p-3 font-bold focus:bg-yellow-50 outline-none">
                </div>

                <div>
                    <label class="block font-black uppercase text-xs mb-1">Mot de passe</label>
                    <input type="password" name="password" id="password" placeholder="••••••••" required 
                        class="w-full border-2 border-black p-3 font-bold focus:bg-yellow-50 outline-none">
                </div>

                <div>
                    <label class="block font-black uppercase text-xs mb-1">Validez le mot de passe</label>
                    <input type="password" name="passwordConfirm" id="passwordConfirm" placeholder="••••••••" required 
                        class="w-full border-2 border-black p-3 font-bold focus:bg-yellow-50 outline-none">
                </div>
            </div>

            <input type="hidden" name="role" value="user">

            <button type="submit" class="w-full bg-black text-white font-black py-5 uppercase hover:bg-green-500 hover:text-black transition-all shadow-[6px_6px_0px_0px_rgba(0,0,0,0.3)] active:translate-x-1 active:translate-y-1 active:shadow-none flex items-center justify-center gap-3 text-lg">
                <i data-lucide="user-plus" class="w-6 h-6"></i>
                Valider l'inscription
            </button>
        </form>
    </div>
    HTML;
}

function printCreateUserBtn()
{
    echo <<<HTML
    <div class="mt-8 text-center">
        <a href="index.php?page=createUser" 
        class="inline-flex items-center gap-3 bg-white text-black border-4 border-black px-6 py-4 font-black uppercase italic tracking-widest hover:bg-yellow-400 transition-all shadow-[6px_6px_0px_0px_rgba(0,0,0,1)] active:shadow-none active:translate-x-1 active:translate-y-1">
            <i data-lucide="user-plus" class="w-6 h-6"></i>
            Nouveau ? Créer un compte
        </a>
    </div>
    HTML;
}

?>