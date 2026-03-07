<?php

require_once 'users/users.php';
require_once 'users/usersRenderer.php';

$userId = $_SESSION['userId'];

UserRenderer::displayUser($pdo, $userId);

?>