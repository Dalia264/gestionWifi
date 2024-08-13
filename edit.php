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

// Vérifier si l'identifiant est passé dans l'URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];
//php sostorm dit que ta version de php est très ancienne mais il préfere rester sur ta version; Il ne peux pas s'y opposer; cepandant il te recommande d'installer la version au moins 7 de php

    // en haut il analyse ton code
    // s'il détecte des erreurs critiqus il va mettre en rouge, des erreurs moins critiques en orange pour dire danger et les erreurs d'hothographe en vert mais ca c'est pas un pb

    // Sélectionner les données de l'utilisateur à éditer
    $sql = "SELECT * FROM recharges WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    // tu vois il te dis même les bêtises que tu fais
    // Alida comment tu peux me faire ca ?
    //ma requete marchait, je sais pas ce que j'ai fait de mal moi
    //php storm s'en fou si ton code marche ou pas seulement il voudrait voir un code clean, propore
    // Maais bon au niveaude la variable sql il dit de configurer larequête dans sql dialect
    // Pour qu'il dialogue avec ta base de données mais bon je vais te montrer comment on l'utilise

    // Quand j'ai fait git init tu as certainement vu que tous tes fichiers sont  en rouge à gauche de ton écran
    //Oui
    // Super; le professeur te dis de ne pas penser que c'est une erreur je vais t'expliquer ca en d'tails plus tard
    // maintenant je vais taper une commande dans la console
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Vérifier si le formulaire a été soumis
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $adresse_geographique = $_POST['adresse_geographique'];
        $telephone = $_POST['telephone'];
        $email = $_POST['email'];
        $date_recharge = $_POST['date_recharge'];
        $date_echeance = $_POST['date_echeance'];

        // Mettre à jour les données de l'utilisateur
        $update_sql = "UPDATE recharges SET nom = ?, prenom = ?, adresse_geographique = ?, telephone = ?, email = ?, date_recharge = ?, date_echeance = ? WHERE id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("sssssssi", $nom, $prenom, $adresse_geographique, $telephone, $email, $date_recharge, $date_echeance, $id);
        $update_stmt->execute();

        // Rediriger vers la page d'accueil après la mise à jour
        header('Location: index.php');
    }
} else {
    // Rediriger si aucun ID n'est passé
    header('Location: index.php');
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Recharge Wi-Fi</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <h1><img src="Logo.png" alt="Logo" style="vertical-align: middle; width: 100px; height: 100px;">
    Modifier les Détails de Recharge du Wi-Fi</h1>

    <form action="edit.php?id=<?php echo $id; ?>" method="POST">
        <label for="nom">Nom:</label>
        <input type="text" id="nom" name="nom" value="<?php echo $user['nom']; ?>" required><br>

        <label for="prenom">Prénom:</label>
        <input type="text" id="prenom" name="prenom" value="<?php echo $user['prenom']; ?>" required><br>

        <label for="adresse">Adresse Géographique:</label>
        <input type="text" id="adresse" name="adresse_geographique" value="<?php echo $user['adresse_geographique']; ?>" required><br>

        <label for="telephone">Téléphone:</label>
        <input type="tel" id="telephone" name="telephone" value="<?php echo $user['telephone']; ?>" required><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo $user['email']; ?>" required><br>

        <label for="date_recharge">Date de Recharge:</label>
        <input type="date" id="date_recharge" name="date_recharge" value="<?php echo $user['date_recharge']; ?>" required><br>

        <label for="date_echeance">Date d'Échéance:</label>
        <input type="date" id="date_echeance" name="date_echeance" value="<?php echo $user['date_echeance']; ?>" required><br>

        <center><input type="submit" value="Mettre à jour"></center>
    </form>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
