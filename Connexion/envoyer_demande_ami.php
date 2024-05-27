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
    // Récupérer l'identifiant de l'utilisateur connecté à partir de la session
    $username = $_SESSION['username']; // Assurez-vous d'avoir une variable de session contenant l'identifiant de l'utilisateur connecté
    
    // Récupérer l'identifiant de l'ami à partir du paramètre GET
    $ami_id = $_GET['ami']; // Supposons que 'ami' contient l'identifiant de l'ami
    
    // Insérer une nouvelle demande d'ami dans la table demandes_ami
    $sql_insert_demande = "INSERT INTO demandes_ami (sender_id, receiver_id) VALUES ('$username', '$ami_id')";
    
    if ($conn->query($sql_insert_demande) === TRUE) {
        // Afficher un message de confirmation
        echo "Demande d'ami envoyée avec succès à l'utilisateur $ami_id.";
    } else {
        // En cas d'erreur lors de l'insertion de la demande d'ami
        echo "Erreur lors de l'envoi de la demande d'ami: " . $conn->error;
    }
} else {
    // Si le paramètre GET 'ami' n'est pas défini, l'utilisateur est redirigé vers une page d'erreur ou une autre page appropriée
    
    // Par exemple, vous pouvez afficher un message indiquant que la page demandée n'a pas été trouvée
    echo "La page demandée n'a pas été trouvée.";
}

// Fermer la connexion à la base de données
$conn->close();
?>
