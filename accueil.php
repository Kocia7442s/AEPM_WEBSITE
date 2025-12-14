<?php
session_start();

// CORRECTION : On vérifie user_id (car on ne stocke plus le mdp en session)
if(!isset($_SESSION['user_id'])){
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Accueil</title>
    <link rel="stylesheet" href="./css/sidebar.css">
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
    
    <style>
        .home_content {
            position: absolute;
            height: 100%;
            width: calc(100% - 78px);
            left: 78px;
            transition: all 0.5s ease;
            padding: 20px;
        }
        .sidebar.active ~ .home_content {
            width: calc(100% - 240px);
            left: 240px;
        }
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

    <div class="home_content">
        <h1>Bienvenue, <?php echo htmlspecialchars($_SESSION['nom']); ?> !</h1>
        <p>Vous êtes connecté en tant que : <strong><?php echo $_SESSION['role']; ?></strong></p>
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