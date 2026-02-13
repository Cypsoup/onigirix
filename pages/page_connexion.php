<?php
$askedPage = 'connexion';
// Affichage de formulaires
if ($_SESSION["loggedIn"]) {
    printLogoutForm($askedPage);
} else {
    printLoginForm($askedPage);
}

?>