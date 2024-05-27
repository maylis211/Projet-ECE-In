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

// Si le formulaire de création de post est soumis
if (isset($_POST['submit'])) {
    $username = $_SESSION['username'];
    $content = $conn->real_escape_string($_POST['content']);

    $sql_insert_post = "INSERT INTO posts (username, content) VALUES ('$username', '$content')";
    if ($conn->query($sql_insert_post) === TRUE) {
        echo "Post créé avec succès.";
    } else {
        echo "Erreur lors de la création du post: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - ECE In</title>
    <link rel="stylesheet" href="accueil.css">
    <style>
        .profile-pic {
            width: 50px;
            height: 50px;
            border-radius: 50%;
        }
        .create-post, .feed, .post {
            margin: 20px auto;
            max-width: 600px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 10px;
            background-color: #f9f9f9;
        }
        .create-post img {
            display: block;
            margin: 0 auto 10px;
        }
        .post-content {
            margin-left: 70px;
        }
        .post .profile-pic {
            float: left;
            margin-right: 10px;
        }
        .post .timestamp {
            font-size: 0.8em;
            color: #777;
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">
            <a href="accueil.php"><img src="logo.jpg" alt="Logo ECE In"></a>
        </div>
        <nav>
            <ul>
                <li><div class="ongletSelect"><a href="accueil.php">Accueil</a></div></li>
                <li><div class="onglet"><a href="reseau.php">Mon Réseau</a></div></li>
                <li><div class="onglet"><a href="vous.php">Vous</a></div></li>
                <li><div class="onglet"><a href="notifications.html">Notifications</a></div></li>
                <li><div class="onglet"><a href="messagerie.html">Messagerie</a></div></li>
                <li><div class="onglet"><a href="emplois.html">Emplois</a></div></li>
            </ul>
        </nav>
    </header>
    <main>
        <section class="presentation">
            <h1>Bienvenue sur ECE In</h1>
            <p>ECE In est un réseau social professionnel conçu pour les membres de la communauté ECE Paris. Que vous soyez étudiant de licence, master, doctorat, apprenti en entreprise, enseignant ou employé de l'école, ECE In est là pour vous aider à prendre votre vie professionnelle au sérieux.</p>
            <p>Notre plateforme offre un espace où vous pouvez vous connecter avec d'autres professionnels, échanger des idées, rechercher des opportunités de carrière, et bien plus encore.</p>
        </section>
        <section class="event">
            <h2>Évènement de la semaine</h2>
            <p>Restez informé sur les événements importants de la semaine à l'ECE Paris.</p>
            <p>Exemples d'événements : porte ouverte de l'école, séminaire, conférence, etc.</p>
        </section>
        <div class="main-content">
            <div class="left-content">
                <!-- Formulaire de création de post avec la photo de profil de l'utilisateur -->
                <section class="create-post">
                    <h2>Créer un post</h2>
                    <?php
                    if (isset($_SESSION['username'])) {
                        $username = $_SESSION['username'];
                        $sql = "SELECT photoProfil FROM utilisateur WHERE username='$username'";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            $row = $result->fetch_assoc();
                            echo "<img src='".$row['photoProfil']."' alt='Photo de profil' class='profile-pic'>";
                        }
                    }
                    ?>
                    <form method="POST" action="">
                        <textarea name="content" placeholder="Quoi de neuf ?" required></textarea>
                        <button type="submit" name="submit">Publier</button>
                    </form>
                </section>
                <!-- Feed de posts -->
                <section class="feed">
                    <h2>Fil d'actualités</h2>
                    <?php
                    $sql_posts = "SELECT posts.content, posts.created_at, utilisateur.username, utilisateur.photoProfil FROM posts JOIN utilisateur ON posts.username = utilisateur.username ORDER BY posts.created_at DESC";
                    $result_posts = $conn->query($sql_posts);

                    if ($result_posts->num_rows > 0) {
                        while ($row_post = $result_posts->fetch_assoc()) {
                            echo "<div class='post'>";
                            echo "<img src='".$row_post['photoProfil']."' alt='Photo de profil' class='profile-pic'>";
                            echo "<div class='post-content'>";
                            echo "<h3>".$row_post['username']."</h3>";
                            echo "<p>".$row_post['content']."</p>";
                            echo "<span class='timestamp'>".$row_post['created_at']."</span>";
                            echo "</div>";
                            echo "</div>";
                        }
                    } else {
                        echo "<p>Aucun post disponible.</p>";
                    }
                    ?>
                </section>
                <section class="contact">
                <h2>Nous contacter</h2>
                <p>Vous pouvez nous contacter par les moyens suivants :</p>
                <ul>
                    <li><a href="mailto:admin@ecein.com">Email: admin@ecein.com</a></li>
                    <li>Téléphone: +33 1 23 45 67 89</li>
                    <li>Adresse: 10 Rue Sextius Michel, 75015 Paris, France</li>
                </ul>
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2625.816863599406!2d2.2914572156740104!3d48.83747107928404!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47e67161ec115303%3A0x20b44f189d292990!2s10%20Rue%20Sextius%20Michel%2C%2075015%20Paris%2C%20France!5e0!3m2!1sen!2sus!4v1622003223999!5m2!1sen!2sus" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
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
