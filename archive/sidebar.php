<?php include 'utils/utils.php'; ?>

<aside class="w-[60px] bg-black flex flex-col items-center py-6 justify-between z-20">
    <div class="flex flex-col items-center gap-8">
        <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center overflow-hidden">
            <img src="images/logo.jpg" alt="Logo" class="object-cover w-full h-full">
        </div>
        <nav class="flex flex-col gap-6">
            <?php
            foreach ($page_list as $page) {
                $active = ($activePage == $page["name"]) ? 'text-[#E60012] bg-white/10' : 'text-white/50';
                if ($page["access"] <= $user_access && $page["connected"] <= $user_connected) {
                    echo <<<end
                    <a href="{$page["name"]}.php" class="{$active} p-2 rounded-lg transition-colors hover:text-white">
                        <i data-lucide="{$page["icon"]}"></i>
                    </a>
                    end;
                }
            }
            ?>
        </nav>
    </div>
    <?php
    if ($user_connected) {
        echo '<a href="logout.php" class="text-white/50 hover:text-[#E60012] transition-colors"><i data-lucide="log-out"></i></a>';
    } else {
        echo '<a href="login.php" class="text-white/50 hover:text-[#E60012] transition-colors"><i data-lucide="log-in"></i></a>';
    }
    ?>
</aside>