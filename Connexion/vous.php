<?php
session_start();
// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "projet";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Utilisateur</title>
    <link rel="stylesheet" href="vous.css">
</head>
<body>
<header>
        <div class="logo">
            <img src="logo.jpg" alt="Logo ECE In">
        </div>
        <nav>
            <ul>
                <li><div class="onglet"><a href="accueil.php">Accueil</a></div></li>
                <li><div class="onglet"><a href="mon-reseau.html">Mon Réseau</a></div></li>
                <li><div class="ongletSelect"><a href="vous.php">Vous</a></div></li>
                <li><div class="onglet"><a href="notifications.html">Notifications</a></div></li>
                <li><div class="onglet"><a href="messagerie.html">Messagerie</a></div></li>
                <li><div class="onglet"><a href="emplois.html">Emplois</a></div></li>
            </ul>
        </nav>
    </header>

    <section class="user-profile">
        <h2>Profil de l'utilisateur</h2>
        <?php
        if(isset($_SESSION['username'])) {
            $username = $_SESSION['username'];
            // Requête SQL pour récupérer les informations de l'utilisateur
            $sql_user = "SELECT * FROM utilisateur WHERE username='$username'";
            $result_user = $conn->query($sql_user);
            if ($result_user->num_rows > 0) {
                $row_user = $result_user->fetch_assoc();
                // Affichage de la photo de profil de l'utilisateur
                echo '<img src="' . $row_user['photoProfil'] . '" alt="Photo de profil" style="width: 50px; height: auto; display: block; margin: 0 auto;">';
                // Affichage du nom de l'utilisateur
                echo "<h1>" . $row_user['username'] . "</h1>";
            }
        }
        ?>
    </section>

    <footer>
        <p>&copy; 2024 ECE In. Tous droits réservés.</p>
    </footer>
</body>
</html>

<?php
$conn->close();
?>
