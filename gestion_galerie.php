<?php
session_start();
require 'db.php';

// SÉCURITÉ ADMIN
if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin'){
    header('Location: galerie.php');
    exit();
}

$msg = "";
$album_id = isset($_GET['album_id']) ? (int)$_GET['album_id'] : null;

// 1. CRÉATION D'ALBUM
if(isset($_POST['creer_album'])){
    if(!empty($_POST['titre'])){
        $titre = htmlspecialchars($_POST['titre']);
        $stmt = $mysqli->prepare("INSERT INTO albums (titre) VALUES (?)");
        $stmt->bind_param("s", $titre);
        $stmt->execute();
        $msg = "Album créé !";
    }
}

// 2. SUPPRESSION ALBUM
if(isset($_GET['supprimer_album'])){
    $id = (int)$_GET['supprimer_album'];
    // On devrait aussi supprimer les fichiers physiques ici idéalement
    $mysqli->query("DELETE FROM albums WHERE id=$id");
    header('Location: gestion_galerie.php');
    exit();
}

// 3. AJOUT DE PHOTOS (Upload Multiple)
if(isset($_POST['upload_photos']) && $album_id){
    // On boucle sur les fichiers envoyés
    $total = count($_FILES['photos']['name']);
    
    for($i=0; $i<$total; $i++) {
        if($_FILES['photos']['error'][$i] === 0){
            $ext = pathinfo($_FILES['photos']['name'][$i], PATHINFO_EXTENSION);
            if(in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'webp'])){
                $nomImage = uniqid() . '.' . $ext;
                if(move_uploaded_file($_FILES['photos']['tmp_name'][$i], 'uploads/' . $nomImage)){
                    $stmt = $mysqli->prepare("INSERT INTO photos (album_id, nom_fichier) VALUES (?, ?)");
                    $stmt->bind_param("is", $album_id, $nomImage);
                    $stmt->execute();
                }
            }
        }
    }
    $msg = "Photos ajoutées !";
}

// 4. SUPPRESSION PHOTO UNIQUE
if(isset($_GET['supprimer_photo'])){
    $idPhoto = (int)$_GET['supprimer_photo'];
    // Récupérer le nom pour supprimer le fichier
    $req = $mysqli->query("SELECT nom_fichier FROM photos WHERE id=$idPhoto");
    $data = $req->fetch_assoc();
    if($data){
        unlink('uploads/'.$data['nom_fichier']); // Supprime fichier physique
        $mysqli->query("DELETE FROM photos WHERE id=$idPhoto"); // Supprime en BDD
    }
    // Redirection pour rester sur la page de l'album
    header("Location: gestion_galerie.php?album_id=$album_id");
    exit();
}

?>

<?php include 'sidebar.php'; ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion Galerie</title>
    <link rel="stylesheet" href="./css/sidebar.css">
    <link rel="stylesheet" href="./css/pages.css">
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <div class="main-content">
        
        <?php if(!$album_id): ?>
            <h1>Gestion des Albums</h1>
            
            <div class="form-box">
                <h3>Créer un nouvel album</h3>
                <?php if($msg) echo "<p class='success'>$msg</p>"; ?>
                <form method="POST">
                    <div class="form-group">
                        <input type="text" name="titre" placeholder="Titre (ex: Vide Grenier 2024)" required>
                    </div>
                    <button type="submit" name="creer_album" class="btn-submit">Créer l'album</button>
                </form>
            </div>

            <div class="album-grid">
                <?php 
                $albums = $mysqli->query("SELECT * FROM albums ORDER BY date_creation DESC");
                while($a = $albums->fetch_assoc()): 
                ?>
                    <div class="album-card">
                        <a href="?album_id=<?= $a['id']; ?>" style="text-decoration:none; color:inherit;">
                            <i class='bx bx-folder'></i>
                            <h3><?= htmlspecialchars($a['titre']); ?></h3>
                        </a>
                        <a href="?supprimer_album=<?= $a['id']; ?>" onclick="return confirm('Tout supprimer ?');" style="color:red; font-size:12px;">
                            <br>[Supprimer l'album]
                        </a>
                    </div>
                <?php endwhile; ?>
            </div>

        <?php else: ?>
            <?php 
                $reqAlbum = $mysqli->query("SELECT * FROM albums WHERE id=$album_id");
                $currentAlbum = $reqAlbum->fetch_assoc();
            ?>
            
            <a href="gestion_galerie.php" class="btn-action" style="background:#666;">&larr; Retour aux albums</a>
            <h1 style="margin-top:10px;">Album : <?= htmlspecialchars($currentAlbum['titre']); ?></h1>

            <div class="form-box">
                <h3>Ajouter des photos ici</h3>
                <?php if($msg) echo "<p class='success'>$msg</p>"; ?>
                <form method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <input type="file" name="photos[]" multiple accept="image/*" required>
                    </div>
                    <button type="submit" name="upload_photos" class="btn-submit">Envoyer les photos</button>
                </form>
            </div>

            <div class="photo-grid">
                <?php 
                $photos = $mysqli->query("SELECT * FROM photos WHERE album_id=$album_id");
                while($p = $photos->fetch_assoc()): 
                ?>
                    <div class="photo-item">
                        <img src="uploads/<?= $p['nom_fichier']; ?>">
                        <a href="?album_id=<?= $album_id; ?>&supprimer_photo=<?= $p['id']; ?>" class="btn-del-photo" onclick="return confirm('Supprimer photo ?');">
                            <i class='bx bx-x'></i>
                        </a>
                    </div>
                <?php endwhile; ?>
            </div>

        <?php endif; ?>

    </div>
</body>
</html>