<?php 
$bdd = new PDO('mysql:host=localhost;dbname=aepm;', 'root', '');
if(isset($_GET['id']) && !empty($_GET['id'])){
    $getid = $_GET['id'];

    $recupArtricle = $bdd->prepare('SELECT * FROM articles WHERE id = ? ');
    $recupArtricle->execute(array($getid));
    if($recupArtricle->rowCount() > 0){
        $articleInfos = $recupArtricle->fetch();
        $titre = $articleInfos['titre'];
        $description = str_replace ('<br />', '', $articleInfos['description']);
        if(isset($_POST['valider'])){
            $titre_saisi = htmlspecialchars($_POST['titre']);
            $description_saisie = nl2br(htmlspecialchars($_POST['description']));

            $updateArticle = $bdd->prepare('UPDATE articles SET titre = ?, description = ? WHERE id = ?');
            $updateArticle->execute(array($titre_saisi, $description_saisie, $getid));
            header('Location: articles.php');
        }
    }else{
        echo "Aucun article trouvé";
    }
}else{
    echo "Aucun identifiant trouvé";
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Modifier l'article</title>
    <link rel="stylesheet" href="../css/modifier_article.css">
</head>
<body>
    <!--<form method="POST" action="">
        <input type="text" name="titre" value="<?= $titre; ?>">
        <br>
        <textarea name="description"><?= $description; ?></textarea>
        <br><br>
        <input type="submit" name="valider">
    </form>-->
    <form class="formulaire" method ="POST" action="">
        <box class="box">
            <div class="titre">
                <label for="titre">Modifier le titre de l'article</label>
                <input class="input_titre" type="text" name="titre"  value="<?= $titre; ?>" autocomplete="off" style="width: 250px;">
                <br>
            </div>
            <div class="description">
                <label for="description">Modifier la description de l'article</label>
                <textarea name="description" value="<?= $description; ?>" cols="40" rows="5"></textarea>
                <br>
            </div>
            <div class="file">
                <label for="file">Modifier l'image de l'article</label>
                <input class="input_file" type="file" id="file" name="file">
                <br>
            </div>
            <div class="button">
                <input class="input_button" name="valider" type="submit">
            </div>
            
        </box>
    </form>
</body>
</html>