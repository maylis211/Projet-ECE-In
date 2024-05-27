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

// Vérifier si le paramètre GET 'ami' est défini
if (isset($_GET['ami'])) {
    // Récupérer le nom d'utilisateur de l'ami à ajouter
    $ami = $_GET['ami'];

    // Récupérer le nom d'utilisateur de l'utilisateur connecté
    $user_id = $_SESSION['username']; // Assurez-vous d'avoir une variable de session contenant le nom d'utilisateur de l'utilisateur connecté

    // Mettre à jour la demande d'ami dans la table utilisateur
    $sql_update_demande = "UPDATE utilisateur SET demande_ami='$ami' WHERE username='$user_id'";
    if ($conn->query($sql_update_demande) === TRUE) {
        echo "Demande d'ami envoyée à $ami.";
    } else {
        echo "Erreur lors de l'envoi de la demande d'ami: " . $conn->error;
    }
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
                <a href="profil.php?username=<?php echo $utilisateur['username']; ?>"> <!-- Ajout du lien autour de l'image -->
                    <img src="<?php echo $utilisateur['photoProfil']; ?>" alt="<?php echo $utilisateur['username']; ?>" class="profile-pic">
                </a> <!-- Fin du lien -->
                <div class="contact-info">
                    <h2><?php echo $utilisateur['username']; ?></h2>
                    <p><?php echo $utilisateur['description']; ?></p>
                    <!-- Ajout du lien pour envoyer une demande d'ami -->
                    <a href="reseau.php?ami=<?php echo $utilisateur['username']; ?>">Envoyer une demande d'ami</a>
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
