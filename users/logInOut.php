<?php
require('users/users.php');

function logIn($dbh)
{
    $user = User::getUtilisateur($dbh, $_POST['login']);
    if ($user != null && User::testMdp($user, $_POST['mdp'])) {
        $_SESSION['loggedIn'] = true;
        $_SESSION['role'] = $user->role;
    } else
        $_SESSION['loggedIn'] = false;
}

function logOut()
{
    unset($_SESSION['loggedIn'], $_SESSION['role']);
    session_destroy();
}

?>