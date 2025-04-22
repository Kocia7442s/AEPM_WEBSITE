<?php
session_start();

// Si l'utilisateur n'est pas connecté, redirection vers la page de connexion
if (!isset($_SESSION['connecte']) || $_SESSION['connecte'] !== true) {
    header('Location: login.php');
    exit();
}

// Connexion à la base de données
$host = 'localhost'; // Nom de l'hôte, peut être 'localhost' ou l'adresse IP du serveur
$dbname = 'aepm'; // Remplacez par le nom de votre base de données
$username = 'root'; // Remplacez par votre nom d'utilisateur MySQL
$password = ''; // Remplacez par votre mot de passe MySQL

try {
    // Créer une nouvelle instance de PDO pour la connexion à la base de données
    $bdd = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    // Définir le mode d'erreur de PDO pour afficher les erreurs SQL
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // En cas d'erreur, afficher un message d'erreur
    die('Erreur de connexion à la base de données : ' . $e->getMessage());
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/SideBar.css">
    <!-- Boxicons CDN Link -->
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
    <title>Contact</title>
</head>
<body>
    <!--NavBar-->
    <div class="menu-btn" id="menu-btn">
        <i class='bx bx-menu'></i>
    </div>
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