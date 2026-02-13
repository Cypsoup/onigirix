<?php
require('users/users.php');

function logIn($dbh)
{
    $user = User::getUtilisateur($dbh, $_POST['login']);
    if ($user != null && User::testMdp($user, $_POST['mdp'])) {
        $_SESSION['loggedIn'] = true;
    } else
        $_SESSION['loggedIn'] = false;
}

function logOut()
{
    unset($_SESSION['loggedIn']);
    session_destroy();
}

?>