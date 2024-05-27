<?php
session_start();

// Simulation de données de contacts pour l'exemple
$contacts = array(
    array("name" => "Alice Dupont", "description" => "Étudiante en master informatique.", "photo" => "alice.jpg"),
    array("name" => "Bob Martin", "description" => "Enseignant en réseaux et télécommunications.", "photo" => "bob.jpg"),
    array("name" => "Claire Lefevre", "description" => "Développeuse chez XYZ Entreprise.", "photo" => "claire.jpg")
);
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
        <div class="contacts">
            <?php foreach ($contacts as $contact): ?>
                <div class="contact-card">
                    <img src="<?php echo $contact['photo']; ?>" alt="<?php echo $contact['name']; ?>" class="profile-pic">
                    <div class="contact-info">
                        <h2><?php echo $contact['name']; ?></h2>
                        <p><?php echo $contact['description']; ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </main>
    <footer>
        <p>&copy; 2024 ECE In. Tous droits réservés.</p>
    </footer>
</body>
</html>