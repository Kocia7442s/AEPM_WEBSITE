<?php
// login.php
session_start();
require 'db.php'; 

$msg = "";
if(isset($_GET['created'])) $msg = "Compte créé ! Connectez-vous.";

if(isset($_POST['valider'])){
    if(!empty($_POST['email']) AND !empty($_POST['mdp'])){
        
        $email = htmlspecialchars($_POST['email']);
        $mdp_saisi = $_POST['mdp'];

        // On sélectionne uniquement les champs qui existent dans ta table users
        $stmt = $mysqli->prepare("SELECT id, nom, mot_de_passe, role FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $res = $stmt->get_result();

        if($res->num_rows === 1){
            $user = $res->fetch_assoc();
            
            // Vérification du hash
            if(password_verify($mdp_saisi, $user['mot_de_passe'])){
                
                session_regenerate_id(true);

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['nom']     = $user['nom'];
                $_SESSION['email']   = $email;
                $_SESSION['role']    = $user['role']; 

                header('Location: accueil.php');
                exit();
            } else {
                $msg = "Mot de passe incorrect.";
            }
        } else {
            $msg = "Aucun compte associé à cet email.";
        }
    } else {
        $msg = "Veuillez tout remplir.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <link rel="stylesheet" href="./css/global.css">
</head>
<body>
    <section>
        <div class="container">
            <div class="form">
                <h2>Connexion</h2>
                <form method="POST" action="">
                    
                    <?php if($msg) echo "<p style='color:white;background:rgba(255,0,0,0.5);padding:5px;margin-bottom:10px;border-radius:5px;'>$msg</p>"; ?>
                    
                    <div class="inputBox">
                        <input type="email" name="email" placeholder="Email" required>
                    </div>

                    <div class="inputBox">
                        <input type="password" name="mdp" placeholder="Mot de passe" required>
                    </div>

                    <div class="inputBox">
                        <input type="submit" name="valider" value="Entrer">
                    </div>

                    <div class="forget">
                        <a href="inscription.php">Créer un compte</a>
                    </div>

                </form>
            </div>
        </div>
    </section>
</body>
</html>