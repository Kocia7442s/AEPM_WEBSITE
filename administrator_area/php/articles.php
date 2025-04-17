<?php 
session_start();
$bdd = new PDO('mysql:host=localhost;dbname=espace_admin;', 'root', '');
if(!$_SESSION['mdp']){
    header('Location: login.php');
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../css/SideBar.css">
    <title>Afficher tous les articles</title>
    <!-- Boxicons CDN Link -->
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <?php 
        $recupArticles = $bdd->query('SELECT * FROM articles');
        while($article = $recupArticles->fetch()){
            ?>
            <div class="container">
                <h1 class="article" style="position:relative; left:100px;"><?= $article['titre']; ?></h1>
                <p class="article" style="position:relative; left:100px;"><?= $article['description']; ?></p>
                <a class="article" style="position:relative; left:100px;" href="supprimer_article.php?id=<?= $article['id']; ?>">
                    <button style="color: white; background-color: red; margin-bottom: 10px;">Supprimer l'article</button>
                </a>
                <a class="article" style="position:relative; left:100px;" href="modifier_article.php?id=<?= $article['id']; ?>">
                    <button style="color: white; background-color: blue; margin-bottom: 10px;">Modifier l'article</button>
                </a>
            </div>
            <br>
            <?php
        }
    ?>

    <!--NavBar-->
    <div class="sidebar">
        <div class="logo_content">
            <div class="logo">
                <img class='logo_aepm' src="../logo/aepm.png" style="position:relative; height: 30px; width: 30px;">
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
                    <!--<img src="profile.jpg" alt="">-->
                    <div class="name_job">
                        <div class="name">AEPM</div>
                        <div class="job">Salle des fêtes</div>
                    </div>
                </div>
                <a href="logout.php" style="color: white;"><i class='bx bx-log-out' id="log_out"></a></i></a>
            </div>
        </div>
    </div>

    <script>

        let btn = document.querySelector("#btn");
        let sidebar = document.querySelector(".sidebar");
        let searchBtn = document.querySelector(".bx-search");

        btn.addEventListener('click', function(){
            sidebar.classList.toggle('active');
        });
        searchBtn.addEventListener('click', function(){
            sidebar.classList.toggle('active')
        });

    </script>
</body>
</html>