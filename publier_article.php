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

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Publier un article</title>
    <link rel="stylesheet" href="./css/sidebar.css">
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
    <style>
        .main-content { margin-left: 80px; padding: 20px; }
        
        .form-box {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            max-width: 600px;
            margin: 0 auto; /* Centrer le formulaire */
        }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; color: #333; }
        .form-group input[type="text"], 
        .form-group textarea, 
        .form-group input[type="file"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        .btn-submit {
            background-color: #33A7FF;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
        }
        .btn-submit:hover { background-color: #007bff; }
        .msg { padding: 10px; margin-bottom: 15px; border-radius: 5px; text-align: center;}
        .success { background: #d4edda; color: #155724; }
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

    <script>
        let btn = document.querySelector("#btn");
        let sidebar = document.querySelector(".sidebar");
        btn.addEventListener('click', function(){
            sidebar.classList.toggle('active');
        });
    </script>
</body>
</html>