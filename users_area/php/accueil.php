<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Accueil</title>
    <link rel="stylesheet" href="../css/SideBar.css">
    <!-- Boxicons CDN Link -->
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <!--NavBar-->
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