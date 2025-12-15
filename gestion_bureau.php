<?php
session_start();
require 'db.php';

// 1. SÉCURITÉ ADMIN
if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin'){
    header('Location: bureau.php'); // Redirection vers la page publique si pas admin
    exit();
}

$msg = "";

// 2. AJOUT D'UN MEMBRE
if(isset($_POST['ajouter'])){
    if(!empty($_POST['nom']) && !empty($_POST['role'])){
        
        $nom = htmlspecialchars($_POST['nom']);
        $role = htmlspecialchars($_POST['role']);
        $nomImage = "default_user.png"; // Image par défaut si pas de photo

        // Upload Image
        if(isset($_FILES['photo']) && $_FILES['photo']['error'] === 0){
            if($_FILES['photo']['size'] <= 5000000){
                $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
                $autorises = ['jpg', 'jpeg', 'png', 'webp'];
                if(in_array(strtolower($ext), $autorises)){
                    $nomImage = uniqid() . '.' . $ext;
                    move_uploaded_file($_FILES['photo']['tmp_name'], 'uploads/' . $nomImage);
                }
            }
        }

        $stmt = $mysqli->prepare("INSERT INTO bureau (nom, role, image) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $nom, $role, $nomImage);
        $stmt->execute();
        $msg = "Membre ajouté !";
    }
}

// 3. SUPPRESSION D'UN MEMBRE
if(isset($_GET['supprimer'])){
    $id = (int)$_GET['supprimer'];
    // On supprime l'image avant
    $req = $mysqli->query("SELECT image FROM bureau WHERE id = $id");
    $data = $req->fetch_assoc();
    if($data && $data['image'] != "default_user.png" && file_exists('uploads/'.$data['image'])){
        unlink('uploads/'.$data['image']);
    }
    
    $mysqli->query("DELETE FROM bureau WHERE id = $id");
    header('Location: gestion_bureau.php');
    exit();
}

// 4. LISTE DES MEMBRES
$membres = $mysqli->query("SELECT * FROM bureau");
?>

<?php include 'sidebar.php'; ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gérer le Bureau</title>
    <link rel="stylesheet" href="./css/sidebar.css">
    <link rel="stylesheet" href="./css/pages.css">
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <div class="main-content">
        <h1>Gestion du Bureau</h1>
        
        <div class="form-box">
            <h3>Ajouter un membre</h3>
            <?php if($msg) echo "<p class='success'>$msg</p>"; ?>
            
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Nom Prénom</label>
                    <input type="text" name="nom" required>
                </div>
                <div class="form-group">
                    <label>Rôle (ex: Trésorier)</label>
                    <input type="text" name="role" required>
                </div>
                <div class="form-group">
                    <label>Photo</label>
                    <input type="file" name="photo">
                </div>
                <button type="submit" name="ajouter" class="btn-submit">Ajouter</button>
            </form>
        </div>

        <br><hr><br>

        <h3>Membres actuels</h3>
        <div class="team-grid">
            <?php while($m = $membres->fetch_assoc()): ?>
                <div class="team-card">
                    <img src="uploads/<?= htmlspecialchars($m['image'] ?: 'default_user.png'); ?>" alt="Photo">
                    <h3><?= htmlspecialchars($m['nom']); ?></h3>
                    <div class="role"><?= htmlspecialchars($m['role']); ?></div>
                    <a href="?supprimer=<?= $m['id']; ?>" class="btn-action btn-delete" onclick="return confirm('Supprimer ?');">Supprimer</a>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>
</html>