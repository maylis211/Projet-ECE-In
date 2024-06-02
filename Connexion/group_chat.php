<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// Connexion à la base de données
require_once 'connexion_pdo.php';

// Récupération de l'ID du groupe depuis l'URL
$group_id = isset($_GET['group_id']) ? $_GET['group_id'] : null;


// Récupération des messages du groupe
$stmt = $pdo->prepare("SELECT * FROM group_messages WHERE group_id = ? ORDER BY created_at ASC");
$stmt->execute([$group_id]);
$messages = $stmt->fetchAll();

// Traitement du formulaire d'envoi de message
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['message'])) {
    $content = $_POST['content'];
    $sender_username = $_SESSION['username']; // Assumons que vous stockez le username dans la session

    // Insertion du message dans la base de données
    $stmt = $pdo->prepare("INSERT INTO group_messages (group_id, sender_username, content) VALUES (?, ?, ?)");
    $stmt->execute([$group_id, $sender_username, $content]);

    // Redirection vers la même page pour afficher le nouveau message
    header("Location: group_chat.php?group_id=" . $group_id);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Discussion de Groupe</title>
    <link rel="stylesheet" href="style.css">
    <style>
    #chatbox {
    height: 300px; /* Ajustez la hauteur selon vos besoins */
    overflow-y: auto; /* Active le défilement lorsque nécessaire */
    border: 1px solid #ccc; /* Ajoute une bordure */
    padding: 10px; /* Ajoute de la mise en page interne */
    margin-bottom: 10px; /* Ajoute de l'espace sous la chatbox */
    border-radius: 10px; /* Arrondit les coins de la chatbox */
    background: lightgray;
    color: #fff; /* Change la couleur du texte en blanc *
}
#wrapper { margin: 0 auto; padding-bottom: 25px; background: linear-gradient(90deg, rgba(7,109,121,1) 0%, rgba(9,152,169,1) 98%);; width: 600px; max-width: 100%; border: 2px solid #212121; border-radius: 4px; }

.chat-message {
    display: flex;
    align-items: flex-end;
    margin-bottom: 10px; /* Ajoute de l'espace entre les messages */

}

.chat-bubble {
    padding: 10px; /* Ajoute de la mise en page interne */
    border-radius: 10px; /* Arrondit les coins */
    max-width: 70%; /* Définit la largeur maximale */
    background-color: #fff; /* Garder la bulle de chat en blanc */
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1); /* Ajoute un effet d'ombre */
    background: linear-gradient(90deg, rgba(7,109,121,1) 0%, rgba(9,152,169,1) 98%);
}

.user-message {
    margin-left: auto; /* Aligne à droite */
    color: #fff; /* Change la couleur du texte en blanc */

}

.friend-message {
    margin-right: auto; /* Aligne à gauche */
}

.chat-time {
    font-size: 0.8em; /* Réduit la taille de la police pour l'heure */
    color: #777; /* Change la couleur du texte pour l'heure */
    margin-left: 5px; /* Ajoute un espace entre le message et l'heure */
}

/* Styles pour le formulaire d'envoi de message */
#content {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    margin-bottom: 10px;
    background: lightgray;
}


#submitmsg {
    padding: 10px;
    border: none;
    border-radius: 5px;
    background-color: #088897;
    color: white;
    cursor: pointer;
}

#submitmsg:hover {
    background-color: #056672;
}

/* Styles pour le bouton de retour à l'accueil */
.back-to-home {
    padding: 10px;
    border: none;
    border-radius: 5px;
    background-color: #088897;
    color: white;
    cursor: pointer;
    margin-top: 10px;
    color: #fff; /* Change la couleur du texte en blanc */
}

.back-to-home:hover {
    background-color: #056672;
}
        /* Assurez-vous que le menu est suffisamment large pour contenir le bouton */
        #menu {
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;

        }

    header {
        background: linear-gradient(90deg, rgba(7,109,121,1) 0%, rgba(9,152,169,1) 98%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 20px;
        margin-bottom: 30px;
    }

    .logo img {
        height: 50px; /* Ajustez la taille du logo selon vos besoins */
    }

    nav ul {
        list-style-type: none;
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: flex-end;

    }

    nav ul li {
        margin: 0 15px;

    }

    nav ul li a {
        color: white;
        text-decoration: none;
        font-size: 16px;

    }

    .onglet {
        padding: 8px 12px;
        margin: 0 5px;
        border-radius: 5px;
        transition: background-color 0.3s, border-bottom 0.3s;
    }

    .onglet:hover {
        box-shadow: 12px 12px 72px -23px rgba(41,49,56,0.9) inset;
    }

    .ongletSelect {
        font-weight: bold; /* Met en gras le texte de l'onglet */
        color: #088897; /* Change la couleur du texte de l'onglet */
        border-bottom: 2px solid #088897; /* Ajoute une bordure en bas de l'onglet */
        background-color: #088897; /* Ajoute un arrière-plan gris clair */
        border-radius: 5px; /* Arrondit les coins de l'onglet */
        padding: 8px 12px; /* Ajoute de l'espace à l'intérieur de l'onglet */
        margin: 0 5px; /* Ajoute un espace entre les onglets */
        box-shadow: 12px 12px 72px -23px rgba(41,49,56,0.9) inset;
        transition: background-color 0.3s; /* Ajoute une transition fluide lors du survol */
    }
    nav ul {
    list-style-type: none;
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: flex-end;

}

nav ul li {
    margin: 0 15px;
}

nav ul li a {
    color: white;
    text-decoration: none;
    font-size: 16px;

}

    </style>
</head>
<body>
    <header>
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
                <li><div class="ongletSelect"><a href="messagerie.html">Messagerie</a></div></li>
                <li><div class="onglet"><a href="emplois.html">Emplois</a></div></li>
            </ul>
        </nav>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

    </header>
    </header>
    
    <div id="wrapper">
        <div id="chatbox">
            <?php foreach ($messages as $message): ?>
                <div class="chat-message">
                    <div class="chat-bubble <?php echo ($message['sender_username'] == $_SESSION['username']) ? 'user-message' : 'friend-message'; ?>">
                        <?php echo htmlspecialchars($message['content']); ?>
                        <span class="chat-time"><?php echo date("g:i A", strtotime($message['created_at'])); ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <form name="message" action="" method="post">
            <label for="content">Message:</label>
            <textarea id="content" name="content" required></textarea><br><br>
            <input type="submit" name="message" id="submitmsg" value="Envoyer">

        </form>
                    <form action="home.php">
                <button type="submit">Retour à l'accueil</button>
            </form>
    </div>
</body>
</html>