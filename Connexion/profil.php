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

$current_username = $_SESSION['username'];

// Si le formulaire pour envoyer une demande d'ami est soumis
if (isset($_POST["send_friend_request"])) {
    $friend_username = $conn->real_escape_string($_POST['friend_username']);

    if ($friend_username === $current_username) {
        echo "Vous ne pouvez pas vous envoyer une demande d'ami à vous-même.";
    } else {
        $sql_check_user = "SELECT username FROM utilisateur WHERE username='$friend_username'";
        $result_check_user = $conn->query($sql_check_user);
        if ($result_check_user->num_rows > 0) {
            $sql_send_request = "INSERT INTO friends (username, friend_name, status) VALUES ('$current_username', '$friend_username', 'pending')";
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
$profile_username = $_GET['username'];
$is_friend = false;
$profil_public = false;

$sql_check_friendship = "SELECT * FROM friends WHERE 
                         (username = '$current_username' AND friend_name = '$profile_username' AND status = 'accepted') 
                         OR (username = '$profile_username' AND friend_name = '$current_username' AND status = 'accepted')";
$result_check_friendship = $conn->query($sql_check_friendship);
if ($result_check_friendship->num_rows > 0) {
    $is_friend = true;
}

$sql_check_public = "SELECT profil_public FROM utilisateur WHERE username = '$profile_username'";
$result_check_public = $conn->query($sql_check_public);
if ($result_check_public->num_rows > 0) {
    $profil_public = $result_check_public->fetch_assoc()['profil_public'];
}

$sql_user_info = "SELECT username, description, photoProfil, cv FROM utilisateur WHERE username = '$profile_username'";
$result_user_info = $conn->query($sql_user_info);
if ($result_user_info->num_rows > 0) {
    $user_info = $result_user_info->fetch_assoc();
    $cv_path = $user_info['cv'];
}

$sql_user_posts = "SELECT content, created_at FROM posts WHERE username = '$profile_username' ORDER BY created_at DESC";
$result_user_posts = $conn->query($sql_user_posts);
$user_posts = [];
if ($result_user_posts->num_rows > 0) {
    while ($row = $result_user_posts->fetch_assoc()) {
        $user_posts[] = $row;
    }
}

// Générer le fichier XML et le fichier HTML
if (isset($_POST["generate_html"])) {
    // Récupérer les informations de l'utilisateur depuis la base de données
    $sql_user = "SELECT * FROM utilisateur WHERE username='$profile_username'";
    $result_user = $conn->query($sql_user);
    $row_user = $result_user->fetch_assoc();
    
    $sql_experience = "SELECT * FROM parcours WHERE username='$profile_username'";
    $result_experience = $conn->query($sql_experience);
    
    $sql_education = "SELECT * FROM projet WHERE username='$profile_username'";
    $result_education = $conn->query($sql_education);

    // Commencer à construire le fichier XML
    $xml = new SimpleXMLElement('<cv/>');
    $user_xml = $xml->addChild('utilisateur');
    $user_xml->addChild('username', $row_user['username']);
    $user_xml->addChild('email', $row_user['email']);
    $user_xml->addChild('photoProfil', $row_user['photoProfil']);

    $experiences_xml = $user_xml->addChild('parcours');
    while ($row_experience = $result_experience->fetch_assoc()) {
        $experience_xml = $experiences_xml->addChild('experience');
        $experience_xml->addChild('titre', $row_experience['titre']);
        $experience_xml->addChild('date_debut', $row_experience['date_debut']);
        $experience_xml->addChild('date_fin', $row_experience['date_fin']);
        $experience_xml->addChild('description', $row_experience['description']);
    }

    $education_xml = $user_xml->addChild('projets');
    while ($row_education = $result_education->fetch_assoc()) {
        $education_entry_xml = $education_xml->addChild('entry');
        $education_entry_xml->addChild('titre', $row_education['titre']);
        $education_entry_xml->addChild('date_debut', $row_education['date_debut']);
        $education_entry_xml->addChild('date_fin', $row_education['date_fin']);
        $education_entry_xml->addChild('description', $row_education['description']);
    }

    // Sauvegarder le fichier XML sur le serveur
    $xml_file = "cv_xml/" . $profile_username . "_cv.xml";
    $xml->asXML($xml_file);

    // Générer le fichier HTML à partir du XML
    if (file_exists($xml_file)) {
        $xml = simplexml_load_file($xml_file);
        
        $html = '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CV de ' . $xml->utilisateur->username . '</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .container { width: 80%; margin: 0 auto; }
        h1, h2 { color: #333; }
        .section { margin-bottom: 20px; }
        .section h2 { border-bottom: 2px solid #333; padding-bottom: 5px; }
        .experience, .projet { margin-bottom: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>CV de ' . $xml->utilisateur->username . '</h1>
        <img src="../' . $xml->utilisateur->photoProfil . '" alt="Photo de profil" class="profile-pic" width="100" height="100">
        <div class="section">
            <h2>Informations Personnelles</h2>
            <p><strong>Nom d\'utilisateur:</strong> ' . $xml->utilisateur->username . '</p>
            <p><strong>Email:</strong> ' . $xml->utilisateur->email . '</p>
        </div>
        <div class="section">
            <h2>Parcours</h2>';

        foreach ($xml->utilisateur->parcours->experience as $experience) {
            $html .= '<div class="experience">
                <h3>' . $experience->titre . '</h3>
                <p><strong>Date de début:</strong> ' . $experience->date_debut . '</p>
                <p><strong>Date de fin:</strong> ' . $experience->date_fin . '</p>
                <p>' . $experience->description . '</p>
            </div>';
        }

        $html .= '</div>
        <div class="section">
            <h2>Projets</h2>';

        foreach ($xml->utilisateur->projets->entry as $projet) {
            $html .= '<div class="projet">
                <h3>' . $projet->titre . '</h3>
                <p><strong>Date de début:</strong> ' . $projet->date_debut . '</p>
                <p><strong>Date de fin:</strong> ' . $projet->date_fin . '</p>
                <p>' . $projet->description . '</p>
            </div>';
        }

        $html .= '</div>
    </div>
</body>
</html>';

        $html_file = "cv_html/" . $profile_username . "_cv.html";
        file_put_contents($html_file, $html);

        echo "Fichier HTML généré avec succès. <a href='$html_file' target='_blank'>Voir le fichier HTML</a>";
    } else {
        echo "Le fichier XML n'existe pas.";
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
        <a href="accueil.php"><img src="logo.jpg" alt="Logo ECE In"></a>
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
    <?php if ($profil_public || $is_friend || $current_username == $profile_username) : ?>
        <img src="<?php echo $user_info['photoProfil']; ?>" alt="<?php echo $user_info['username']; ?>" class="profile-pic">
        <h1><?php echo $user_info['username']; ?></h1>
        <p><?php echo $user_info['description']; ?></p>
        <form action="" method="post">
            <input type="submit" name="generate_html" value="Générer le CV en HTML">
        </form>
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
