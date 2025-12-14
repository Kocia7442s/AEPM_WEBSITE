<?php
session_start();
require 'db.php';

// 1. Vérification : Est-ce qu'on est connecté et Admin ?
if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin'){
    // Si pas admin, on vire vers l'accueil
    header('Location: accueil.php');
    exit();
}

$msg = "";

// 2. Traitement du formulaire
if(isset($_POST['envoyer'])){
    
    // Vérification des champs texte
    if(!empty($_POST['titre']) && !empty($_POST['description'])){
        
        $titre = htmlspecialchars($_POST['titre']);
        $description = htmlspecialchars($_POST['description']);
        $nomImage = null; // Par défaut, pas d'image

        // 3. Gestion de l'upload de l'image (Tout en une seule fois)
        if(isset($_FILES['image']) && $_FILES['image']['error'] === 0){
            
            // On autorise max 5Mo
            if($_FILES['image']['size'] <= 5000000){
                
                $infosfichier = pathinfo($_FILES['image']['name']);
                $extension_upload = $infosfichier['extension'];
                $extensions_autorisees = array('jpg', 'jpeg', 'gif', 'png', 'webp');

                if(in_array(strtolower($extension_upload), $extensions_autorisees)){
                    
                    // On renomme l'image avec le temps actuel pour éviter les doublons (ex: 1678943_monimage.jpg)
                    $nomImage = time() . '_' . basename($_FILES['image']['name']);
                    
                    // On déplace l'image dans le dossier "uploads"
                    move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/' . $nomImage);
                    
                } else {
                    $msg = "Extension d'image non autorisée (jpg, jpeg, png, gif, webp).";
                }
            } else {
                $msg = "L'image est trop lourde (Max 5Mo).";
            }
        }

        // 4. Insertion en base de données (Si pas d'erreur d'image)
        if(empty($msg)){
            $stmt = $mysqli->prepare("INSERT INTO articles(titre, description, image) VALUES(?, ?, ?)");
            $stmt->bind_param("sss", $titre, $description, $nomImage);
            
            if($stmt->execute()){
                $msg = "Article publié avec succès !";
                // Optionnel : Redirection vers la liste des articles
                // header('Location: articles.php');
            } else {
                $msg = "Erreur lors de l'enregistrement en base de données.";
            }
        }

    } else {
        $msg = "Veuillez remplir le titre et la description.";
    }
}
?>
<?php include 'sidebar.php'; ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Publier un article</title>
    <link rel="stylesheet" href="./css/sidebar.css">
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="./css/pages.css">
</head>
<body>
    <div class="main-content">
        <h1 style="text-align:center; margin-bottom:20px;">Nouvel Article</h1>

        <div class="form-box">
            <?php if(!empty($msg)): ?>
                <div class="msg <?php echo strpos($msg, 'succès') !== false ? 'success' : 'error'; ?>">
                    <?php echo $msg; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="" enctype="multipart/form-data">
                
                <div class="form-group">
                    <label for="titre">Titre de l'article</label>
                    <input type="text" name="titre" placeholder="Ex: Soirée Loto..." required>
                </div>

                <div class="form-group">
                    <label for="description">Contenu de l'article</label>
                    <textarea name="description" rows="8" placeholder="Racontez votre histoire..." required></textarea>
                </div>

                <div class="form-group">
                    <label for="image">Image (Optionnel)</label>
                    <input type="file" name="image" accept="image/*">
                </div>

                <button type="submit" name="envoyer" class="btn-submit">Publier l'article</button>
            
            </form>
        </div>
    </div>
</body>
</html>