<?php
session_start();
require 'db.php';

// Vérification de connexion
if(!isset($_SESSION['user_id'])){
    header('Location: login.php');
    exit();
}

$estAdmin = (isset($_SESSION['role']) && $_SESSION['role'] === 'admin');
$userId   = $_SESSION['user_id'];
$annee    = (isset($_GET['annee']) && is_numeric($_GET['annee'])) ? (int)$_GET['annee'] : date("Y");

// --- TRAITEMENT DES ACTIONS (UNIQUEMENT POUR L'ADMIN MAINTENANT) ---
// J'ai rajouté "&& $estAdmin" dans la condition. Les users ne peuvent plus rien déclencher ici.
if ($estAdmin && isset($_GET['action'], $_GET['jour'], $_GET['mois'], $_GET['annee'])) {
    
    $jour = (int)$_GET['jour'];
    $mois = (int)$_GET['mois'];
    $an = (int)$_GET['annee'];
    
    // On définit le début et la fin de la journée cliquée
    $dateDebut = sprintf("%04d-%02d-%02d 00:00:00", $an, $mois, $jour);
    $dateFin   = sprintf("%04d-%02d-%02d 23:59:59", $an, $mois, $jour);

    if ($_GET['action'] == 'reserver') {
        // L'admin bloque la date (statut 'validee' direct)
        $statut = 'validee';
        $motif  = "Bloqué par l'admin";

        $stmt = $mysqli->prepare("INSERT INTO reservations (user_id, date_debut, date_fin, motif, statut) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("issss", $userId, $dateDebut, $dateFin, $motif, $statut);
        $stmt->execute();
        $stmt->close();
    } 
    elseif ($_GET['action'] == 'liberer') {
        // L'admin libère la date
        $stmt = $mysqli->prepare("DELETE FROM reservations WHERE date_debut <= ? AND date_fin >= ?");
        $stmt->bind_param("ss", $dateFin, $dateDebut);
        $stmt->execute();
        $stmt->close();
    }
    
    header("Location: calendrier.php?annee=$an");
    exit;
}

// --- RECUPERATION DES DONNEES ---
$datesEtat = []; 

$debutAnnee = "$annee-01-01 00:00:00";
$finAnnee   = "$annee-12-31 23:59:59";

$sql = "SELECT date_debut, date_fin, statut FROM reservations 
        WHERE (date_debut <= ? AND date_fin >= ?)";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("ss", $finAnnee, $debutAnnee);
$stmt->execute();
$res = $stmt->get_result();

while ($row = $res->fetch_assoc()) {
    $period = new DatePeriod(
        new DateTime($row['date_debut']),
        new DateInterval('P1D'),
        (new DateTime($row['date_fin']))->modify('+1 second')
    );

    foreach ($period as $dt) {
        $d = $dt->format('Y-m-d');
        if (!isset($datesEtat[$d]) || $datesEtat[$d] !== 'validee') {
            $datesEtat[$d] = $row['statut'];
        }
    }
}

// --- CALCULS CALENDRIER ---
$joursLabel = array(1=>"Lu",2=>"Ma",3=>"Me",4=>"Je",5=>"Ve",6=>"Sa",0=>"Di");
$NbrDeJour = [];
$PremierJourDuMois = [];
for($m=1; $m<=12; $m++) {
    $NbrDeJour[$m] = date("t", mktime(1,1,1,$m,2,$annee));
    $PremierJourDuMois[$m] = date("w", mktime(5,1,1,$m,1,$annee));
}
?>
<?php include 'sidebar.php'; ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Calendrier</title>
    <link rel="stylesheet" href="./css/sidebar.css"> 
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="./css/pages.css">
</head>
<body>
    <div style="margin-left: 80px; padding: 20px;">
        
        <div style="margin-bottom: 20px; font-family: sans-serif;">
            <h3>Disponibilités :</h3>
            <span style="display:inline-block;width:15px;height:15px;background:#98FB98;border:1px solid #000;"></span> Libre
            <span style="display:inline-block;width:15px;height:15px;background:#FF6666;border:1px solid #000;margin-left:10px;"></span> Occupé
            
            <?php if($estAdmin): ?>
                <p style="color:red; font-weight:bold; margin-top:10px;">Mode Administrateur : Cliquez sur une case pour bloquer/débloquer une date.</p>
            <?php endif; ?>
        </div>

        <h2 style="text-align:center; font-family: sans-serif;">
            <a href="?annee=<?php echo $annee-1; ?>"><i class='bx bx-chevron-left'></i></a>
            Année <?php echo $annee; ?>
            <a href="?annee=<?php echo $annee+1; ?>"><i class='bx bx-chevron-right'></i></a>
        </h2>

        <div class="table-responsive">
            <table class="cal-table">
                <tr>
                    <?php 
                    $moisNoms = ["", "Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"];
                    for($m=1; $m<=12; $m++) {
                        echo "<th class='cal-header' style='background:#33A7FF'>".$moisNoms[$m]."</th>";
                    }
                    ?>
                </tr>
                <tr>
                    <?php for($m=1; $m<=12; $m++): ?>
                        <td style="vertical-align:top; border:1px solid #000; padding:0;">
                            <table style="width:100%; border-collapse:collapse;">
                                <?php 
                                $jourSemaine = $PremierJourDuMois[$m];
                                for($d=1; $d<=$NbrDeJour[$m]; $d++): 
                                    
                                    $dateJour = sprintf("%04d-%02d-%02d", $annee, $m, $d);
                                    $class = "free";
                                    $isReserved = false;

                                    if (isset($datesEtat[$dateJour])) {
                                        $isReserved = true;
                                        $class = $datesEtat[$dateJour]; 
                                    }
                                ?>
                                <tr>
                                    <td class="<?php echo $class; ?>" style="border-bottom:1px solid #eee; font-size:12px;">
                                        <?php echo $joursLabel[$jourSemaine]; ?> <?php echo $d; ?>
                                    </td>
                                    
                                    <td class="<?php echo $class; ?>" style="text-align:right; border-bottom:1px solid #eee;">
                                        <?php if($isReserved): ?>
                                            <?php if($estAdmin): ?>
                                                <a href="?annee=<?php echo $annee; ?>&mois=<?php echo $m; ?>&jour=<?php echo $d; ?>&action=liberer">
                                                    <i class='bx bx-x' style='color:white; font-weight:bold;'></i>
                                                </a>
                                            <?php else: ?>
                                                <i class='bx bx-lock-alt' style='color:white; font-size:10px;'></i>
                                            <?php endif; ?>

                                        <?php else: ?>
                                            <?php if($estAdmin): ?>
                                                <a href="?annee=<?php echo $annee; ?>&mois=<?php echo $m; ?>&jour=<?php echo $d; ?>&action=reserver">
                                                    <i class='bx bx-plus' style='color:green; font-weight:bold;'></i>
                                                </a>
                                            <?php else: ?>
                                                &nbsp; 
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php 
                                    $jourSemaine++; 
                                    if($jourSemaine > 6) $jourSemaine = 0;
                                endfor; 
                                ?>
                            </table>
                        </td>
                    <?php endfor; ?>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>