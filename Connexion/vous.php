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

// Si le formulaire pour uploader une photo est soumis
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

// Si le formulaire pour ajouter une étape de parcours est soumis
if(isset($_POST["submit_parcours"])) {
    $username = $_SESSION['username'];
    $titre = $conn->real_escape_string($_POST['titre']);
    $description = $conn->real_escape_string($_POST['description']);
    $date_debut = $conn->real_escape_string($_POST['date_debut']);
    $date_fin = $conn->real_escape_string($_POST['date_fin']);
    
    $sql_insert_parcours = "INSERT INTO parcours (username, titre, description, date_debut, date_fin) VALUES ('$username', '$titre', '$description', '$date_debut', '$date_fin')";
    if ($conn->query($sql_insert_parcours) === TRUE) {
        echo "Nouvelle étape de parcours ajoutée avec succès.";
    } else {
        echo "Erreur lors de l'ajout de l'étape de parcours: " . $conn->error;
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
        .timeline {
            max-width: 600px;
            margin: 0 auto;
            padding: 10px;
            list-style-type: none;
            position: relative;
        }
        .timeline:before {
            content: '';
            position: absolute;
            top: 0;
            bottom: 0;
            left: 20px;
            width: 4px;
            background: #ddd;
        }
        .timeline-item {
            margin: 20px 0;
            padding-left: 40px;
            position: relative;
        }
        .timeline-item:before {
            content: '';
            position: absolute;
            top: 10px;
            left: 10px;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: #ddd;
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
            <li><div class="onglet"><a href="reseau.php">Mon Réseau</a></div></li>
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

            // Formulaire pour ajouter une nouvelle étape de parcours
            echo '<h2>Ajouter une étape de parcours</h2>';
            echo '<form action="" method="post">';
            echo '<input type="text" name="titre" placeholder="Titre" required><br>';
            echo '<textarea name="description" placeholder="Description" required></textarea><br>';
            echo '<input type="date" name="date_debut" required><br>';
            echo '<input type="date" name="date_fin" required><br>';
            echo '<input type="submit" name="submit_parcours" value="Ajouter"><br>';
            echo '</form>';

            // Récupérer et afficher la frise chronologique du parcours de l'utilisateur
            $sql_parcours = "SELECT * FROM parcours WHERE username='$username' ORDER BY date_debut ASC";
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
