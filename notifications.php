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

        .container {
            margin: 20px auto;
            display: flex;
            flex-direction: column;
            gap: 15px;
            background-color: darkblue;
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
            background-color: skyblue;
            color: black;
            height: 550px;
        }
        .section-title {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 15px;
            margin-top: 15px;
        }
        .feed {
            margin-top: 10px;   
            list-style-type: none;
            padding: 0;
            overflow-y: auto;
            max-height: 450px;
            margin-bottom: 30px;
        
        }
        .feed-item {
            border: 1px solid #e9e9e9;
            border-bottom: 1px solid #ccc;
            margin-bottom: 20px;
            padding: 2px;
            max-height: 100%;
            background-color: #D3D3D3;
            border-radius: 8px;
            width: 600px; /* Largeur fixe de 600px */
            max-width: 100%; /* Assure que la largeur ne dépasse pas 100% de la largeur du conteneur parent */
        }


    </style>
</head>
<body>
 
<div class="container text-center">
    <div class="button-container sticky-top">
        <div class="btn-group" role="group" aria-label="Basic example">
            <button type="button" onclick="showEvents()" class="btn btn-info">Evenements</button>
            <button type="button" onclick="showFriendPosts()" class="btn btn-info">Posts des Amis</button>
            <button type="button" onclick="showOffresEmplois()" class="btn btn-info">Offres     d'Emplois</button>
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
</body>
</html> 