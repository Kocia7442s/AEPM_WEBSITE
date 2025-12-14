<?php
// inscription.php
session_start();
require 'db.php'; // Connexion BDD

$msg = "";

if(isset($_POST['valider'])){
    if(!empty($_POST['nom']) AND !empty($_POST['email']) AND !empty($_POST['mdp'])){
        
        $nom = htmlspecialchars($_POST['nom']);
        $email = htmlspecialchars($_POST['email']);
        $mdp_clair = $_POST['mdp'];

        // 1. Vérifier si l'email existe
        $check = $mysqli->prepare("SELECT id FROM users WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();

        if($check->num_rows == 0){
            // 2. Hachage
            $mdp_hash = password_hash($mdp_clair, PASSWORD_DEFAULT);
            
            // 3. Insertion
            $insert = $mysqli->prepare("INSERT INTO users (nom, email, mot_de_passe) VALUES (?, ?, ?)");
            $insert->bind_param("sss", $nom, $email, $mdp_hash);
            
            if($insert->execute()){
                // On redirige vers le login avec un message de succès
                header('Location: login.php?created=1');
                exit();
            } else {
                $msg = "Erreur technique.";
            }
        } else {
            $msg = "Cet email est déjà utilisé.";
        }
    } else {
        $msg = "Veuillez remplir tous les champs.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
    <link rel="stylesheet" href="./css/global.css">
</head>
<body>
    <section>
        <div class="box">
            <div class="container">
                <div class="form">
                    <h2>Inscription</h2>
                    
                    <?php if($msg) echo "<p style='color: white; background: rgba(255,0,0,0.5); padding: 5px; border-radius: 5px; margin-bottom: 10px;'>$msg</p>"; ?>

                    <form method="POST" action="">
                        <div class="inputBox">
                            <input type="text" name="nom" placeholder="Votre Nom" autocomplete="off" required>
                        </div>

                        <div class="inputBox">
                            <input type="email" name="email" placeholder="Votre Email" autocomplete="off" required>
                        </div>

                        <div class="inputBox">
                            <input type="password" name="mdp" placeholder="Mot de passe" autocomplete="off" required>
                        </div>

                        <div class="inputBox">
                            <input type="submit" name="valider" value="Valider">
                        </div>

                        <p class="forget">Déjà un compte ? <a href="login.php">Se connecter</a></p>
                    </form>
                </div>
            </div>
        </div>
    </section>
</body>
</html>