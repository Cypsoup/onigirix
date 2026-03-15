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
        <div class="max-w-2xl mx-auto my-10">
            <div class="bg-white border-4 border-black p-8 shadow-[12px_12px_0px_0px_rgba(0,0,0,1)] space-y-6">
                <div class="border-b-4 border-black pb-4 mb-6">
                    <h2 class="text-3xl font-black uppercase italic">Cher {$user->firstname}</h2>
                    <p class="text-xs font-bold text-gray-500 uppercase">Vos informations </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block font-black uppercase text-xs mb-1">Nom Complet</label>
                        <div class="w-full border-2 border-black p-3 font-bold focus:bg-yellow-50 outline-none">
                            {$user->name} {$user->firstname}
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

                <a href="?page=editUser&todo=editInfo"
                    class="w-full bg-black text-white border-2 border-black font-black py-4 uppercase tracking-widest hover:bg-yellow-400 hover:text-black transition-all shadow-[6px_6px_0px_0px_rgba(0,0,0,1)] active:translate-x-1 active:translate-y-1 active:shadow-none flex items-center justify-center gap-3 text-lg group">
                    <i data-lucide="pencil" class="w-6 h-6 transition-transform group-hover:rotate-12"></i>
                    Modifier ces propriétés
                </a>

                <a href="?page=editUser&todo=editPassword"
                    class="w-full bg-black text-white border-2 border-black font-black py-4 uppercase tracking-widest hover:bg-yellow-400 hover:text-black transition-all shadow-[6px_6px_0px_0px_rgba(0,0,0,1)] active:translate-x-1 active:translate-y-1 active:shadow-none flex items-center justify-center gap-3 text-lg group">
                    <i data-lucide="lock" class="w-6 h-6 transition-transform group-hover:rotate-12"></i>
                    Changer de mot de passe
                </a>
            </div>
        </div>
        HTML;
    }

}

?>