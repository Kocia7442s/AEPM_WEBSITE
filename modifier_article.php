<?php
session_start();
require 'db.php';

// 1. SÉCURITÉ ADMIN
if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin'){
    header('Location: accueil.php');
    exit();
}

$msg = "";

// 2. VÉRIFIER L'ID ET RÉCUPÉRER L'ARTICLE ACTUEL
if(isset($_GET['id']) && !empty($_GET['id'])){
    $id = (int)$_GET['id'];

    $stmt = $mysqli->prepare("SELECT * FROM articles WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0){
        $article = $result->fetch_assoc();
        $ancienNomImage = $article['image']; // On garde ça en mémoire pour la suppression éventuelle
    } else {
        die("Article introuvable.");
    }
} else {
    die("ID manquant.");
}

// 3. TRAITEMENT DU FORMULAIRE DE MODIFICATION
if(isset($_POST['valider'])){
    
    if(!empty($_POST['titre']) && !empty($_POST['description'])){
        
        $titre = htmlspecialchars($_POST['titre']);
        $description = htmlspecialchars($_POST['description']);
        $nouvelleImage = $ancienNomImage; // Par défaut, on garde l'ancienne image
        $imageChangee = false;

        // A. Est-ce qu'une NOUVELLE image a été envoyée ?
        if(isset($_FILES['image']) && $_FILES['image']['error'] === 0){
            
            if($_FILES['image']['size'] <= 5000000){
                $infosfichier = pathinfo($_FILES['image']['name']);
                $extension_upload = $infosfichier['extension'];
                $extensions_autorisees = array('jpg', 'jpeg', 'gif', 'png', 'webp');

                if(in_array(strtolower($extension_upload), $extensions_autorisees)){
                    
                    // 1. On prépare le nom de la nouvelle image
                    $nouvelleImage = time() . '_' . basename($_FILES['image']['name']);
                    
                    // 2. On l'upload
                    move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/' . $nouvelleImage);
                    
                    // 3. On note qu'on a changé l'image
                    $imageChangee = true;

                } else {
                    $msg = "Extension non autorisée.";
                }
            } else {
                $msg = "Image trop lourde (Max 5Mo).";
            }
        }

        // B. Mise à jour en Base de Données
        if(empty($msg)){ // Si pas d'erreur d'upload
            
            $stmtUpdate = $mysqli->prepare("UPDATE articles SET titre = ?, description = ?, image = ? WHERE id = ?");
            $stmtUpdate->bind_param("sssi", $titre, $description, $nouvelleImage, $id);
            
            if($stmtUpdate->execute()){
                
                // C. NETTOYAGE : Si on a mis une nouvelle image et que l'ancienne existait, on supprime l'ancienne
                if($imageChangee && !empty($ancienNomImage)){
                    $cheminAncienne = 'uploads/' . $ancienNomImage;
                    if(file_exists($cheminAncienne)){
                        unlink($cheminAncienne);
                    }
                }

                $msg = "Article modifié avec succès !";
                // On met à jour la variable $article['image'] pour que l'affichage en bas soit à jour direct
                $article['image'] = $nouvelleImage;
                
                // Optionnel : Redirection
                // header('Location: articles.php'); 

            } else {
                $msg = "Erreur lors de la mise à jour.";
            }
        }

    } else {
        $msg = "Veuillez remplir le titre et la description.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier l'article</title>
    <link rel="stylesheet" href="./css/sidebar.css">
    <link rel="stylesheet" href="../css/modifier_article.css">
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
    <style>
        /* Petit correctif CSS pour centrer et rendre joli */
        .main-content { margin-left: 80px; padding: 20px; display: flex; justify-content: center;}
        .formulaire { background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); width: 100%; max-width: 600px;}
        .current-img { max-width: 150px; border-radius: 5px; margin-top: 10px; display: block;}
        .msg { padding: 10px; margin-bottom: 10px; background: #d4edda; color: #155724; border-radius: 5px; text-align: center; }
        .error { background: #f8d7da; color: #721c24; }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="logo_content">
            <div class="logo">
                <img class='logo_aepm' src="../logo/aepm.png" style="position:relative; height: 30px; width: 30px;" alt="Logo">
                <div class="logo_name">AEPM WEB SITE</div>
            </div>
            <i class='bx bx-menu' id="btn"></i>
        </div>
        <ul class="nav_list">
            <li>
                <i class='bx bx-search' ></i>
                <input type="text" placeholder="Search...">
                <span class="tooltip">Search</span>
            </li>
            <li>
                <a href="accueil.php">
                    <i class='bx bx-home' ></i>
                    <span class="links_name">Accueil</span>
                </a>
                <span class="tooltip">Accueil</span>
            </li>
            <li>
                <a href="articles.php">
                    <i class='bx bx-book' ></i>
                    <span class="links_name">Articles</span>
                </a>
                <span class="tooltip">Articles</span>
            </li>
            <li>
                <a href="publier_article.php">
                    <i class='bx bx-plus-circle'></i>
                    <span class="links_name">Publier article</span>
                </a>
                <span class="tooltip">Publier article</span>
            </li>
            <li>
                <a href="calendrier.php">
                    <i class='bx bx-calendar' ></i>
                    <span class="links_name">Calendrier</span>
                </a>
                <span class="tooltip">Calendrier</span>
            </li>
            <li>
                <a href="contact.php">
                    <i class='bx bx-message-detail'></i>
                    <span class="links_name">Contact</span>
                </a>
                <span class="tooltip">Contact</span>
            </li>
        </ul>
        <div class="profile_content">
            <div class="profile">
                <div class="profile_details">
                    <div class="name_job">
                        <div class="name"><?php echo htmlspecialchars($_SESSION['nom']); ?></div>
                        <div class="job"><?php echo htmlspecialchars($_SESSION['role']); ?></div>
                    </div>
                </div>
                <a href="logout.php" style="color: white;"><i class='bx bx-log-out' id="log_out"></i></a>
            </div>
        </div>
    </div>

    <div class="main-content">
        <form class="formulaire" method="POST" action="" enctype="multipart/form-data">
            
            <h2 style="text-align:center; margin-bottom: 20px;">Modifier l'article</h2>
            
            <?php if(!empty($msg)): ?>
                <div class="msg <?php echo (strpos($msg, 'Erreur') !== false) ? 'error' : ''; ?>">
                    <?= $msg; ?>
                </div>
            <?php endif; ?>

            <box class="box">
                
                <div class="titre">
                    <label for="titre">Titre de l'article</label>
                    <input class="input_titre" type="text" name="titre" value="<?= htmlspecialchars($article['titre']); ?>" autocomplete="off" style="width: 100%;">
                    <br><br>
                </div>
                
                <div class="description">
                    <label for="description">Description</label>
                    <textarea name="description" cols="40" rows="10" style="width: 100%;"><?= htmlspecialchars($article['description']); ?></textarea>
                    <br><br>
                </div>
                
                <div class="file">
                    <label for="image">Image de l'article</label>
                    
                    <?php if(!empty($article['image'])): ?>
                        <div style="margin-bottom: 10px;">
                            <small>Image actuelle :</small><br>
                            <img src="uploads/<?= htmlspecialchars($article['image']); ?>" class="current-img">
                        </div>
                    <?php endif; ?>

                    <input class="input_file" type="file" id="image" name="image">
                    <small style="color:gray;">Laisser vide pour conserver l'image actuelle.</small>
                    <br><br>
                </div>
                
                <div class="button">
                    <input class="input_button" name="valider" type="submit" value="Mettre à jour" style="cursor: pointer; background: #33A7FF; color: white; border: none; padding: 10px 20px; border-radius: 5px;">
                </div>
                
            </box>
        </form>
    </div>

    <script>
        let btn = document.querySelector("#btn");
        let sidebar = document.querySelector(".sidebar");
        btn.addEventListener('click', function(){
            sidebar.classList.toggle('active');
        });
    </script>
</body>
</html>