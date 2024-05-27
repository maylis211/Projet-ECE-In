<?php
session_start();

// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "projet"; // Remplacez "projet" par le nom de votre base de données
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Récupération des utilisateurs pour affichage
$sql = "SELECT username, description, photoProfil FROM utilisateur";
$result = $conn->query($sql);
$utilisateurs = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $utilisateurs[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Réseau - ECE In</title>
    <link rel="stylesheet" href="reseau.css">
</head>
<body>
    <header>
        <div class="logo">
            <img src="logo.jpg" alt="Logo ECE In">
        </div>
        <nav>
            <ul>
                <li><a href="accueil.php">Accueil</a></li>
                <li><a href="reseau.php" class="selected">Mon Réseau</a></li>
                <li><a href="vous.php">Vous</a></li>
                <li><a href="notifications.html">Notifications</a></li>
                <li><a href="messagerie.html">Messagerie</a></li>
                <li><a href="emplois.html">Emplois</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <h1>Mon Réseau</h1>
        <div class="contacts">
            <?php foreach ($utilisateurs as $utilisateur): ?>
            <div class="contact-card">
                <img src="<?php echo $utilisateur['photoProfil']; ?>" alt="<?php echo $utilisateur['username']; ?>" class="profile-pic">
                <div class="contact-info">
                    <h2><?php echo $utilisateur['username']; ?></h2>
                    <p><?php echo $utilisateur['description']; ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </main>
    <footer>
        <p>&copy; 2024 ECE In. Tous droits réservés.</p>
    </footer>
</body>
</html>
