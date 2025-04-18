<?php
//page: calendrier.php
session_start();//pour maintenir la session active
//connexion à la base de données:
$BDD = array();
$BDD['host'] = "localhost";
$BDD['user'] = "root";
$BDD['pass'] = "";
$BDD['db'] = "aepm";
$mysqli = mysqli_connect($BDD['host'], $BDD['user'], $BDD['pass'], $BDD['db']);
if(!$mysqli) {
    echo "Connexion non &eacute;tablie.";
    exit;
}
$NomDeSessionAdmin="*****";//mettre ici le nom de $_SESSION de votre site quand l'administrateur est connecté

$jours = array(1=>"Lu",2=>"Ma",3=>"Me",4=>"Je",5=>"Ve",6=>"Sa",0=>"Di");
if(isset($_GET['annee']) AND preg_match("#^[0-9]{4}$#",$_GET['annee'])){//si on souhaite afficher une autre année, on l'affiche si elle est correcte
    $annee=$_GET['annee'];
} else {
    $annee=date("Y");//si non, on affiche l'année actuelle
}
$NbrDeJour=[];
for($mois=1;$mois<=12;$mois++) {
    $NbrDeJour[$mois]=date("t",mktime(1,1,1,$mois,2,$annee));
    $PremierJourDuMois[$mois]=date("w",mktime(5,1,1,$mois,1,$annee));
}
?>
<table id="recap" style="position: relative; left: 100px; top: 710px; width:200px;">
    <tr>
        <td style="background:#FF8888;width:15px;height:15px;"></td><td>Réservé</td>
    </tr>
    <tr>
        <td style="background:#88FF88;width:15px;height:15px;"></td><td>Disponible</td>
    </tr>
</table>
<?php
$StyleTh="text-shadow: 1px 1px 1px #000;color:white;width:75px;border-right:1px solid black;border-bottom:1px solid black;";
?>
<table style="border:1px solid black;border-collapse:collapse;box-shadow: 10px 10px 5px #888888; position: relative; margin-left: 7.5vw; width: 90vw;">
    <caption style="font-size:18px;"><a href="?annee=<?php echo $annee-1; ?>" style="font-size:50%;vertical-align:middle;text-decoration:none;"><?php echo $annee-1; ?></a> <?php echo $annee; ?> <a href="?annee=<?php echo $annee+1; ?>" style="font-size:50%;vertical-align:middle;text-decoration:none;"><?php echo $annee+1; ?></a></caption>
    <tr style="border-right:1px solid black;">
                    <th style="<?php echo $StyleTh; ?>background:#FF3333">Janvier</th>
					<th style="<?php echo $StyleTh; ?>background:#FF9933">Février</th>
					<th style="<?php echo $StyleTh; ?>background:#FFF833">Mars</th>
					<th style="<?php echo $StyleTh; ?>background:#A7FF33">Avril</th>
					<th style="<?php echo $StyleTh; ?>background:#3EFF30">Mai</th>
					<th style="<?php echo $StyleTh; ?>background:#30FF83">Juin</th>
					<th style="<?php echo $StyleTh; ?>background:#33FFEB">Juillet</th>
					<th style="<?php echo $StyleTh; ?>background:#33A7FF">Août</th>
					<th style="<?php echo $StyleTh; ?>background:#3341FF">Septembre</th>
					<th style="<?php echo $StyleTh; ?>background:#8636FF">Octobre</th>
					<th style="<?php echo $StyleTh; ?>background:#F133FF">Novembre</th>
					<th style="<?php echo $StyleTh; ?>background:#FF33A7">Décembre</th>
    </tr>
    <tr>
        <?php
        for($mois=1;$mois<=12;$mois++) {
            for($jour=1;$jour<=$NbrDeJour[$mois];$jour++){
                if($jour==1){
                    echo '<td style="vertical-align:top;border-right:1px solid black;">
                            <center><table style="width:100%;border-collapse:collapse;">';
                            $Jr=$PremierJourDuMois[$mois];
                }
            $JourReserve=0;
            $req = mysqli_query($mysqli,"SELECT * FROM calendrier WHERE date='".$annee."-".$mois."-".$jour."'");
            if(mysqli_num_rows($req)>0)$JourReserve=1;
            ?>
            <tr>
                <td style="border-bottom:1px solid #eee;<?php echo $JourReserve==1?"background:#FF8888;":"background:#88FF88;"; ?>"><?php echo $jours[$Jr]; ?></td>
                <td style="border-bottom:1px solid #eee;width:20%;<?php echo $JourReserve==1?"background:#FF8888;":"background:#88FF88;"; ?>"><?php echo $jour; ?></td>
                <?php 
                if($Jr>5){
                    $Jr=0;
                } else {
                    $Jr++;
                }
                if(isset($_SESSION[$NomDeSessionAdmin])) { ?>
                <td style="border-bottom:1px solid #eee;<?php echo $JourReserve==1?"background:#FF8888;":"background:#88FF88;"; ?>"><a href="?jour=<?php echo $jour; ?>&amp;mois=<?php echo $mois; ?>&amp;annee=<?php echo $annee; ?>&amp;choix=<?php echo $JourReserve==1?0:1; ?>#recap"><img src="images/<?php echo $JourReserve; ?>.png" alt="Action" style="width:13px;" title="Mettre ce jour en <?php echo $JourReserve==1?"Disponible":"Réservé"; ?>" /></a></td>"
                <?php } ?>
            </tr>
            <?php
                if($jour==$NbrDeJour[$mois]){
                    echo '</table></center>
                        </td>';
                }
            }
        }
        ?>
    </tr>
</table>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../css/calendrier.css">
    <link rel="stylesheet" href="../css/Sidebar.css">
    <title>Calendrier de réservation</title>
    <!-- Boxicons CDN Link -->
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
</head>
<body style="background: #f0f0f0;">
    <!--NavBar-->
    <div class="menu-btn" id="menu-btn">
        <i class='bx bx-menu'></i>
    </div>
    <div class="sidebar">
        <div class="logo_content">
            <div class="logo">
                <img class='logo_aepm' src="../images/aepm.png" style="position:relative; height: 30px; width: 30px;">
                <div class="logo_name">AEPM WEB SITE</div>
            </div>
            <i class='bx bx-menu' id="btn"></i>
        </div>
        <ul class="nav_list">
            <li>
                <i class='bx bx-search' ></i>
                <input type="text" placeholder="Search...">
                <span class="tooltip">Search</span>
            </li>
            <li>
                <a href="accueil.php">
                    <i class='bx bx-home' ></i>
                    <span class="links_name">Accueil</span>
                </a>
                <span class="tooltip">Accueil</span>
            </li>
            <li>
                <a href="articles.php">
                    <i class='bx bx-book' ></i>
                    <span class="links_name">Articles</span>
                </a>
                <span class="tooltip">Articles</span>
            </li>
            <li>
                <a href="calendrier.php">
                    <i class='bx bx-calendar' ></i>
                    <span class="links_name">Calendrier</span>
                </a>
                <span class="tooltip">Calendrier</span>
            </li>
            <li>
                <a href="contact.php">
                    <i class='bx bx-message-detail'></i>
                    <span class="links_name">Contact</span>
                </a>
                <span class="tooltip">Contact</span>
            </li>
        </ul>
        <div class="profile_content">
            <div class="profile">
                <div class="profile_details">
                    <div class="name_job">
                        <div class="name">AEPM</div>
                        <div class="job">Salle des fêtes</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--<a href="../images/reserv.odt">LIEN TELECHARGEMENT</a>-->

    <script>

        const sidebar = document.querySelector('.sidebar');
        const sidebarBtn = document.getElementById('btn'); // bouton dans la sidebar
        const menuBtn = document.getElementById('menu-btn'); // bouton externe

        sidebarBtn.addEventListener('click', () => {
            sidebar.classList.toggle('active');
        });

        menuBtn.addEventListener('click', () => {
            sidebar.classList.toggle('active');
        });

    </script>
</body>
</html>