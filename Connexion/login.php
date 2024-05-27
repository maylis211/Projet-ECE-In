<?php
// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "projet";

// Créer une connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Vérifier si le formulaire de connexion a été soumis
if(isset($_POST['login'])) {
    // Récupérer les données du formulaire
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Requête SQL pour vérifier si l'utilisateur existe dans la base de données
    $sql = "SELECT * FROM utilisateur WHERE username='$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // L'utilisateur existe, donc c'est une connexion
        // Vérifier le mot de passe
        $row = $result->fetch_assoc();
        if ($password == $row['password']) { // Vous devez remplacer cette vérification par une vérification sécurisée, comme le hachage de mot de passe
            echo "Connecté avec succès";
            // Rediriger vers la page d'accueil ou autre page appropriée
        } else {
            echo "Nom d'utilisateur ou mot de passe incorrect";
        }
    } else {
        echo "Utilisateur non trouvé";
    }
}

// Vérifier si le formulaire d'inscription a été soumis
if(isset($_POST['register'])) {
    // Récupérer les données du formulaire
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Requête SQL pour insérer un nouvel utilisateur dans la base de données
    $sql = "INSERT INTO utilisateur (username, password,photoProfil) VALUES ('$username', '$password','profil.png')";
    if ($conn->query($sql) === TRUE) {
        echo "Inscription réussie";
        // Rediriger vers la page d'accueil ou autre page appropriée
    } else {
        echo "Erreur lors de l'inscription: " . $conn->error;
    }
}

// Fermer la connexion à la base de données
$conn->close();
?>
