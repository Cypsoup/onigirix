<?php

session_name("SessionOnigirix");
session_start();
require_once '../utils/flash.php';

$_SESSION = array();
session_destroy();

session_name("SessionOnigirix");
session_start();
Flash::success("Vous avez été déconnecté. À bientôt !");

header("Location: ../index.php?page=home");
exit;

?>