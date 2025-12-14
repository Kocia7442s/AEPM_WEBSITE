<?php
session_start();
require 'db.php';

// 1. SÉCURITÉ : On vérifie que c'est bien un Admin
if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin'){
    header('Location: accueil.php');
    exit();
}

// 2. On vérifie qu'on a bien un ID
if(isset($_GET['id']) && !empty($_GET['id'])){
    
    $id = (int)$_GET['id']; // On force en entier pour la sécurité

    // Etape A : On récupère d'abord le nom de l'image pour pouvoir supprimer le fichier
    $stmt = $mysqli->prepare("SELECT image FROM articles WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0){
        $article = $result->fetch_assoc();
        
        // Etape B : Si une image existe, on la supprime du dossier "uploads"
        if(!empty($article['image'])){
            $cheminImage = 'uploads/' . $article['image'];
            
            // "unlink" est la fonction PHP pour supprimer un fichier
            if(file_exists($cheminImage)){
                unlink($cheminImage);
            }
        }

        // Etape C : Maintenant on peut supprimer la ligne dans la base de données
        $stmtDelete = $mysqli->prepare("DELETE FROM articles WHERE id = ?");
        $stmtDelete->bind_param("i", $id);
        $stmtDelete->execute();
        $stmtDelete->close();

        // Redirection vers la liste
        header('Location: articles.php');
        exit();

    } else {
        echo "Aucun article trouvé avec cet ID.";
    }

} else {
    echo "Identifiant manquant.";
}
?>