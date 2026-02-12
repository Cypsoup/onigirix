<?php
// Importation des fichiers
require_once 'config/db.php';
require_once 'includes/functions.php';
require_once 'utils/pageGeneration.php';


$activePage = 'index';
$user_access = 0;
$user_connected = 0;

// HTML Header
generateHTMLHeader(getPageTitle($activePage));

// Sidebar
generateSidebar($activePage, $user_access, $user_connected)

    ?>



<?php

# HTML Footer
generateHTMLFooter();

?>