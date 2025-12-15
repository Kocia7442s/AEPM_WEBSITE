<?php
session_start();
require 'db.php';

// 1. SÉCURITÉ : Admin uniquement
if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin'){
    header('Location: accueil.php');
    exit();
}

// 2. Récupération des messages
// On joint la table 'users' pour savoir QUI a écrit le message
$sql = "SELECT m.id, m.sujet, m.contenu, m.date_envoi, u.nom, u.email 
        FROM messages m 
        JOIN users u ON m.user_id = u.id 
        ORDER BY m.date_envoi DESC";

$result = $mysqli->query($sql);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messagerie</title>
    <link rel="stylesheet" href="./css/sidebar.css">
    <link rel="stylesheet" href="./css/pages.css"> 
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
    <style>
        /* Petit CSS spécifique pour la table des messages */
        .msg-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            border-radius: 8px;
            overflow: hidden;
        }
        .msg-table th, .msg-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        .msg-table th {
            background-color: #33A7FF;
            color: white;
            font-weight: 600;
        }
        .msg-table tr:hover { background-color: #f9f9f9; }
        .date-col { width: 150px; color: #666; font-size: 0.9em; }
        .sender-col { width: 200px; font-weight: bold; }
        .subject-col { font-weight: 600; color: #333; }
        
        /* Badge pour les rôles ou status si besoin plus tard */
        .sender-email { display: block; font-size: 0.8em; color: #888; font-weight: normal;}
    </style>
</head>
<body>

    <?php include 'sidebar.php'; ?>

    <div class="main-content">
        <h1>Boîte de réception</h1>
        
        <?php if($result->num_rows > 0): ?>
            <div style="overflow-x:auto;">
                <div class="table-responsive">
                    <table class="msg-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Expéditeur</th>
                                <th>Sujet / Message</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($msg = $result->fetch_assoc()): ?>
                                <tr>
                                    <td class="date-col">
                                        <?php 
                                            // Formatage de la date (ex: 12/03/2024 à 14:30)
                                            echo date("d/m/Y à H:i", strtotime($msg['date_envoi'])); 
                                        ?>
                                    </td>
                                    <td class="sender-col">
                                        <?php echo htmlspecialchars($msg['nom']); ?>
                                        <span class="sender-email"><?php echo htmlspecialchars($msg['email']); ?></span>
                                    </td>
                                    <td>
                                        <div class="subject-col"><?php echo htmlspecialchars($msg['sujet']); ?></div>
                                        <div style="margin-top:5px; color:#555;">
                                            <?php echo nl2br(htmlspecialchars($msg['contenu'])); ?>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="mailto:<?php echo $msg['email']; ?>?subject=Réponse : <?php echo urlencode($msg['sujet']); ?>" 
                                        class="btn-action btn-edit" 
                                        style="background:#33A7FF;">
                                        <i class='bx bx-reply'></i> Répondre
                                        </a>
                                        
                                        </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php else: ?>
            <p style="text-align:center; margin-top:50px; font-size:1.2em; color:#666;">
                <i class='bx bx-envelope'></i> Aucun message reçu pour le moment.
            </p>
        <?php endif; ?>
    </div>

</body>
</html>