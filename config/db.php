<?php
$host = 'localhost';
$dbname = 'onigirix';
$user = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); # Pour afficher les erreurs
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); #
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}