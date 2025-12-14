<?php 
session_start();
require 'db.php';

// 1. Vérification de sécurité (Si pas connecté -> Login)
if(!isset($_SESSION['user_id'])){
    header('Location: login.php');
    exit();
}

// 2. Est-ce un admin ?
$estAdmin = (isset($_SESSION['role']) && $_SESSION['role'] === 'admin');

// 3. Récupération des articles (du plus récent au plus vieux)
$sql = "SELECT * FROM articles ORDER BY date_creation DESC";
$result = $mysqli->query($sql);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Articles</title>
    <link rel="stylesheet" href="./css/sidebar.css">
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
    
    <style>
        /* CSS Spécifique pour rendre les articles jolis */
        .main-content {
            margin-left: 80px; /* Pour ne pas passer sous la sidebar */
            padding: 20px;
        }
        .article-card {
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .article-card img {
            max-width: 100%; /* L'image ne dépasse pas */
            max-height: 300px;
            object-fit: cover;
            border-radius: 5px;
            margin-bottom: 15px;
            display: block;
        }
        .article-card h2 { margin-top: 0; color: #333; }
        .article-card p { color: #555; line-height: 1.6; }
        .btn-action {
            display: inline-block;
            padding: 8px 15px;
            text-decoration: none;
            color: white;
            border-radius: 4px;
            font-size: 14px;
            margin-right: 5px;
        }
        .btn-edit { background-color: #33A7FF; }
        .btn-delete { background-color: #FF4F1F; }
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
        <h1>Actualités et Articles</h1>
        
        <?php if($estAdmin): ?>
            <div style="margin-bottom: 20px;">
                <a href="publier_article.php" class="btn-action" style="background-color:green;">
                    <i class='bx bx-plus'></i> Nouvel Article
                </a>
            </div>
        <?php endif; ?>

        <?php 
        // Vérifier s'il y a des articles
        if ($result->num_rows > 0) {
            while($article = $result->fetch_assoc()) {
                ?>
                <div class="article-card">
                    <?php if(!empty($article['image'])): ?>
                        <img src="uploads/<?= htmlspecialchars($article['image']); ?>" alt="Image article">
                    <?php endif; ?>

                    <h2><?= htmlspecialchars($article['titre']); ?></h2>
                    
                    <p><?= nl2br(htmlspecialchars($article['description'])); ?></p>
                    
                    <small style="color:#999;">Publié le <?= date("d/m/Y", strtotime($article['date_creation'])); ?></small>
                    <br><br>

                    <?php if($estAdmin): ?>
                        <a href="modifier_article.php?id=<?= $article['id']; ?>" class="btn-action btn-edit">Modifier</a>
                        <a href="supprimer_article.php?id=<?= $article['id']; ?>" 
                           class="btn-action btn-delete"
                           onclick="return confirm('Voulez-vous vraiment supprimer cet article ?');">
                           Supprimer
                        </a>
                    <?php endif; ?>
                </div>
                <?php
            }
        } else {
            echo "<p>Aucun article publié pour le moment.</p>";
        }
        ?>
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