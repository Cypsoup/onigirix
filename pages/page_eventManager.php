<?php

require_once 'events/event.php';
require_once 'events/eventRenderer.php';

// --- LOGIQUE DE TRAITEMENT DES ACTIONS ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    $id = $_POST['eventId'] ?? null;
    $name = $_POST['eventName'] ?? null;

    switch ($action) {
        case 'create':
            if ($name)
                Event::createNewEvent($pdo, $name);
            break;
        case 'toggleEvent':
            $event = Event::getEventById($pdo, $id);
            $event->isOpen ? Event::closeEvent($pdo) : Event::openEvent($pdo, $id);
            break;
        case 'toggleOrder':
            $event = Event::getEventById($pdo, $id);
            $event->canOrder ? Event::closeOrder($pdo) : Event::openOrder($pdo);
            break;
        case 'editName':
            if ($id && $name)
                Event::editEventName($pdo, $id, $name);
            break;
    }
    // Redirection pour éviter de renvoyer le formulaire au refresh
    header("Location: ?page=eventManager");
    exit;
}

// Récupération des données
$activeEvent = Event::getOpenEvent($pdo);
$allEvents = Event::getAllEvents($pdo);
?>

<div class="flex flex-col w-full h-full overflow-y-auto bg-zinc-50">

    <header class="p-8 border-b-2 border-black bg-white">
        <div class="max-w-5xl mx-auto">
            <h1 class="text-4xl font-black italic uppercase tracking-tighter">Gestion des Services</h1>
            <p class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest mt-1">Création et historiques</p>
        </div>
    </header>

    <main class="p-8">
        <div class="max-w-5xl mx-auto">

            <section class="mb-12">
                <h3 class="text-xs font-black uppercase mb-4 tracking-tighter text-zinc-400">Nouvel Événement</h3>
                <form method="POST" class="flex gap-4">
                    <input type="text" name="eventName" placeholder="Nom (ex: Vente de Printemps)" required
                        class="flex-1 border-2 border-black p-4 font-bold shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] focus:outline-none">
                    <button name="action" value="create"
                        class="bg-[#22C55E] border-2 border-black px-8 font-black uppercase shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] active:translate-y-1 transition-all">
                        Créer
                    </button>
                </form>
            </section>

            <section class="mb-12">
                <?php if ($activeEvent): ?>
                    <?php EventRenderer::renderEventManagerCard($activeEvent); ?>
                <?php else: ?>
                    <div
                        class="border-2 border-black border-dashed p-10 text-center bg-white shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">
                        <p class="text-zinc-400 font-bold uppercase italic">Aucun événement ouvert actuellement.</p>
                    </div>
                <?php endif; ?>
            </section>

            <section>
                <h3 class="text-xs font-black uppercase mb-4 tracking-tighter text-zinc-400">Historique et Édition</h3>
                <div class="bg-white border-2 border-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] overflow-hidden">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-black text-white text-[10px] uppercase">
                            <tr>
                                <th class="p-4">Nom</th>
                                <th class="p-4">Statut</th>
                                <th class="p-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y-2 divide-black">
                            <?php foreach ($allEvents as $event): ?>
                                <tr class="hover:bg-zinc-50">
                                    <td class="p-4">
                                        <form method="POST" class="flex items-center gap-2 group">
                                            <input type="hidden" name="eventId" value="<?= $event->id ?>">
                                            <input type="text" name="eventName" value="<?= $event->name ?>"
                                                class="bg-transparent font-bold border-b border-transparent focus:border-black focus:outline-none px-1">
                                            <button name="action" value="editName"
                                                class="text-[8px] underline opacity-0 group-hover:opacity-100 uppercase font-black transition-opacity">Renommer</button>
                                        </form>
                                    </td>
                                    <td class="p-4">
                                        <?= EventRenderer::renderStatusBadge($event) ?>
                                    </td>
                                    <td class="p-4 text-right flex items-center justify-end gap-4">
                                        <?php if (!$event->isOpen): ?>
                                            <a href="actions/exportOrdersCsv.php?eventExportedId=<?= $event->id ?>"
                                                class="bg-blue-500 text-white text-[10px] font-black px-3 py-1 border-2 border-black hover:bg-white hover:text-black transition-all uppercase shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] active:translate-y-0.5"
                                                title="Exporter les commandes en CSV">
                                                CSV
                                            </a>
                                        <?php endif; ?>

                                        <?php if (!$event->isOpen && !$activeEvent): ?>
                                            <form method="POST">
                                                <input type="hidden" name="eventId" value="<?= $event->id ?>">
                                                <button name="action" value="toggleEvent"
                                                    class="bg-black text-white text-[10px] font-black px-3 py-1 border-2 border-black hover:bg-white hover:text-black transition-all uppercase shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] active:translate-y-0.5">
                                                    Ouvrir
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </main>
</div>