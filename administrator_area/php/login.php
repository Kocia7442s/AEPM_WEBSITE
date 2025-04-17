<?php
session_start();
if(isset($_POST['valider'])){
    if(!empty($_POST['pseudo']) AND !empty($_POST['mdp'])){
        $pseudo_par_defaut = "admin";
        $mdp_par_defaut = "admin1234";

        $pseudo_saisi = htmlspecialchars($_POST['pseudo']);
        $mdp_saisi = htmlspecialchars($_POST['mdp']);

        if($pseudo_saisi == $pseudo_par_defaut && $mdp_saisi == $mdp_par_defaut){
            $_SESSION['mdp'] = $mdp_saisi;
            header('Location: accueil.php');
        }else{
            echo "Votre mot de passe ou pseudo est incorrect";
        }
    }else{
        echo "Veuillez compléter tous les champs...";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CONNEXION ADMINISTRATEUR</title>
    <link rel="stylesheet" href="../css/login.css">
</head>
<body>

    <section>
        <div class="box">
            <div class="container">
                <div class="form">
                    <h2>Connexion</h2>
                    <form action="", method="POST">
                        <div class="inputBox">
                            <input type="text" name="pseudo" placeholder="Identifiant" autocomplete="off"/>
                        </div>
                        <br>
                        <div class="inputBox">
                            <input type="password" name="mdp" placeholder="Mot de passe" autocomplete="off"/>
                        </div>
                        <br>
                        <div class="inputBox">
                        <input type="submit" name="valider" value="valider"/>
                    </form>
                </div>
            </div>
        </div>
    </section>

</body>
</html>