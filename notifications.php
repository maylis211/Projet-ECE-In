<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications élégantes</title>
    <style>
       body {
            font-family: 'Arial', sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 0;
        }
        .info-container {
		    display: flex;
		    justify-content: space-between;
		}

		.creator {
		    flex: 1;
		    align-content: left;
		    font-size: 20px;
		}

		.date {
		    flex: 1;
		    text-align: right;
		}

        .header {
            height: 50px;
            background-color: #007bff;
        }

        .container {
            margin: 500px; /* Espace vide plus grand sur les côtés */
            display: flex;
            flex-direction: column;
            gap: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .section {
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 10px;
        }

        .section-title {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .feed {
            list-style-type: none;
            padding: 0;
        }

        .feed-item {
		    border: 1px solid #e9e9e9;
		    border-bottom: 1px solid #ccc; 	
		    margin-bottom: 10px;
		    display: flex;
		    justify-content: space-between;
		    padding: 10px;
		    background-color: #fff;
		    border-radius: 8px; /* Ajout de border-radius pour arrondir les bords */
		}

        .feed-item:last-child {
            margin-bottom: 0;
        }

        .feed-item .content {
		    margin-bottom: 10px;
		    text-align: center; /* Centrer le contenu à l'intérieur du feed-item */
		}

		.feed-item .content img {
		    display: block; /* Pour centrer l'image horizontalement */
		    margin: 0 auto; /* Centrer l'image horizontalement */
		}

        .feed-item .date {
            color: #999;
            font-size: 12px;
        }

        .buttons {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        button {
            padding: 5px 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        /* Styles pour l'interface ressemblant à Facebook/Twitter */
        .post {
            border-bottom: 1px solid #e9ecef;
            padding: 10px 0;
        }

        .post:last-child {
            border-bottom: none;
        }

        .post .content {
            margin-bottom: 10px;
        }

        .post .date {
        	flex: 1;
            color: #6c757d;
            font-size: 12px;
        }
        .post.creator{

        }


        .button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 8px 16px;
            border-radius: 5px;
            cursor: pointer;
        }    </style>
</head>
<body>
<div class="container">
    <div class="buttons">
        <button onclick="afficherEvenements()">Événements</button>
        <button onclick="afficherPostsAmis()">Posts des Amis</button>
    </div>
    <div class="section">
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

    <div class="section">
        <h2 class="section-title">Posts des Amis</h2>
        <ul class="feed">
            <?php
            // Code PHP pour afficher les posts des amis
            ?>
        </ul>
    </div>
</div>
<script>
    function afficherEvenements() {
        fetch('displayEvents.php')
            .then(response => response.json())
            .then(events => {
                displayNotifications(events);
            })
            .catch(error => console.error('Erreur lors de la récupération des événements:', error));
    }

    function afficherPostsAmis() {
        fetch('displayFriendPosts.php') // Remplacez 'displayFriendPosts.php' par le nom du script pour les posts des amis
            .then(response => response.json())
            .then(posts => {
                displayFriendPosts(posts);
            })
            .catch(error => console.error('Erreur lors de la récupération des posts des amis:', error));
    }

    function displayNotifications(events) {
        const notificationsContainer = document.querySelector('.feed');
        notificationsContainer.innerHTML = ""; // Efface le contenu actuel
        events.forEach(event => {
            const feedItem = document.createElement('li');
            feedItem.innerHTML = `<div>${event.NomEvent}</div><div>${event.Date}</div><div>${event.Description}</div>`;
            notificationsContainer.appendChild(feedItem);
        });
    }

    function displayFriendPosts(posts) {
        const postsContainer = document.querySelector('.feed');
        postsContainer.innerHTML = ""; // Efface le contenu actuel
        posts.forEach(post => {
            const feedItem = document.createElement('li');
            feedItem.innerHTML = `<div>${post.Auteur}</div><div>${post.Contenu}</div><div>${post.Date}</div>`;
            postsContainer.appendChild(feedItem);
        });
    }
</script>
</body>
</html>






