<?php
// logout.php
session_start(); // On récupère la session active
session_unset(); // On vide les variables
session_destroy(); // On détruit la session
header('Location: login.php'); // On renvoie vers le login
exit();
?>