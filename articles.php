<?php 
session_start();
require 'db.php';

// 1. Vérification de sécurité (Si pas connecté -> Login)
if(!isset($_SESSION['user_id'])){
    header('Location: login.php');
    exit();
}

// 2. Est-ce un admin ?
$estAdmin = (isset($_SESSION['role']) && $_SESSION['role'] === 'admin');

// 3. Récupération des articles (du plus récent au plus vieux)
$sql = "SELECT * FROM articles ORDER BY date_creation DESC";
$result = $mysqli->query($sql);
?>
<?php include 'sidebar.php'; ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Articles</title>
    <link rel="stylesheet" href="./css/sidebar.css">
    <link rel="stylesheet" href="./css/pages.css">
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <div class="main-content">
        <h1>Actualités et Articles</h1>
        
        <?php if($estAdmin): ?>
            <div style="margin-bottom: 20px;">
                <a href="publier_article.php" class="btn-action" style="background-color:green;">
                    <i class='bx bx-plus'></i> Nouvel Article
                </a>
            </div>
        <?php endif; ?>

        <?php 
        // Vérifier s'il y a des articles
        if ($result->num_rows > 0) {
            while($article = $result->fetch_assoc()) {
                ?>
                <div class="article-card">
                    <?php if(!empty($article['image'])): ?>
                        <img src="uploads/<?= htmlspecialchars($article['image']); ?>" alt="<?= htmlspecialchars($article['titre']); ?>">
                    <?php endif; ?>

                    <h2><?= htmlspecialchars($article['titre']); ?></h2>
                    
                    <p><?= nl2br(htmlspecialchars($article['description'])); ?></p>
                    
                    <small style="color:#999;">Publié le <?= date("d/m/Y", strtotime($article['date_creation'])); ?></small>
                    <br><br>

                    <?php if($estAdmin): ?>
                        <a href="modifier_article.php?id=<?= $article['id']; ?>" class="btn-action btn-edit">Modifier</a>
                        <a href="supprimer_article.php?id=<?= $article['id']; ?>" 
                           class="btn-action btn-delete"
                           onclick="return confirm('Voulez-vous vraiment supprimer cet article ?');">
                           Supprimer
                        </a>
                    <?php endif; ?>
                </div>
                <?php
            }
        } else {
            echo "<p>Aucun article publié pour le moment.</p>";
        }
        ?>
    </div>
</body>
</html>