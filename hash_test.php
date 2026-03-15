<?php
$motDePasse = "hash_user1"; 
echo "Ton mot de passe hashé est : <br>";
echo password_hash($motDePasse, PASSWORD_DEFAULT);
?>