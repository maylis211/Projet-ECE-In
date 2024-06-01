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
            max-width: 600px;
        }
        .section {
            margin-top: 0px;
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 10px;
            background-color: gray;
            color: black;
            height: 550px;

        }
        .section-title {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 15px;
            margin-top: 15px;
            color: white;
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
            width: 600px; /* Largeur fixe de 600px */
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
                <li><div class="onglet"><a href="messagerie.php">Messagerie</a></div></li>
                <li><div class="onglet"><a href="emplois.html">Emplois</a></div></li>
            </ul>
        </nav>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

    </header>
 
<div class="container text-center">
    <div class="button-container sticky-top">
        <div class="btn-group" role="group" aria-label="Basic example">
            <button type="button" onclick="showEvents()" class="onlget">Evenements</button>
            <button type="button" onclick="showFriendPosts()" class="onglet">Posts des Amis</button>
            <button type="button" onclick="showOffresEmplois()" class="onglet">Offres     d'Emplois</button>
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
            <h2 class="section-title">Posts des Amis</h2>
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

                $sql = "SELECT * FROM parcours ORDER BY date_debut DESC";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<div class='feed-item'>";
                        echo "<div class='content'>";
                        echo "<h2 class='creator'>" . $row["username"] . "</h2>"; // Nom de l'utilisateur
                        echo "<p><strong>Titre:</strong> " . $row["titre"] . "</p>"; // Titre du parcours
                        echo "<p><strong>Description:</strong> " . $row["description"] . "</p>"; // Description du parcours
                        echo "<p><strong>Date de début:</strong> " . $row["date_debut"] . "</p>"; // Date de début du parcours
                        echo "<p><strong>Date de fin:</strong> " . $row["date_fin"] . "</p>"; // Date de fin du parcours
                        echo "</div>";
                        echo "</div>";
                        echo "<hr>";
                    }
                } else {
                    echo "Aucun résultat trouvé.";
                }

            $conn->close();
            ?>
            </ul>   
        </div>
        <div id="offresEmploisSection" class="section" style="display: none;">
    <h2 class="section-title">Offres d'Emplois</h2>
    <ul class="feed">
        <?php
        // Connexion à la base de données
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "projet";
        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Échec de la connexion à la base de données: " . $conn->connect_error);
        }

        // Requête pour sélectionner les offres d'emplois
        $sql = "SELECT * FROM offres ORDER BY date_publication DESC";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='feed-item'>";
                echo "<div class='content'>";
                echo "<h2 class='creator'>" . $row["employeur"] . "</h2>"; // Employeur
                echo "<h3>" . $row["titre"] . "</h3>"; // Titre de l'offre
                echo "<p><strong>Description:</strong> " . $row["description"] . "</p>"; // Description
                echo "<p><strong>Type:</strong> " . $row["type"] . "</p>"; // Type de contrat
                echo "<p><strong>Localisation:</strong> " . $row["localisation"] . "</p>"; // Localisation
                echo "<p><strong>Date de publication:</strong> " . $row["date_publication"] . "</p>"; // Date de publication
                echo "<p><strong>Rémunération:</strong> " . $row["remuneration"] . "</p>"; // Rémunération
                echo "<p><strong>Contact:</strong> " . $row["contact_email"] . "</p>"; // Contact
                echo "</div>";
                echo "</div>";
                echo "<hr>";
            }
        } else {
            echo "Aucune offre d'emploi trouvée.";
        }

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
    document.getElementById('offresEmploisSection').style.display = 'none';
    document.getElementById('offresEmploisButton').classList.remove('button-pressed');
    document.getElementById('eventsButton').classList.add('button-pressed');
    document.getElementById('friendPostsButton').classList.remove('button-pressed');
}

function showFriendPosts() {
    document.getElementById('eventsSection').style.display = 'none';
    document.getElementById('friendPostsSection').style.display = 'block';
    document.getElementById('offresEmploisSection').style.display = 'none';
    document.getElementById('offresEmploisButton').classList.remove('button-pressed');
    document.getElementById('friendPostsButton').classList.add('button-pressed');
    document.getElementById('eventsButton').classList.remove('button-pressed');
}
function showOffresEmplois() {
        document.getElementById('eventsSection').style.display = 'none';
        document.getElementById('friendPostsSection').style.display = 'none';
        document.getElementById('offresEmploisSection').style.display = 'block';
        document.getElementById('offresEmploisButton').classList.add('button-pressed');
        document.getElementById('eventsButton').classList.remove('button-pressed');
        document.getElementById('friendPostsButton').classList.remove('button-pressed');
 }
</script>
<footer>
    <p>&copy; 2024 ECE In. Tous droits réservés.</p>
</footer>
</body>
</html> 
