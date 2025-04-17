<?php
session_start();
$bdd = new PDO('mysql:host=localhost;dbname=espace_admin;', 'root', '');
if(!$_SESSION['mdp']){
    header('Location: login.php');
}

if(isset($_POST['titre'], $_POST['description'])){
    if(!empty($_POST['titre']) && !empty($_POST['description']))){
        $titre = htmlspecialchars($_POST['titre']);
        $description = nl2br(htmlspecialchars($_POST['description']));

        $insererArticle = $bdd->prepare('INSERT INTO articles(titre, description)VALUES(?, ?)');
        $insererArticle->execute(array($titre, $description));

        echo "L'article a bien été envoyé !";
    }else{
        echo "Veuillez compléter tous les champs...";
    }
}

// upload photo

if(isset($_POST['envoyer'])){
    $dossierTempo = $_FILES['image']['tmp_name'];
    $dossierSite = '../images/'.$_FILES['image']['name'];
    $deplacer = move_uploaded_file($dossierTempo, $dossierSite);
    if($deplacer){
        
        $sql = 'INSERT INTO articles
                (image) VALUES (:image)';
        $insererArticle = $bdd->prepare($sql);
        $retour = $insererArticle->execute(array(
            ':image' => $_FILES['image']['name']
        ));

        if($retour){
            echo 'Image envoyée avec succès';
        }else{
            echo 'Une erreur est survenue';
        }

    }else{
        echo 'Une erreur est survenue';
    }
}


?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Publication article</title>
    <link rel="stylesheet" href="../css/SideBar.css">
    <link rel="stylesheet" href="../css/publier_article.css">
    <!-- Boxicons CDN Link -->
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <form class="formulaire" method ="POST" action="">
        <box class="box">
            <div class="titre">
                <label for="titre">Ajouter un titre à votre article</label>
                <input class="input_titre" type="text" name="titre" autocomplete="off" style="width: 250px;">
                <br>
            </div>
            <div class="description">
                <label for="description">Ajouter une description à votre article</label>
                <textarea name="description" cols="40" rows="5"></textarea>
                <br>
            </div>
            <div class="file">
                <label for="upload">Ajouter une image à votre article</label>
                <input class="input_file" type="file" id="upload" name="image">
                <br>
            </div>
            <div class="button">
                <input class="input_button" type="submit" name="envoyer">
            </div>
            
        </box>
    </form>

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
                <a href="publier-article.php">
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
                    <span class="links_name">contact</span>
                </a>
                <span class="tooltip">contact</span>
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