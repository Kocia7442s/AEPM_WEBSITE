<?php
session_start();

// Connexion PDO sécurisée
try {
    $pdo = new PDO('mysql:host=localhost;dbname=aepm;charset=utf8', 'root', '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

// CSRF Token
if (empty($_SESSION['token'])) {
    $_SESSION['token'] = bin2hex(random_bytes(32));
}

// Tentatives
if (!isset($_SESSION['tentatives'])) {
    $_SESSION['tentatives'] = 0;
}

if ($_SESSION['tentatives'] >= 5) {
    die('Trop de tentatives échouées. Réessayez plus tard.');
}

// Traitement du formulaire
if (isset($_POST['valider'])) {
    if (!empty($_POST['pseudo']) && !empty($_POST['mdp']) && !empty($_POST['token'])) {

        if (!hash_equals($_SESSION['token'], $_POST['token'])) {
            die('Erreur de sécurité. Token invalide.');
        }

        $pseudo_saisi = htmlspecialchars($_POST['pseudo']);
        $mdp_saisi = htmlspecialchars($_POST['mdp']);

        // Vérifier si l'utilisateur existe
        $sql = "SELECT * FROM administrateurs WHERE pseudo = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$pseudo_saisi]);
        $utilisateur = $stmt->fetch();

        if ($utilisateur && password_verify($mdp_saisi, $utilisateur['mot_de_passe'])) {
            $_SESSION['connecte'] = true;
            $_SESSION['pseudo'] = $utilisateur['pseudo'];
            $_SESSION['tentatives'] = 0;
            header('Location: accueil.php');
            exit();
        } else {
            $_SESSION['tentatives']++;
            echo "Votre mot de passe ou pseudo est incorrect.";
        }
    } else {
        echo "Veuillez compléter tous les champs.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion Administrateur</title>
    <link rel="stylesheet" href="../css/login.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

<section>
    <div class="box">
        <div class="container">
            <div class="form">
                <h2>Connexion</h2>
                <form action="" method="POST">
                    <div class="inputBox">
                        <input type="text" name="pseudo" placeholder="Identifiant" autocomplete="off" required/>
                    </div>
                    <br>
                    <div class="inputBox">
                        <input type="password" name="mdp" placeholder="Mot de passe" autocomplete="off" required/>
                    </div>
                    <br>
                    <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>">
                    <div class="inputBox">
                        <input type="submit" name="valider" value="Valider"/>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

</body>
</html>
