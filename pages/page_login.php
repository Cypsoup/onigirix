<?php

require_once 'users/printForms.php';

// Affichage de formulaires
if (!isset($_SESSION["loggedIn"]) || $_SESSION["loggedIn"] == false) {
    printLoginForm();
}

?>