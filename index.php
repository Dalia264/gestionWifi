<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "wifi_management";

// Créer une connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Suppression d'un utilisateur
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM recharges WHERE id=$id");
    header('Location: index.php');
}

// Gérer la recherche
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

if ($search) {
    $sql = "SELECT * FROM recharges WHERE nom LIKE '%$search%' OR prenom LIKE '%$search%' ORDER BY date_echeance ASC";
} else {
    $sql = "SELECT * FROM recharges ORDER BY date_echeance ASC";
}

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion de Recharge Wi-Fi</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

<h1>
    <img src="Logo.png" alt="Logo" style="vertical-align: middle; width: 100px; height: 100px;">
    Formule de Recharge du Wi-Fi
</h1>

<!-- Formulaire de recherche -->

<form method="GET" action="index.php" style="display: flex; align-items: center;">
    
    <!-- Champ de saisie avec une icône à l'intérieur -->
    <div style="position: relative; width: 100%;">
        <input type="text" id="search" name="search" placeholder="Entrez un nom ou prénom" 
               value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>" 
               style="padding-left: 30px; width: 100%; box-sizing: border-box;">
        <!-- Icône de recherche -->
        <span style="position: absolute; left: 10px; top: 50%; transform: translateY(-50%); color: #aaa;">
            <i class="fas fa-search"></i>
        </span>
    </div>
    
    <!-- Bouton de soumission -->
    <button type="submit" class="btn-search" style="margin-left: 10px; display: flex; align-items: center;"
    background-color: #ffc107>
        <i class="fas fa-search" style="margin-right: 5px;"></i> Rechercher
    </button>

    <!-- Bouton de réinitialisation -->
    <a href="index.php" class="btn-reset" style="margin-left: 10px;">
    <i class="fas fa-times" style="margin-right: 5px;"></i> Réinitialiser
</a>
    </button>
</a>
</form>

<form action="insert.php" method="POST">
    <label for="nom">Nom:</label>
    <input type="text" id="nom" name="nom" required><br>

    <label for="prenom">Prénom:</label>
    <input type="text" id="prenom" name="prenom" required><br>

    <label for="adresse">Adresse Géographique:</label>
    <input type="text" id="adresse" name="adresse_geographique" required><br>

    <label for="telephone">Téléphone:</label>
    <input type="tel" id="telephone" name="telephone" required><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br>

    <label for="date_recharge">Date de Recharge:</label>
    <input type="date" id="date_recharge" name="date_recharge" required><br>

    <label for="date_echeance">Date d'Échéance:</label>
    <input type="date" id="date_echeance" name="date_echeance" required><br>

    <center> <input type="submit" value="VALIDER"> </center>
</form>

<table>
    <thead>
        <tr>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Adresse Géographique</th>
            <th>Téléphone</th>
            <th>Email</th>
            <th>Date de Recharge</th>
            <th>Date d'Échéance</th>
            <th>Actions</th>
            <th>Notification</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $rows = [];
        while($row = $result->fetch_assoc()) {
            $date_echeance = new DateTime($row['date_echeance']);
            $aujourd_hui = new DateTime();
            $interval = $date_echeance->diff($aujourd_hui);
            $days_left = $interval->days * ($interval->invert == 1 ? 1 : -1);

            $notification = "";
            if ($days_left <= 7 && $days_left >= 0) {
                $notification = '<span class="notification">Échéance dans '.$days_left.' jour'.($days_left > 1 ? 's' : '').'</span>';
            }

            // Ajouter la ligne dans un tableau temporaire avec le nombre de jours restants
            $rows[] = ['row' => $row, 'days_left' => $days_left, 'notification' => $notification];
        }

        // Trier le tableau temporaire par nombre de jours restants en ordre décroissant
        usort($rows, function($a, $b) {
            return $a['days_left'] <=> $b['days_left'];
        });

        // Afficher les résultats triés
        foreach ($rows as $entry): 
            $row = $entry['row'];
            $notification = $entry['notification'];
        ?>
            <tr style="<?php if($notification) echo 'background-color: #fff3cd; color: #856404;'; ?>">
                <td><?php echo $row['nom']; ?></td>
                <td><?php echo $row['prenom']; ?></td>
                <td><?php echo $row['adresse_geographique']; ?></td>
                <td><?php echo $row['telephone']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td><?php echo $row['date_recharge']; ?></td>
                <td><?php echo $row['date_echeance']; ?></td>
                <td>
                    <center>
                        <a href="edit.php?id=<?php echo $row['id']; ?>" class="edit-btn" style="font-size: 13px">
                            <i class="fas fa-pen"></i>
                        </a>
                        <a href="index.php?delete=<?php echo $row['id']; ?>" class="delete-btn" onclick="return confirm('Voulez-vous vraiment supprimer cet élément?');">
                            <i class="fas fa-trash"></i>
                        </a>
                    </center>
                </td>
                <td><?php echo $notification; ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>

<?php
$conn->close();
?>
