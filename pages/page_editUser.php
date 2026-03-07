<?php

require_once 'users/users.php';
require_once 'users/usersRenderer.php';
require_once 'users/printForms.php';
require_once 'utils/flash.php';

$userId = $_SESSION['userId'];
$user = User::getUserById($pdo, $userId);

if (!$user) {
    Flash::error("Impossible de modifier l'utilisateur");
    header('Location: index.php?page=userProfile');
    exit;
}

$todo = $_GET['todo'] ?? null;

if ($todo == "editInfo") {
    printEditUserForm($user);
} else if ($todo == "editPassword") {
    printEditPasswordForm($user);
} else {
    Flash::error("Impossible de modifier l'utilisateur");
    header('Location: index.php?page=userProfile');
    exit;
}

?>