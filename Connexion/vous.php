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

// Si le formulaire est soumis
if(isset($_POST["submit"])) {
    $username = $_SESSION['username'];
    // Répertoire de destination pour les photos de profil
    $target_dir = "uploads/";
    // Chemin complet du fichier téléchargé
    $target_file = $target_dir . basename($_FILES["photo"]["name"]);
    // Extension du fichier
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    // Vérifier si le fichier est une image réelle
    $check = getimagesize($_FILES["photo"]["tmp_name"]);
    if($check !== false) {
        // Vérifier la taille de l'image
        if ($_FILES["photo"]["size"] > 500000) {
            echo "Désolé, votre fichier est trop volumineux.";
        } else {
            // Déplacer le fichier téléchargé vers le répertoire de destination
            if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
                // Mettre à jour le chemin de la photo de profil dans la base de données
                $sql_update_photo = "UPDATE utilisateur SET photoProfil='$target_file' WHERE username='$username'";
                if ($conn->query($sql_update_photo) === TRUE) {
                    echo "La photo de profil a été mise à jour avec succès.";
                } else {
                    echo "Erreur lors de la mise à jour de la photo de profil: " . $conn->error;
                }
            } else {
                echo "Désolé, une erreur s'est produite lors de l'envoi de votre fichier.";
            }
        }
    } else {
        echo "Le fichier n'est pas une image.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Utilisateur</title>
    <link rel="stylesheet" href="vous.css">
    <style>
        .profile-pic {
            width: 150px; /* Taille de la photo augmentée */
            height: 150px; /* Taille de la photo augmentée */
            border-radius: 50%; /* Rendre la photo ronde */
            display: block;
            margin: 0 auto;
        }
        .user-profile {
            text-align: center; /* Centrer les éléments */
        }
        .user-profile form {
            margin-top: 20px; /* Ajouter de l'espace entre les éléments */
        }
        .user-profile h1 {
            margin-top: 20px; /* Ajouter de l'espace entre les éléments */
        }
    </style>
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
    <?php
    if(isset($_SESSION['username'])) {
        $username = $_SESSION['username'];
        $sql_user = "SELECT * FROM utilisateur WHERE username='$username'";
        $result_user = $conn->query($sql_user);
        if ($result_user->num_rows > 0) {
            $row_user = $result_user->fetch_assoc();
            echo "<h1>" . $row_user['username'] . "</h1>";
            echo '<img src="' . $row_user['photoProfil'] . '" alt="Photo de profil" class="profile-pic">';
            // Formulaire pour uploader une nouvelle photo de profil
            echo '<form action="" method="post" enctype="multipart/form-data">';
            echo '<input type="file" name="photo" accept="image/*"><br>';
            echo '<input type="submit" name="submit" value="Valider"><br>';
            echo '</form>';
            
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
