<?php 
session_start();
$bdd = new PDO('mysql:host=localhost;dbname=espace_admin;', 'root', '');
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../css/SideBar.css">
    <link rel="stylesheet" href="../css/articles.css">
    <!-- Boxicons CDN Link -->
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
    <title>Afficher tous les articles</title>
</head>
<body>
    <div class="articles-wrapper">
            <?php 
            $recupArticles = $bdd->query('SELECT * FROM articles');
            while($article = $recupArticles->fetch()){
            ?>
                <div class="container">
                    <h1 class="article"><?= htmlspecialchars($article['titre']); ?></h1>
                    <p class="article"><?= nl2br(htmlspecialchars($article['description'])); ?></p>

                    <?php if (!empty($article['image'])): ?>
                        <img class="article-img" src="../images/<?= htmlspecialchars($article['image']); ?>" alt="Image de l'article">
                    <?php endif; ?>
                </div>
            <?php
            }
            ?>
    </div>

    <!--NavBar-->
    <div class="menu-btn" id="menu-btn">
        <i class='bx bx-menu'></i>
    </div>
    <div class="sidebar">
        <div class="logo_content">
            <div class="logo">
                <img class='logo_aepm' src="../images/aepm.png" style="position:relative; height: 30px; width: 30px;">
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
                    <!--<img src="profile.jpg" alt="">-->
                    <div class="name_job">
                        <div class="name">AEPM</div>
                        <div class="job">Salle des fêtes</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>

        const sidebar = document.querySelector('.sidebar');
        const sidebarBtn = document.getElementById('btn'); // bouton dans la sidebar
        const menuBtn = document.getElementById('menu-btn'); // bouton externe

        sidebarBtn.addEventListener('click', () => {
            sidebar.classList.toggle('active');
        });

        menuBtn.addEventListener('click', () => {
            sidebar.classList.toggle('active');
        });

    </script>
</body>
</html>