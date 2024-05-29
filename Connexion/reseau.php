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

// Si le formulaire pour envoyer une demande d'ami est soumis
if (isset($_POST["send_friend_request"])) {
    $friend_username = $conn->real_escape_string($_POST['friend_username']);
    $username = $_SESSION['username'];

    // Vérifier si l'utilisateur essaie de s'envoyer une demande d'ami à lui-même
    if ($friend_username === $username) {
        echo "Vous ne pouvez pas vous envoyer une demande d'ami à vous-même.";
    } else {
        // Vérifier si l'utilisateur à ajouter existe
        $sql_check_user = "SELECT username FROM utilisateur WHERE username='$friend_username'";
        $result_check_user = $conn->query($sql_check_user);
        if ($result_check_user->num_rows > 0) {
            // Insérer la demande d'ami dans la base de données
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

// Récupération des utilisateurs qui ne sont pas encore amis avec l'utilisateur connecté
$user_id = $_SESSION['username'];
$sql_non_amis = "SELECT DISTINCT u.username, u.description, u.photoProfil 
        FROM utilisateur u 
        WHERE u.username != '$user_id' 
        AND u.username NOT IN (
            SELECT f.friend_name FROM friends f WHERE f.username = '$user_id'
            UNION
            SELECT f.username FROM friends f WHERE f.friend_name = '$user_id'
            UNION
            SELECT f2.friend_name FROM friends f1 JOIN friends f2 ON f1.friend_name = f2.username WHERE f1.username = '$user_id'
            UNION
            SELECT f1.username FROM friends f1 JOIN friends f2 ON f1.friend_name = f2.username WHERE f2.friend_name = '$user_id'
        )";

$result_non_amis = $conn->query($sql_non_amis);
$utilisateurs_non_amis = [];
if ($result_non_amis->num_rows > 0) {
    while ($row = $result_non_amis->fetch_assoc()) {
        $utilisateurs_non_amis[] = $row;
    }
}

// Récupération des amis de l'utilisateur
$sql_amis = "SELECT u.username, u.description, u.photoProfil 
        FROM utilisateur u 
        JOIN friends f ON u.username = f.friend_name
        WHERE f.username = '$user_id'
        UNION
        SELECT u.username, u.description, u.photoProfil 
        FROM utilisateur u 
        JOIN friends f ON u.username = f.username
        WHERE f.friend_name = '$user_id'";

$result_amis = $conn->query($sql_amis);
$amis = [];
if ($result_amis->num_rows > 0) {
    while ($row = $result_amis->fetch_assoc()) {
        $amis[] = $row;
    }
}

// Récupération des demandes d'amis en attente
$sql_pending_requests = "SELECT u.username, u.photoProfil 
        FROM utilisateur u 
        JOIN friends f ON u.username = f.username 
        WHERE f.friend_name = '$user_id' AND f.status = 'pending'";
$result_pending_requests = $conn->query($sql_pending_requests);
$pending_requests = [];
if ($result_pending_requests->num_rows > 0) {
    while ($row = $result_pending_requests->fetch_assoc()) {
        $pending_requests[] = $row;
    }
}

// Si le formulaire pour accepter une demande d'ami est soumis
if (isset($_POST["accept_friend_request"])) {
    $request_username = $conn->real_escape_string($_POST['request_username']);
    $username = $_SESSION['username'];

    $sql_accept_request = "UPDATE friends SET status='accepted' WHERE username='$request_username' AND friend_name='$username'";
    if ($conn->query($sql_accept_request) === TRUE) {
        echo "Demande d'ami acceptée.";
    } else {
        echo "Erreur lors de l'acceptation de la demande d'ami: " . $conn->error;
    }
}

// Fermer la connexion à la base de données
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
                <li><div class="onglet"><a href="accueil.php">Accueil</a></div></li>
                <li><div class="ongletSelect"><a href="reseau.php">Mon Réseau</a></div></li>
                <li><div class="onglet"><a href="vous.php">Vous</a></div></li>
                <li><div class="onglet"><a href="notifications.html">Notifications</a></div></li>
                <li><div class="onglet"><a href="messagerie.html">Messagerie</a></div></li>
                <li><div class="onglet"><a href="emplois.html">Emplois</a></div></li>
            </ul>
        </nav>
    </header>
    <main>
        <h1>Mon Réseau</h1>
        <!-- Formulaire pour envoyer une demande d'ami -->
        <h2>Envoyer une demande d'ami</h2>
        <form action="" method="post">
            <input type="text" name="friend_username" placeholder="Nom d'utilisateur de l'ami" required><br>
            <input type="submit" name="send_friend_request" value="Envoyer la demande"><br>
        </form>

        <!-- Afficher les demandes d'amis en attente -->
        <h2>Demandes d'amis en attente</h2>
        <?php
        if (count($pending_requests) > 0) {
            foreach ($pending_requests as $request) {
                echo '<div class="pending-request">';
                echo '<img src="' . $request['photoProfil'] . '" alt="Photo de profil de ' . $request['username'] . '" class="profile-pic">';
                echo '<p>' . $request['username'] . '</p>';
                echo '<form action="" method="post">';
                echo '<input type="hidden" name="request_username" value="' . $request['username'] . '">';
                echo '<input type="submit" name="accept_friend_request" value="Accepter">';
                echo '</form>';
                echo '</div>';
            }
        } else {
            echo '<p>Aucune demande d\'ami en attente.</p>';
        }
        ?>

        <!-- Afficher les utilisateurs non amis -->
        <h2>Utilisateurs à ajouter</h2>
        <?php
        if (count($utilisateurs_non_amis) > 0) {
            foreach ($utilisateurs_non_amis as $utilisateur) {
                echo '<div class="user">';
                echo '<img src="' . $utilisateur['photoProfil'] . '" alt="Photo de profil de ' . $utilisateur['username'] . '" class="profile-pic">';
                echo '<p>' . $utilisateur['username'] . '</p>';
                echo '<p>' . $utilisateur['description'] . '</p>';
                echo '<a href="?ami=' . $utilisateur['username'] . '">Ajouter comme ami</a>';
                echo '</div>';
            }
        } else {
            echo '<p>Aucun utilisateur à ajouter.</p>';
        }
        ?>

        <!-- Afficher les amis de l'utilisateur -->
        <h2>Mes amis</h2>
        <?php
        if (count($amis) > 0) {
            foreach ($amis as $ami) {
                echo '<div class="friend">';
                echo '<img src="' . $ami['photoProfil'] . '" alt="Photo de profil de ' . $ami['username'] . '" class="profile-pic">';
                echo '<p>' . $ami['username'] . '</p>';
                echo '<p>' . $ami['description'] . '</p>';
                echo '</div>';
            }
        } else {
            echo '<p>Vous n\'avez aucun ami.</p>';
        }
        ?>
    </main>
</body>
</html>
