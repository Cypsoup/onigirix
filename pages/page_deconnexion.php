<p>Déconnexion</p>
<?php

$askedPage = "deconnexion";
// Affichage de formulaires
if (isset($_SESSION["loggedIn"])) {
    printLogoutForm($askedPage);
}

?>