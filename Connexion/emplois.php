<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emplois</title>
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
            height: 50px;
        }
        nav ul {
            list-style-type: none;
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
        footer {
            background-color: #076d79;
            color: white;
            text-align: center;
            padding: 10px 0;
            position: fixed;
            width: 100%;
            bottom: 0;
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
            width: 80%;
            max-width: 800px;
        }
        .section {
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 10px;
            background-color: lightgrey;
            height: 550px;
        }
        .section-title {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 15px;
            color: white;
        }
        .feed {
            list-style-type: none;
            padding: 0;
            overflow-y: auto;
            max-height: 450px;
            margin-bottom: 30px;
        }
        .feed-item {
            border: 1px solid #e9e9e9;
            margin-bottom: 20px;
            padding: 2px;
            background-color: white;
            border-radius: 8px;
            width: 780px;
        }
        .onglet {
            padding: 8px 12px;
            margin: 0 5px;
            border-radius: 5px;
            transition: background-color 0.3s, border-bottom 0.3s;
        }
        .onglet:hover, .ongletSelect {
            box-shadow: 12px 12px 72px -23px rgba(41,49,56,0.9) inset;
            -webkit-box-shadow: 12px 12px 72px -23px rgba(41,49,56,0.9) inset;
            -moz-box-shadow: 12px 12px 72px -23px rgba(41,49,56,0.9) inset;
        }
        .ongletSelect {
            font-weight: bold;
            color: #088897;
            border-bottom: 2px solid #088897;
            background-color: #088897; 
            border-radius: 5px;
            padding: 8px 12px;
            margin: 0 5px;
            transition: background-color 0.3s;
        }
        .onglet.active {
            background-color: #088897;
            color: white;
            border-bottom: 2px solid #088897;
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
                <li><div class="onglet"><a href="notifications.php">Notifications</a></div></li>
                <li><div class="onglet"><a href="messagerie.html">Messagerie</a></div></li>
                <li><div class="ongletSelect"><a href="emplois.html">Emplois</a></div></li>
            </ul>
        </nav>
    </header>

    <div class="container text-center">
        <div class="button-container sticky-top">
            <div class="btn-group" role="group" aria-label="Basic example">
                <button type="button" onclick="showStage()" class="onglet" data-target="stageSection">Stage</button>
                <button type="button" onclick="showApprentissage()" class="onglet" data-target="apprentissageSection">Apprentissage</button>
                <button type="button" onclick="showCDD()" class="onglet" data-target="cddSection">CDD</button>
                <button type="button" onclick="showCDI()" class="onglet" data-target="cdiSection">CDI</button>
            </div>
        </div>
        <div class="feed-container">
            <div id="stageSection" class="section">
                <h2 class="section-title">Stages</h2>
                <ul class="feed">
                    <?php
                    // Connexion à la base de données
                    $servername = "localhost";
                    $username = "root";
                    $password = "";
                    $dbname = "projet";

                    // Création de la connexion
                    $conn = new mysqli($servername, $username, $password, $dbname);

                    // Vérification de la connexion
                    if ($conn->connect_error) {
                        die("Échec de la connexion à la base de données: " . $conn->connect_error);
                    }

                    // Récupération des offres de stage
                    $query = "SELECT * FROM offres WHERE type = 'Stage'";
                    $result = $conn->query($query);

                    // Affichage des offres de stage
                    if ($result->num_rows > 0) {
                        while ($job = $result->fetch_assoc()) {
                            echo "<li class='feed-item'>";
                            echo "<h3>" . htmlspecialchars($job['titre']) . "</h3>";
                            echo "<p>" . nl2br(htmlspecialchars($job['description'])) . "</p>";
                            echo "<p><strong>Type:</strong> " . htmlspecialchars($job['type']) . "</p>";
                            echo "<p><strong>Employeur:</strong> " . htmlspecialchars($job['employeur']) . "</p>";
                            echo "<p><strong>Localisation:</strong> " . htmlspecialchars($job['localisation']) . "</p>";
                            echo "<p><strong>Date de publication:</strong> " . htmlspecialchars($job['date_publication']) . "</p>";
                            echo "<p><strong>Rémunération:</strong> " . htmlspecialchars($job['remuneration']) . "</p>";
                            echo "<p><strong>Contact:</strong> " . htmlspecialchars($job['contact_email']) . "</p>";
                            echo "</li>";
                        }
                    } else {
                        echo "<li>Aucune offre de stage disponible.</li>";
                    }

                    // Fermeture de la connexion
                    $conn->close();
                    ?>
                </ul>
            </div>
            <div id="apprentissageSection" class="section" style="display: none;">
                <h2 class="section-title">Apprentissages</h2>
                <ul class="feed">
                    <?php
                    // Connexion à la base de données
                    $servername = "localhost";
                    $username = "root";
                    $password = "";
                    $dbname = "projet";

                    // Création de la connexion
                    $conn = new mysqli($servername, $username, $password, $dbname);

                    // Vérification de la connexion
                    if ($conn->connect_error) {
                        die("Échec de la connexion à la base de données: " . $conn->connect_error);
                    }

                    // Récupération des offres de stage
                    $query = "SELECT * FROM offres WHERE type = 'Apprentissage'";
                    $result = $conn->query($query);

                    // Affichage des offres de stage
                    if ($result->num_rows > 0) {
                        while ($job = $result->fetch_assoc()) {
                            echo "<li class='feed-item'>";
                            echo "<h3>" . htmlspecialchars($job['titre']) . "</h3>";
                            echo "<p>" . nl2br(htmlspecialchars($job['description'])) . "</p>";
                            echo "<p><strong>Type:</strong> " . htmlspecialchars($job['type']) . "</p>";
                            echo "<p><strong>Employeur:</strong> " . htmlspecialchars($job['employeur']) . "</p>";
                            echo "<p><strong>Localisation:</strong> " . htmlspecialchars($job['localisation']) . "</p>";
                            echo "<p><strong>Date de publication:</strong> " . htmlspecialchars($job['date_publication']) . "</p>";
                            echo "<p><strong>Rémunération:</strong> " . htmlspecialchars($job['remuneration']) . "</p>";
                            echo "<p><strong>Contact:</strong> " . htmlspecialchars($job['contact_email']) . "</p>";
                            echo "</li>";
                        }
                    } else {
                        echo "<li>Aucune offre d'Apprentissage' disponible.</li>";
                    }

                    // Fermeture de la connexion
                    $conn->close();
                    ?>
                </ul>
            </div>
            <div id="cddSection" class="section" style="display: none;">
                <h2 class="section-title">CDD</h2>
                <ul class="feed">
                    <?php
                    // Connexion à la base de données
                    $servername = "localhost";
                    $username = "root";
                    $password = "";
                    $dbname = "projet";

                    // Création de la connexion
                    $conn = new mysqli($servername, $username, $password, $dbname);

                    // Vérification de la connexion
                    if ($conn->connect_error) {
                        die("Échec de la connexion à la base de données: " . $conn->connect_error);
                    }

                    // Récupération des offres de stage
                    $query = "SELECT * FROM offres WHERE type = 'Temporaire'";
                    $result = $conn->query($query);

                    // Affichage des offres de stage
                    if ($result->num_rows > 0) {
                        while ($job = $result->fetch_assoc()) {
                            echo "<li class='feed-item'>";
                            echo "<h3>" . htmlspecialchars($job['titre']) . "</h3>";
                            echo "<p>" . nl2br(htmlspecialchars($job['description'])) . "</p>";
                            echo "<p><strong>Type:</strong> " . htmlspecialchars($job['type']) . "</p>";
                            echo "<p><strong>Employeur:</strong> " . htmlspecialchars($job['employeur']) . "</p>";
                            echo "<p><strong>Localisation:</strong> " . htmlspecialchars($job['localisation']) . "</p>";
                            echo "<p><strong>Date de publication:</strong> " . htmlspecialchars($job['date_publication']) . "</p>";
                            echo "<p><strong>Rémunération:</strong> " . htmlspecialchars($job['remuneration']) . "</p>";
                            echo "<p><strong>Contact:</strong> " . htmlspecialchars($job['contact_email']) . "</p>";
                            echo "</li>";
                        }
                    } else {
                        echo "<li>Aucune offre d'Temporaire' disponible.</li>";
                    }

                    // Fermeture de la connexion
                    $conn->close();
                    ?>
                </ul>
            </div>
            <div id="cdiSection" class="section" style="display: none;">
                <h2 class="section-title">CDI</h2>
                <ul class="feed">
                    <?php
                    // Connexion à la base de données
                    $servername = "localhost";
                    $username = "root";
                    $password = "";
                    $dbname = "projet";

                    // Création de la connexion
                    $conn = new mysqli($servername, $username, $password, $dbname);

                    // Vérification de la connexion
                    if ($conn->connect_error) {
                        die("Échec de la connexion à la base de données: " . $conn->connect_error);
                    }

                    // Récupération des offres de stage
                    $query = "SELECT * FROM offres WHERE type = 'Permanent'";
                    $result = $conn->query($query);

                    // Affichage des offres de stage
                    if ($result->num_rows > 0) {
                        while ($job = $result->fetch_assoc()) {
                            echo "<li class='feed-item'>";
                            echo "<h3>" . htmlspecialchars($job['titre']) . "</h3>";
                            echo "<p>" . nl2br(htmlspecialchars($job['description'])) . "</p>";
                            echo "<p><strong>Type:</strong> " . htmlspecialchars($job['type']) . "</p>";
                            echo "<p><strong>Employeur:</strong> " . htmlspecialchars($job['employeur']) . "</p>";
                            echo "<p><strong>Localisation:</strong> " . htmlspecialchars($job['localisation']) . "</p>";
                            echo "<p><strong>Date de publication:</strong> " . htmlspecialchars($job['date_publication']) . "</p>";
                            echo "<p><strong>Rémunération:</strong> " . htmlspecialchars($job['remuneration']) . "</p>";
                            echo "<p><strong>Contact:</strong> " . htmlspecialchars($job['contact_email']) . "</p>";
                            echo "</li>";
                        }
                    } else {
                        echo "<li>Aucune offre d'Permanent' disponible.</li>";
                    }

                    // Fermeture de la connexion
                    $conn->close();
                    ?>
                </ul>
            </div>
        </div>
    </div>
    <script>
                window.onload = function() {
            showStage(); // Affiche la section des stages et enfonce le bouton correspondant
        };
        function showSection(sectionToShow, buttonToActivate) {
            const sections = ['stageSection', 'apprentissageSection', 'cddSection', 'cdiSection'];
            const buttons = document.querySelectorAll('.onglet');

            sections.forEach((section) => {
                document.getElementById(section).style.display = (section === sectionToShow) ? 'block' : 'none';
            });

            buttons.forEach((button) => {
                button.classList.remove('active'); // Réinitialise tous les boutons
            });

            document.querySelector(buttonToActivate).classList.add('active'); // Enfonce le bouton correspondant
        }

        function showStage() {
            showSection('stageSection', '.onglet:first-child');
        }

        function showApprentissage() {
            showSection('apprentissageSection', '.onglet:nth-child(2)');
        }

        function showCDD() {
            showSection('cddSection', '.onglet:nth-child(3)');
        }

        function showCDI() {
            showSection('cdiSection', '.onglet:nth-child(4)');
        }
        function showSection(button) {
            const target = button.getAttribute('data-target');
            const sections = ['stageSection', 'apprentissageSection', 'cddSection', 'cdiSection'];
            const buttons = document.querySelectorAll('.onglet');

            sections.forEach((section) => {
                document.getElementById(section).style.display = (section === target) ? 'block' : 'none';
            });

            buttons.forEach((btn) => {
                btn.classList.remove('active');
            });

            button.classList.add('active');
        }

        function init() {
            const buttons = document.querySelectorAll('.onglet');
            buttons.forEach((button) => {
                button.addEventListener('click', function() {
                    showSection(this);
                });
            });

            // Enfonce le bouton "Stage" par défaut
            showSection(buttons[0]);
        }

        window.onload = init;
    </script>
    <footer>
        <p>&copy; 2024 ECE In. Tous droits réservés.</p>
    </footer>
</body>
</html>
