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

// Récupération des informations de l'utilisateur
$user_id = $_GET['username']; // Assurez-vous que c'est sécurisé
$sql_user_info = "SELECT username, description, photoProfil FROM utilisateur WHERE username = '$user_id'";
$result_user_info = $conn->query($sql_user_info);
if ($result_user_info->num_rows > 0) {
    $user_info = $result_user_info->fetch_assoc();
}

// Récupération des posts de l'utilisateur
$sql_user_posts = "SELECT content, created_at FROM posts WHERE username = '$user_id' ORDER BY created_at DESC";
$result_user_posts = $conn->query($sql_user_posts);
$user_posts = [];
if ($result_user_posts->num_rows > 0) {
    while ($row = $result_user_posts->fetch_assoc()) {
        $user_posts[] = $row;
    }
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil - <?php echo $user_info['username']; ?></title>
    <link rel="stylesheet" href="profil.css">
</head>
<body>
<header>
    <div class="logo">
        <a href=accueil.php><img src="logo.jpg" alt="Logo ECE In"></a>
    </div>
    <nav>
        <ul>
            <li><div class="onglet"><a href="accueil.php">Accueil</a></div></li>
            <li><div class="onglet"><a href="reseau.php">Mon Réseau</a></div></li>
            <li><div class="onglet"><a href="vous.php">Vous</a></div></li>
            <li><div class="onglet"><a href="notifications.html">Notifications</a></div></li>
            <li><div class="onglet"><a href="messagerie.html">Messagerie</a></div></li>
            <li><div class="onglet"><a href="emplois.html">Emplois</a></div></li>
        </ul>
    </nav>
</header>
<main class="container">
    <div class="profil-info">
        <img src="<?php echo $user_info['photoProfil']; ?>" alt="<?php echo $user_info['username']; ?>" class="profile-pic">
        <h1><?php echo $user_info['username']; ?></h1>
        <p><?php echo $user_info['description']; ?></p>
    </div>
    <div class="parcours">
        <?php
        // Récupérer et afficher la frise chronologique du parcours de l'utilisateur
        $sql_parcours = "SELECT * FROM parcours WHERE username='$user_id' ORDER BY date_debut ASC";
        $result_parcours = $conn->query($sql_parcours);
        if ($result_parcours->num_rows > 0) {
            echo "<h2>Parcours</h2>";
            echo '<ul class="timeline">';
            while($row_parcours = $result_parcours->fetch_assoc()) {
                echo '<li class="timeline-item">';
                echo '<h3>' . $row_parcours['titre'] . '</h3>';
                echo '<p>' . $row_parcours['description'] . '</p>';
                echo '<span>' . $row_parcours['date_debut'] . ' - ' . $row_parcours['date_fin'] . '</span>';
                echo '</li>';
            }
            echo '</ul>';
        } else {
            echo "<p>Aucun parcours disponible.</p>";
        }
        ?>
    </div>
    <div class="user-posts">
        <h2>Posts de <?php echo $user_info['username']; ?></h2>
        <?php if (!empty($user_posts)): ?>
            <?php foreach ($user_posts as $post): ?>
                <div class="post">
                    <p><?php echo $post['content']; ?></p>
                    <span><?php echo $post['created_at']; ?></span>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Aucun post disponible pour le moment.</p>
        <?php endif; ?>
    </div>
</main>
<footer>
    <p>&copy; 2024 ECE In. Tous droits réservés.</p>
</footer>
</body>
</html>
<?php
$conn->close();
?>