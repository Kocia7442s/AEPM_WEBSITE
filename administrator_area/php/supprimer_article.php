<?php 
$bdd = new PDO('mysql:host=localhost;dbname=espace_admin;', 'root', '');
if(isset($_GET['id']) && !empty($_GET['id'])){
    $getid = $_GET['id'];
    $recupArtricle = $bdd->prepare('SELECT * FROM articles WHERE id = ?');
    $recupArtricle->execute(array($getid));
    if($recupArtricle->rowCount() > 0){
        $deleteArticle = $bdd->prepare('DELETE FROM articles WHERE id = ?');
        $deleteArticle->execute(array($getid));
        header('Location: articles.php');
    }else{
        echo "Aucun article trouvé";
    }
}else{
echo "Aucun identifiant trouvé";
}
?>