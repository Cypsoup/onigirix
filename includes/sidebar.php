<aside class="w-[60px] bg-black flex flex-col items-center py-6 justify-between z-20">
    <div class="flex flex-col items-center gap-8">
        <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center overflow-hidden">
            <img src="images/logo.jpg" alt="Logo" class="object-cover w-full h-full">
        </div>
        <nav class="flex flex-col gap-6">
            <?php 
                $pages = [
                    'dashboard' => 'layout-dashboard',
                    'inventory' => 'package',
                    'history' => 'history'
                ];
                foreach ($pages as $slug => $icon): 
                    $active = ($activePage == $slug) ? 'text-[#E60012] bg-white/10' : 'text-white/50';
            ?>
                <a href="<?= $slug ?>.php" class="<?= $active ?> p-2 rounded-lg transition-colors hover:text-white">
                    <i data-lucide="<?= $icon ?>"></i>
                </a>
            <?php endforeach; ?>
        </nav>
    </div>
    <a href="logout.php" class="text-white/50 hover:text-[#E60012] transition-colors"><i data-lucide="log-out"></i></a>
</aside>