<?php
// Fichier: db.php
$host = "localhost";
$user = "root";
$pass = ""; // À changer en production !
$db = "aepm";

$mysqli = new mysqli($host, $user, $pass, $db);

if ($mysqli->connect_error) {
    die("Erreur de connexion : " . $mysqli->connect_error);
}
// Force l'encodage en UTF8
$mysqli->set_charset("utf8");
?>