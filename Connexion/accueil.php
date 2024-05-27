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
    <title>Accueil - ECE In</title>
    <link rel="stylesheet" href="accueil.css">
</head>
<body>
    <header>
        <div class="logo">
            <img src="profil.jpg" alt="Logo ECE In">
        </div>
        <nav>
            <ul>
                <li><a href="index.html">Accueil</a></li>
                <li><a href="mon-reseau.html">Mon Réseau</a></li>
                <li><a href="vous.html">Vous</a></li>
                <li><a href="notifications.html">Notifications</a></li>
                <li><a href="messagerie.html">Messagerie</a></li>
                <li><a href="emplois.html">Emplois</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <section class="presentation">
            <h1>Bienvenue sur ECE In</h1>
            <p>Votre réseau social professionnel pour la communauté ECE Paris.</p>
        </section>
        <div class="main-content">
            <div class="left-content">
                <!-- Formulaire de création de post avec la photo de profil de l'utilisateur -->
                <section class="create-post">
                    <h2>Créer un post</h2>
                    <?php
                    if(isset($_SESSION['username'])) {
                        $username = $_SESSION['username'];
                        $sql = "SELECT photoProfil FROM utilisateur WHERE username='$username'";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            $row = $result->fetch_assoc();
                            echo '<div class="logo"><img src="'.$row['photoProfil'].'" alt="Photo de profil" style="width: 50px; height: auto;"></div>';
                        }
                    }
                    ?>
                    <form method="POST" action="">
                        <textarea placeholder="Quoi de neuf ?"></textarea>
                        <button type="submit" name="submit">Publier</button>
                    </form>
                </section>
                <!-- Feed de posts -->
                <section class="feed">
                    <h2>Fil d'actualités</h2>
                    <!-- Afficher les posts ici -->
                    <div class="post">
                        <p>Post 1: Contenu du post...</p>
                    </div>
                    <div class="post">
                        <p>Post 2: Contenu du post...</p>
                    </div>
                    <!-- Ajoutez plus de posts ici -->
                </section>
            </div>
        </div>
    </main>
    <footer>
        <p>&copy; 2024 ECE In. Tous droits réservés.</p>
    </footer>
    <script src="accueil.js"></script>
</body>
</html>
<?php
$conn->close();
?>
