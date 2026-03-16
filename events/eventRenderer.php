<?php
class EventRenderer
{

    public static function renderStatusBadge($event)
    {
        if (!$event->isOpen)
            echo '<span class="bg-zinc-200 text-zinc-600 px-2 py-1 text-[10px] font-black border border-black uppercase">Fermé</span>';
        else if ($event->canOrder)
            echo '<span class="bg-green-400 text-black px-2 py-1 text-[10px] font-black border border-black uppercase">Commandes Ouvertes</span>';
        else
            echo '<span class="bg-orange-400 text-black px-2 py-1 text-[10px] font-black border border-black uppercase">Service en pause</span>';
    }

    public static function renderEventManagerCard($event)
    {
        $statusLabel = $event->isOpen ? 'OUVERT' : 'FERMÉ';
        $borderColor = $event->isOpen ? 'border-[#E60012]' : 'border-black';

        $btnToggleEventLabel = $event->isOpen ? 'Fermer l\'événement' : 'Ouvrir l\'événement';
        $btnToggleOrderLabel = $event->canOrder ? 'Bloquer les commandes' : 'Autoriser les commandes';

        echo <<<HTML
        <div class="bg-white border-2 {$borderColor} p-6 shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] mb-8">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <p class="text-[10px] font-black text-zinc-400 uppercase tracking-widest">Événement Actuel</p>
                    <h2 class="text-3xl font-black italic uppercase">{$event->name}</h2>
                </div>
                <div>
                    {$statusLabel}
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="flex flex-col gap-2">
                    <p class="text-[10px] font-bold uppercase">État de l'événement</p>
                    <form method="POST" class="flex gap-2">
                        <input type="hidden" name="eventId" value="{$event->id}">
                        <button name="action" value="toggleEvent" class="flex-1 bg-black text-white text-xs font-bold py-3 border-2 border-black hover:bg-white hover:text-black transition-colors uppercase">
                            {$btnToggleEventLabel}
                        </button>
                    </form>
                </div>

                <div class="flex flex-col gap-2">
                    <p class="text-[10px] font-bold uppercase">Prise de commandes</p>
                    <form method="POST" class="flex gap-2">
                        <input type="hidden" name="eventId" value="{$event->id}">
                        <button name="action" value="toggleOrder" class="flex-1 bg-[#E60012] text-white text-xs font-bold py-3 border-2 border-black shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] active:translate-y-0.5 transition-all uppercase">
                            {$btnToggleOrderLabel}
                        </button>
                    </form>
                </div>
            </div>
        </div>
HTML;
    }
}