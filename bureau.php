<?php
session_start();
require 'db.php';

// Vérif connexion standard
if(!isset($_SESSION['user_id'])){
    header('Location: login.php');
    exit();
}

$estAdmin = (isset($_SESSION['role']) && $_SESSION['role'] === 'admin');

// Récupération des membres
$membres = $mysqli->query("SELECT * FROM bureau ORDER BY id ASC");
?>

<?php include 'sidebar.php'; ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Le Bureau</title>
    <link rel="stylesheet" href="./css/sidebar.css">
    <link rel="stylesheet" href="./css/pages.css">
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <div class="main-content">
        <h1 style="text-align:center; margin-bottom:30px;">Les membres du bureau</h1>

        <?php if($estAdmin): ?>
            <div style="text-align:center; margin-bottom: 20px;">
                <a href="gestion_bureau.php" class="btn-action btn-add">
                    <i class='bx bx-cog'></i> Gérer les membres
                </a>
            </div>
        <?php endif; ?>

        <div class="team-grid">
            <?php if($membres->num_rows > 0): ?>
                <?php while($m = $membres->fetch_assoc()): ?>
                    <div class="team-card">
                        <?php 
                            // On regarde si l'image existe et si ce n'est pas le placeholder "default_user.png"
                            if (!empty($m['image']) && $m['image'] !== 'default_user.png') {
                                $img = "uploads/" . $m['image'];
                            } else {
                                // Sinon on affiche le logo (Attention c'est bien .png d'après ta sidebar)
                                $img = "./logo/aepm.jpg"; 
                            }
                        ?>
                        <img src="<?= htmlspecialchars($img); ?>" alt="<?= htmlspecialchars($m['nom']); ?>">
                        
                        <h3><?= htmlspecialchars($m['nom']); ?></h3>
                        <div class="role"><?= htmlspecialchars($m['role']); ?></div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>Aucun membre affiché pour le moment.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>