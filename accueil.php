<?php
session_start();

// CORRECTION : On vérifie user_id (car on ne stocke plus le mdp en session)
if(!isset($_SESSION['user_id'])){
    header('Location: login.php');
    exit();
}
?>
<?php include 'sidebar.php'; ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil</title>
    <link rel="stylesheet" href="./css/sidebar.css">
    <link rel="stylesheet" href="./css/pages.css">
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <div class="home_content">
        <h1>Bienvenue, <?php echo htmlspecialchars($_SESSION['nom']); ?> !</h1>
        <p>Vous êtes connecté en tant que : <strong><?php echo $_SESSION['role']; ?></strong></p>
    </div>
</body>
</html>