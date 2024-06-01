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


$username = $_SESSION['username'];
// Si le formulaire pour envoyer une demande d'ami est soumis
if (isset($_POST["send_friend_request"])) {
    $friend_username = $conn->real_escape_string($_POST['friend_username']);

    // Vérifier si l'utilisateur essaie de s'envoyer une demande d'ami à lui-même
    if ($friend_username === $username) {
        echo "Vous ne pouvez pas vous envoyer une demande d'ami à vous-même.";
    } else {
        // Vérifier si l'utilisateur à ajouter existe
        $sql_check_user = "SELECT username FROM utilisateur WHERE username='$friend_username'";
        $result_check_user = $conn->query($sql_check_user);
        if ($result_check_user->num_rows > 0) {
            // Insérer la demande d'ami dans la table friends
            $sql_send_request = "INSERT INTO friends (username, friend_name, status) VALUES ('$username', '$friend_username', 'pending')";
            if ($conn->query($sql_send_request) === TRUE) {
                echo "Demande d'ami envoyée avec succès.";
            } else {
                echo "Erreur lors de l'envoi de la demande d'ami: " . $conn->error;
            }
        } else {
            echo "Utilisateur non trouvé.";
        }
    }
}


// Récupération des informations de l'utilisateur
$user_id = $_GET['username']; // Assurez-vous que c'est sécurisé
$sql_check_friendship = "SELECT * FROM friends WHERE (username = '$user_id' AND friend_name = '$user_id') OR (username = '$user_id' AND friend_name = '$user_id' AND status = 'accepted')";
$result_check_friendship = $conn->query($sql_check_friendship);
$is_friend = $result_check_friendship->num_rows > 0;

$sql_check_public = "SELECT profil_public FROM utilisateur WHERE username = '$user_id'";
$result_check_public = $conn->query($sql_check_public);
$profil_public = $result_check_public->fetch_assoc()['profil_public'];

$sql_user_info = "SELECT username, description, photoProfil, cv FROM utilisateur WHERE username = '$user_id'";
$result_user_info = $conn->query($sql_user_info);
if ($result_user_info->num_rows > 0) {
    $user_info = $result_user_info->fetch_assoc();
    $cv_path = $user_info['cv']; // Chemin vers le CV
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
            <li><div class="onglet"><a href="notifications.php">Notifications</a></div></li>
            <li><div class="onglet"><a href="messagerie.html">Messagerie</a></div></li>
            <li><div class="onglet"><a href="emplois.html">Emplois</a></div></li>
        </ul>
    </nav>
</header>
<main class="container">
<div class="profil-info">
    <?php if ($profil_public || $is_friend) : ?>
        <img src="<?php echo $user_info['photoProfil']; ?>" alt="<?php echo $user_info['username']; ?>" class="profile-pic">
        <h1><?php echo $user_info['username']; ?></h1>
        <p><?php echo $user_info['description']; ?></p>
        <?php if (isset($cv_path)) : ?>
            <h2>CV<h2>
            <iframe src="<?php echo htmlspecialchars($cv_path); ?>" width="40%" height="700px" frameborder="0"></iframe>
        <?php endif; ?>
    <?php else : ?>
        <img src="<?php echo $user_info['photoProfil']; ?>" alt="<?php echo $user_info['username']; ?>" class="profile-pic">
        <h1><?php echo $user_info['username']; ?></h1>
        <p><?php echo $user_info['description']; ?></p>
        <form action="" method="post">
            <input type="hidden" name="friend_username" value="<?php echo $user_info['username']; ?>">
            <input type="submit" name="send_friend_request" value="Ajouter comme ami">
        </form>
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
