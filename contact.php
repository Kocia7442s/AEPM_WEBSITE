<?php
session_start();
require 'db.php';

// 1. Vérification de sécurité
if(!isset($_SESSION['user_id'])){
    header('Location: login.php');
    exit();
}

$msg = "";

// 2. Traitement du formulaire
if(isset($_POST['envoyer'])){
    if(!empty($_POST['sujet']) && !empty($_POST['contenu'])){
        
        $sujet = htmlspecialchars($_POST['sujet']);
        $contenu = htmlspecialchars($_POST['contenu']);
        $userId = $_SESSION['user_id'];

        // Insertion dans la BDD
        $stmt = $mysqli->prepare("INSERT INTO messages (user_id, sujet, contenu) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $userId, $sujet, $contenu);
        
        if($stmt->execute()){
            $msg = "Votre message a bien été envoyé !";
        } else {
            $msg = "Une erreur est survenue lors de l'envoi.";
        }
        $stmt->close();
    } else {
        $msg = "Veuillez remplir tous les champs.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact</title>
    <link rel="stylesheet" href="./css/sidebar.css">
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
    <style>
        /* CSS spécifique pour le formulaire de contact */
        .main-content { margin-left: 80px; padding: 20px; display: flex; justify-content: center; }
        .contact-box { 
            background: white; 
            padding: 30px; 
            border-radius: 10px; 
            box-shadow: 0 0 15px rgba(0,0,0,0.1); 
            width: 100%; 
            max-width: 600px; 
        }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: bold; color: #333; }
        .form-group input, .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            font-family: 'Poppins', sans-serif;
        }
        .btn-send {
            background-color: #33A7FF;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
            transition: background 0.3s;
        }
        .btn-send:hover { background-color: #008be0; }
        
        .msg { padding: 15px; margin-bottom: 20px; border-radius: 5px; text-align: center; }
        .success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    </style>
</head>
<body>

    <div class="sidebar">
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
        <div class="contact-box">
            <h2 style="text-align: center; margin-bottom: 20px;">Nous contacter</h2>
            
            <?php if(!empty($msg)): ?>
                <div class="msg <?php echo strpos($msg, 'envoyé') !== false ? 'success' : 'error'; ?>">
                    <?php echo $msg; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label>De :</label>
                    <input type="text" value="<?php echo htmlspecialchars($_SESSION['nom']); ?> (<?php echo htmlspecialchars($_SESSION['email']); ?>)" disabled style="background:#f9f9f9; color:#666;">
                </div>

                <div class="form-group">
                    <label for="sujet">Sujet</label>
                    <input type="text" name="sujet" placeholder="Ex: Demande d'information réservation..." required>
                </div>

                <div class="form-group">
                    <label for="contenu">Message</label>
                    <textarea name="contenu" rows="6" placeholder="Votre message..." required></textarea>
                </div>

                <button type="submit" name="envoyer" class="btn-send">Envoyer le message</button>
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