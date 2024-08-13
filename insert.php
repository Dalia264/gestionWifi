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

// Préparer et lier
$stmt = $conn->prepare("INSERT INTO recharges (nom, prenom, adresse_geographique, telephone, email, date_recharge, date_echeance) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssss", $nom, $prenom, $adresse_geographique, $telephone, $email, $date_recharge, $date_echeance);

// Récupérer les données du formulaire
$nom = $_POST['nom'];
$prenom = $_POST['prenom'];
$adresse_geographique = $_POST['adresse_geographique'];
$telephone = $_POST['telephone'];
$email = $_POST['email'];
$date_recharge = $_POST['date_recharge'];
$date_echeance = $_POST['date_echeance'];

// Exécuter la requête
$stmt->execute();

$stmt->close();
$conn->close();

header("Location: index.php"); // Rediriger vers l'index après l'insertion
exit();
?>
