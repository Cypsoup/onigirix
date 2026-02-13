<?php

$askedPage = "connexion";
// Affichage de formulaires
if (!isset($_SESSION["loggedIn"]) || $_SESSION["loggedIn"] == false) {
    printLoginForm($askedPage);
}

?>