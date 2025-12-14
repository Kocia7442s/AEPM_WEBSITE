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
<?php include 'sidebar.php'; ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact</title>
    <link rel="stylesheet" href="./css/sidebar.css">
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="./css/pages.css">
</head>
<body>
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
</body>
</html>