<?php
$motDePasse = "admin";
echo "Ton mot de passe hashé est : <br>";
echo password_hash($motDePasse, PASSWORD_DEFAULT);
?>