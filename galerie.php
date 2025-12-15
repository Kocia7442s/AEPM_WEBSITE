<?php
session_start();
require 'db.php';

// On regarde si on est Admin pour afficher le bouton "Gérer"
$estAdmin = (isset($_SESSION['role']) && $_SESSION['role'] === 'admin');
$album_id = isset($_GET['id']) ? (int)$_GET['id'] : null;
?>

<?php include 'sidebar.php'; ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Galerie Photos</title>
    <link rel="stylesheet" href="./css/sidebar.css">
    <link rel="stylesheet" href="./css/pages.css">
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <div class="main-content">
        
        <?php if($estAdmin): ?>
            <div style="text-align:center; margin-bottom:20px;">
                <a href="gestion_galerie.php" class="btn-action btn-add"><i class='bx bx-camera'></i> Gérer la galerie</a>
            </div>
        <?php endif; ?>

        <?php if(!$album_id): ?>
            <h1 style="text-align:center;">Nos Albums Photos</h1>
            <p style="text-align:center; color:#666;">Retrouvez les souvenirs de nos événements passés.</p>
            
            <div class="album-grid">
                <?php 
                $albums = $mysqli->query("SELECT * FROM albums ORDER BY date_creation DESC");
                if($albums->num_rows > 0):
                    while($a = $albums->fetch_assoc()): 
                ?>
                    <a href="?id=<?= $a['id']; ?>" class="album-card">
                        <i class='bx bx-folder-open'></i>
                        <h3><?= htmlspecialchars($a['titre']); ?></h3>
                        <span style="font-size:12px; color:#999;">
                            <?= date("d/m/Y", strtotime($a['date_creation'])); ?>
                        </span>
                    </a>
                <?php 
                    endwhile;
                else:
                    echo "<p style='text-align:center; width:100%;'>Aucun album pour le moment.</p>";
                endif;
                ?>
            </div>

        <?php else: ?>
            <?php 
                $req = $mysqli->query("SELECT * FROM albums WHERE id=$album_id");
                if($req->num_rows === 0) die("Album introuvable");
                $album = $req->fetch_assoc();
            ?>

            <a href="galerie.php" class="btn-action" style="background:#666; margin-bottom:15px;">&larr; Retour</a>
            <h1><?= htmlspecialchars($album['titre']); ?></h1>

            <div class="photo-grid">
                <?php 
                $photos = $mysqli->query("SELECT * FROM photos WHERE album_id=$album_id");
                if($photos->num_rows > 0):
                    while($p = $photos->fetch_assoc()): 
                ?>
                    <div class="photo-item">
                        <a href="uploads/<?= $p['nom_fichier']; ?>" target="_blank">
                            <img src="uploads/<?= $p['nom_fichier']; ?>" alt="Photo">
                        </a>
                    </div>
                <?php 
                    endwhile; 
                else:
                    echo "<p>Pas encore de photos dans cet album.</p>";
                endif;
                ?>
            </div>
        <?php endif; ?>

    </div>
</body>
</html>