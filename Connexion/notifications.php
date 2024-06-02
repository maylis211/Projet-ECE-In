   

<?php
//L'utilisateur est sur sa session
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
?>
    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <style>
         body {
            font-family: 'Arial', sans-serif;
            background-color: #fff;
            margin: 0;
            padding: 0;
        }
        header {
    background: linear-gradient(90deg, rgba(7,109,121,1) 0%, rgba(9,152,169,1) 98%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px 20px;
}
.logo img {
    height: 50px; /* Ajustez la taille du logo selon vos besoins */
}
h2 {
    color: #076d79;
    text-align: center; /* Centrer les titres */
}
nav ul {
    list-style-type: none;
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: flex-end;
}
footer {
    background-color: #076d79;
    color: white;
    text-align: center;
    padding: 10px 0;
    position: fixed;
    width: 100%;
    bottom: 0;
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
main {
    padding: 20px;
}
button {
    padding: 10px 20px;
    background-color: #076d79;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}


        .container {
            margin: 20px auto;
            display: flex;
            flex-direction: column;
            gap: 15px;
            background: linear-gradient(90deg, rgba(7,109,121,1) 0%, rgba(9,152,169,1) 98%);
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 20px;
            height: 700px;
            width: 80%;
            max-width: 800px;
        }
        .section {
            margin-top: 0px;
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 10px;
            background-color: lightgrey;
            color: black;
            height: 550px;

        }
        .section-title {
            font-size: 20px;
           
            font-weight: bold;
            margin-bottom: 15px;
            margin-top: 15px;
            color: linear-gradient(90deg, rgba(7,109,121,1) 0%, rgba(9,152,169,1) 98%);
        }
        .feed {
            margin-top: 10px;   
            list-style-type: none;
            padding: 0;
            overflow-y: auto;
            max-height: 450px;
            margin-bottom: 30px;
            color: linear-gradient(90deg, rgba(7,109,121,1) 0%, rgba(9,152,169,1) 98%);
        
        }
        .feed-item {
            border: 1px solid #e9e9e9;
            border-bottom: 1px solid #ccc;
            margin-bottom: 20px;
            padding: 2px;
            max-height: 100%;
            background-color: white;
            border-radius: 8px;
            width: 780px; /* Largeur fixe de 600px */
            max-width: 100%; /* Assure que la largeur ne dépasse pas 100% de la largeur du conteneur parent */
        }
        .onglet {
    padding: 8px 12px;
    margin: 0 5px;
    border-radius: 5px;
    transition: background-color 0.3s, border-bottom 0.3s;
}

.onglet:hover {
    box-shadow: 12px 12px 72px -23px rgba(41,49,56,0.9) inset;
    -webkit-box-shadow: 12px 12px 72px -23px rgba(41,49,56,0.9) inset;
    -moz-box-shadow: 12px 12px 72px -23px rgba(41,49,56,0.9) inset;}

.ongletSelect {
    font-weight: bold; /* Met en gras le texte de l'onglet */
    color: #088897; /* Change la couleur du texte de l'onglet */
    border-bottom: 2px solid #088897; /* Ajoute une bordure en bas de l'onglet */
    background-color: #088897; /* Ajoute un arrière-plan gris clair */
    border-radius: 5px; /* Arrondit les coins de l'onglet */
    padding: 8px 12px; /* Ajoute de l'espace à l'intérieur de l'onglet */
    margin: 0 5px; /* Ajoute un espace entre les onglets */
    box-shadow: 12px 12px 72px -23px rgba(41,49,56,0.9) inset;
    -webkit-box-shadow: 12px 12px 72px -23px rgba(41,49,56,0.9) inset;
    -moz-box-shadow: 12px 12px 72px -23px rgba(41,49,56,0.9) inset;
    transition: background-color 0.3s; /* Ajoute une transition fluide lors du survol */
    
}


    </style>
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
                <li><div class="ongletSelect"><a href="notifications.php">Notifications</a></div></li>
                <li><div class="onglet"><a href="home.php">Messagerie</a></div></li>
                <li><div class="onglet"><a href="emplois.php">Emplois</a></div></li>
            </ul>
        </nav>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

    </header>
 
<div class="container text-center">
    <div class="button-container sticky-top">
        <div class="btn-group" role="group" aria-label="Basic example">
            <button type="button" onclick="showEvents()" class="onglet">Événements</button>
            <button type="button" onclick="showFriendPosts()" class="onglet">Abonnements</button>
            <button type="button" onclick="showFriendsOfFriends()" class="onglet">Recommandations</button>
            <button type="button" onclick="showPartners()" class="onglet">Partenaires</button>
        </div>
    </div>
    <div class="feed-container">
        <div id="eventsSection" class="section">
            <h2 class="section-title">Événements</h2>
            <ul class="feed">
                
                <?php
                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname = "projet";

                $conn = new mysqli($servername, $username, $password, $dbname);

                if ($conn->connect_error) {
                    die("Échec de la connexion à la base de données: " . $conn->connect_error);
                }

                $sql = "SELECT * FROM `evenements` ORDER BY Date DESC";
                $result = $conn->query($sql);
                while ($row = $result->fetch_assoc()) {
                    if ($row["Utilisateur"] == "ECE" || $row["Utilisateur"] == "Omnes Education") {
                        echo "<div class='feed-item'>";
                        echo "<div class='content'>";
                        echo "<div class='info-container'>";
                        echo "<h2 class='creator'>" . $row["Utilisateur"] . "</h2>"; // Utilisateur créateur à gauche
                        echo "<span class='date'>" . $row["Date"] . "</span>"; // Date à droite
                        echo "</div>";
                        echo "<h3>" . $row["NomEvent"] . "</h3>";
                        echo "<p>" . $row["Description"] . "</p>";
                        echo "<div class='image'><img src='" . $row["Image"] . "' alt='Image de l'événement'></div>";
                        echo "</div>";
                        echo "</div>";
                        echo "<hr>";                
                    }
                }

                $conn->close();
                ?>
            </ul>
        </div>
        <div id="friendPostsSection" class="section" style="display: none;">
            <h2 class="section-title">Feed de vos amis</h2>
            <ul class="feed">
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

                    // Vérifier si l'utilisateur est connecté
                    if (isset($_SESSION['username'])) {
                        $username = $_SESSION['username']; // Utilisateur connecté

                        // Requête pour récupérer les événements des amis de l'utilisateur, triés par date décroissante
                        $sql = "SELECT e.NomEvent, e.Date, e.Image, e.Description, e.status, e.Utilisateur FROM evenements e 
                                JOIN friends f ON e.Utilisateur = f.friend_name
                                WHERE f.username = '$username' AND f.status = 'accepted'
                                UNION
                                SELECT e.NomEvent, e.Date, e.Image, e.Description, e.status, e.Utilisateur FROM evenements e
                                JOIN friends f ON e.Utilisateur = f.username
                                WHERE f.friend_name = '$username' AND f.status = 'accepted'
                                ORDER BY DATE DESC";
                            

                        // Exécuter la requête
                        $result = $conn->query($sql);

                        // Afficher les événements si des résultats sont disponibles
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                $nomEvent = htmlspecialchars($row['NomEvent'] ?? '');
                                $dateEvent = htmlspecialchars($row['Date'] ?? '');
                                $description = htmlspecialchars($row['Description'] ?? '');
                                $status = htmlspecialchars($row['status'] ?? '');
                                $image = $row['Image'] ? htmlspecialchars($row['Image']) : 'noimage.jpg'; // Utiliser une image par défaut si aucune n'est spécifiée
                                $utilisateur = $row['Utilisateur'] ?? ''; // Utilisateur créateur

                                echo "<div class='feed-item'>";
                                echo "<div class='content'>";
                                echo "<div class='info-container'>";
                                echo "<h2 class='creator'>" . htmlspecialchars($utilisateur) . "</h2>"; // Utilisateur créateur à gauche
                                echo "<span class='date'>" . $dateEvent . "</span>"; // Date à droite
                                echo "</div>";
                                echo "<h3>" . $nomEvent . "</h3>";
                                echo "<p>" . $description . "</p>";
                                echo "<div class='image'><img src='images/$image' alt='Image de l'événement'></div>";
                                echo "</div>";
                                echo "</div>";
                                echo "<hr>";
                            }
                        } else {
                            echo "<p>Aucun événement trouvé pour les amis de " . htmlspecialchars($username) . "</p>";
                        }
                    } else {
                        echo "<p>Veuillez vous connecter pour voir les événements de vos amis.</p>";
                    }

                    // Fermer la connexion à la base de données
                    $conn->close();
                    ?>
                                </ul>   
        </div>
        <div id="friendsOfFriendsSection" class="section" style="display: none;">
                <h2 class="section-title">Feed des amis de vos Amis</h2>
                <ul class="feed">
                    <?php
                    $servername = "localhost";
                    $username = "root";
                    $password = "";
                    $dbname = "projet";


                    // Connexion à la base de données (les détails de connexion doivent être définis ci-dessus)
                    $conn = new mysqli($servername, $username, $password, $dbname);

                    // Vérifier la connexion
                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }

                    // Vérifier si l'utilisateur est connecté
                    if (isset($_SESSION['username'])) {
                        $username = $_SESSION['username']; // Utilisateur connecté

                        // Requête pour récupérer les événements des amis d'amis de l'utilisateur connecté
                        $sql = "SELECT e.NomEvent, e.Date, e.Image, e.Description, e.status, e.Utilisateur
                                FROM evenements e
                                JOIN friends f1 ON e.Utilisateur = f1.friend_name
                                JOIN friends f2 ON f1.username = f2.friend_name
                                WHERE f2.username = ? AND f1.status = 'accepted' AND f2.status = 'accepted'
                                AND f1.friend_name NOT IN (SELECT friend_name FROM friends WHERE username = ? AND status = 'accepted')
                                AND f1.friend_name != ?
                                 ORDER BY e.Date DESC";


                        // Préparer la requête
                        $stmt = $conn->prepare($sql);
                        // Lier les paramètres
                        $stmt->bind_param("sss", $username, $username, $username);
                        // Exécuter la requête
                        $stmt->execute();
                        // Récupérer le résultat
                        $result = $stmt->get_result();

                        if ($result->num_rows > 0) {
                            echo "<ul>";
                            while($row = $result->fetch_assoc()) {
                                $nomEvent = htmlspecialchars($row['NomEvent']);
                                $dateEvent = htmlspecialchars($row['Date']);
                                $description = htmlspecialchars($row['Description']);
                                $status = htmlspecialchars($row['status']);
                                $image = $row['Image'] ? htmlspecialchars($row['Image']) : 'noimage.jpg'; // Utiliser une image par défaut si aucune n'est spécifiée
                                $utilisateur = htmlspecialchars($row['Utilisateur']); // Utilisateur créateur

                                echo "<div class='feed-item'>";
                                echo "<div class='content'>";
                                echo "<div class='info-container'>";
                                echo "<h2 class='creator'>$utilisateur</h2>"; // Utilisateur créateur à gauche
                                echo "<span class='date'>$dateEvent</span>"; // Date à droite
                                echo "</div>";
                                echo "<h3>$nomEvent</h3>";
                                echo "<p>$description</p>";
                                echo "<div class='image'><img src='images/$image' alt='Image de l'événement'></div>";
                                echo "</div>";
                                echo "</div>";
                                echo "<hr>";
                            }
                            echo "</ul>";
                        } else {
                            echo "<p>Aucun événement trouvé pour les amis d'amis de " . htmlspecialchars($username) . "</p>";
                        }

                        // Fermer le statement
                        $stmt->close();
                    } else {
                        echo "<p>Veuillez vous connecter pour voir les événements de vos amis.</p>";
                    }

                    // Fermer la connexion
                    $conn->close();
                    ?>
 
                </ul>
            </div>
            <!-- Ajoutez la section des Partenaires -->
            <div id="partnersSection" class="section" style="display: none;">
                <h2 class="section-title">Partenaires</h2>
                <ul class="feed">
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

                    // Requête pour sélectionner les partenaires
                    $sql = "SELECT * FROM `evenements` WHERE `status` = 'partenaire' ORDER BY `Date` DESC";
                    $result = $conn->query($sql);

                    // Afficher les partenaires
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<div class='feed-item'>";
                            echo "<div class='content'>";
                            echo "<div class='info-container'>";
                            echo "<h2 class='creator'>" . htmlspecialchars($row["Utilisateur"]) . "</h2>";
                            echo "<span class='date'>" . htmlspecialchars($row["Date"]) . "</span>";
                            echo "</div>";
                            echo "<h3>" . htmlspecialchars($row["NomEvent"]) . "</h3>";
                            echo "<p>" . htmlspecialchars($row["Description"]) . "</p>";
                            echo "<div class='image'><img src='" . htmlspecialchars($row["Image"]) . "' alt='Image de l'événement'></div>";
                            echo "</div>";
                            echo "</div>";
                            echo "<hr>";
                        }
                    } else {
                        echo "<p>Aucun partenaire trouvé.</p>";
                    }

                    // Fermer la connexion
                    $conn->close();
                    ?>
                </ul>
            </div>

    </div>
</div>
<script>
    document.getElementById('eventsButton').classList.add('button-pressed');
    function showEvents() {
    document.getElementById('eventsSection').style.display = 'block';
    document.getElementById('friendPostsSection').style.display = 'none';
    document.getElementById('friendsOfFriendsSection').style.display = 'none';
    document.getElementById('partnersSection').style.display = 'none';
    document.getElementById('friendsOfFriendsButton').classList.remove('button-pressed');
    document.getElementById('eventsButton').classList.add('button-pressed');
    document.getElementById('friendPostsButton').classList.remove('button-pressed');
    document.getElementById('partnersButton').classList.remove('button-pressed');

}

function showFriendPosts() {
    document.getElementById('eventsSection').style.display = 'none';
    document.getElementById('friendPostsSection').style.display = 'block';
    document.getElementById('friendsOfFriendsSection').style.display = 'none';
    document.getElementById('partnersSection').style.display = 'none';
    document.getElementById('friendsOfFriendsButton').classList.remove('button-pressed');
    document.getElementById('friendPostsButton').classList.add('button-pressed');
    document.getElementById('eventsButton').classList.remove('button-pressed');
    document.getElementById('partnersButton').classList.remove('button-pressed');

}
function showFriendsOfFriends() {
            document.getElementById('eventsSection').style.display = 'none';
            document.getElementById('friendPostsSection').style.display = 'none';
            document.getElementById('friendsOfFriendsSection').style.display = 'block';
            document.getElementById('partnersSection').style.display = 'none';
            document.getElementById('friendsOfFriendsButton').classList.add('button-pressed');
            document.getElementById('eventsButton').classList.remove('button-pressed');
            document.getElementById('friendPostsButton').classList.remove('button-pressed');
            document.getElementById('partnersButton').classList.remove('button-pressed');

}
 function showPartners() {
            document.getElementById('eventsSection').style.display = 'none';
            document.getElementById('friendPostsSection').style.display = 'none';
            document.getElementById('friendsOfFriendsSection').style.display = 'none';
            document.getElementById('partnersSection').style.display = 'block';

            document.getElementById('eventsButton').classList.remove('button-pressed');
            document.getElementById('friendPostsButton').classList.remove('button-pressed');
            document.getElementById('friendsOfFriendsButton').classList.remove('button-pressed');
            document.getElementById('partnersButton').classList.add('button-pressed');
    }

</script>
<footer>
    <p>&copy; 2024 ECE In. Tous droits réservés.</p>
</footer>
</body>
</html> 
