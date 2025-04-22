<?php
$bdd = new PDO('mysql:host=localhost;dbname=aepm;', 'root', '');

if(isset($_GET['id']) && !empty($_GET['id'])){
    $getid = $_GET['id'];
    
    // Récupérer l'article de la base de données
    $recupArtricle = $bdd->prepare('SELECT * FROM articles WHERE id = ?');
    $recupArtricle->execute(array($getid));
    
    if($recupArtricle->rowCount() > 0){
        // Récupérer les informations de l'article
        $article = $recupArtricle->fetch();
        
        // Vérifier s'il y a une image associée à cet article
        if (!empty($article['image'])) {
            $imagePath = '../images/' . $article['image']; // Chemin de l'image dans le dossier
            
            // Vérifier si le fichier image existe et le supprimer
            if (file_exists($imagePath)) {
                unlink($imagePath); // Supprimer le fichier image
            }
        }
        
        // Supprimer l'article de la base de données
        $deleteArticle = $bdd->prepare('DELETE FROM articles WHERE id = ?');
        $deleteArticle->execute(array($getid));
        
        // Rediriger vers la page des articles
        header('Location: articles.php');
        exit(); // S'assurer que le script s'arrête ici après la redirection
    } else {
        echo "Aucun article trouvé";
    }
} else {
    echo "Aucun identifiant trouvé";
}
?>