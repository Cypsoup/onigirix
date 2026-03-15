<?php

class UserRenderer
{

    public static function displayUser($pdo, $id)
    {
        $user = User::getUserById($pdo, $id);
        if (!$user) {
            Flash::error("Aucun utilisateur à afficher !");
            return null;
        }
        echo <<<HTML
        <header class="flex items-center justify-between p-6 border-b-2 border-black">
            <div class="flex flex-col gap-1">
                <h3 class="text-[10px] text-black/50">VOS INFORMATIONS</h3>
                <h1 class="text-xl font-black italic text-black uppercase">KON'NICHIWA, {$user->firstname} !
                </h1>
            </div>
            <div class="w-16 h-12 bg-white flex items-center justify-center">
                <img src="images/logo.jpg" alt="Logo" class="object-cover w-full h-full">
            </div>
        </header>
        <div class="p-8 pb-24 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block font-black uppercase text-xs mb-1">Prénom</label>
                    <div class="w-full border-2 border-black p-3 font-bold focus:bg-yellow-50 outline-none">
                        {$user->firstname}
                    </div>
                </div>

                <div class="md:col-span-2">
                    <label class="block font-black uppercase text-xs mb-1">Nom</label>
                    <div class="w-full border-2 border-black p-3 font-bold focus:bg-yellow-50 outline-none">
                        {$user->name}
                    </div>
                </div>

                <div>
                    <label class="block font-black uppercase text-xs mb-1">Trigramme (Identifiant)</label>
                    <div class="w-full border-2 border-black p-3 font-black tracking-widest focus:bg-yellow-50 outline-none uppercase text-center">
                        {$user->trigramme}
                    </div>
                </div>

                <div>
                    <label class="block font-black uppercase text-xs mb-1">Adresse Email</label>
                    <div class="w-full border-2 border-black p-3 font-bold focus:bg-yellow-50 outline-none">
                        {$user->email}
                    </div>
                </div>
            </div>

            <a href="?page=editUser&todo=editInfo" class="w-full bg-black text-white border-2 border-black font-black p-4 uppercase tracking-widest hover:bg-yellow-400 hover:text-black transition-all hover:shadow-[6px_6px_0px_0px_rgba(0,0,0,1)] active:translate-x-1 active:translate-y-1 active:shadow-none flex items-center justify-center gap-3 text-lg group">
                <i data-lucide="pencil" class="w-6 h-6 transition-transform group-hover:rotate-12"></i>
                Modifier ces propriétés
            </a>

            <a href="?page=editUser&todo=editPassword" class="w-full bg-black text-white border-2 border-black font-black p-4 uppercase tracking-widest hover:bg-yellow-400 hover:text-black transition-all hover:shadow-[6px_6px_0px_0px_rgba(0,0,0,1)] active:translate-x-1 active:translate-y-1 active:shadow-none flex items-center justify-center gap-3 text-lg group">
                <i data-lucide="lock" class="w-6 h-6 transition-transform group-hover:rotate-12"></i>
                Changer de mot de passe
            </a>
        </div>
        HTML;
    }

}

?>