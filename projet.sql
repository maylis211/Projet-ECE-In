-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mar. 28 mai 2024 à 13:25
-- Version du serveur : 8.3.0
-- Version de PHP : 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `projet`
--

-- --------------------------------------------------------

--
-- Structure de la table `comments`
--

DROP TABLE IF EXISTS `comments`;
CREATE TABLE IF NOT EXISTS `comments` (
  `comment_id` int NOT NULL AUTO_INCREMENT,
  `id` int DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `content` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`comment_id`),
  KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `comments`
--

INSERT INTO `comments` (`comment_id`, `id`, `username`, `content`, `created_at`) VALUES
(1, 34, 'louis.mahl', 'lalallalaa', '2024-05-27 18:18:24'),
(2, 35, 'louis.mahl', 'cc\r\n', '2024-05-27 18:18:30'),
(3, 35, 'louis.mahl', 'c\'est top', '2024-05-27 18:18:42'),
(4, 35, 'louis.mahl', 'c bo ece', '2024-05-27 18:18:50'),
(5, 34, 'louis.mahl', 'kjhgf', '2024-05-27 18:18:55'),
(6, 34, 'louis.mahl', 'kjhgf', '2024-05-27 18:20:54'),
(7, 36, 'louis.mahl', 'cool', '2024-05-27 18:21:10'),
(8, 36, 'louis.mahl', 'cool', '2024-05-27 18:22:59'),
(9, 36, 'louis.mahl', 'cool', '2024-05-27 18:24:02'),
(10, 36, 'Maylis', 'top le post\r\n', '2024-05-27 20:13:20'),
(11, 34, 'Maylis', 'joliii', '2024-05-27 20:13:32'),
(12, 37, 'Maylis', 'y a pa encore\r\n', '2024-05-27 20:27:57'),
(13, 38, 'louis.mahl', 'je commente\r\n', '2024-05-27 21:00:13'),
(14, 39, 'louis.mahl', 'tyu', '2024-05-28 08:27:50');

-- --------------------------------------------------------

--
-- Structure de la table `evenements`
--

DROP TABLE IF EXISTS `evenements`;
CREATE TABLE IF NOT EXISTS `evenements` (
  `Utilisateur` text NOT NULL,
  `NomEvent` text NOT NULL,
  `Date` date NOT NULL,
  `Image` text NOT NULL,
  `Description` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `formations`
--

DROP TABLE IF EXISTS `formations`;
CREATE TABLE IF NOT EXISTS `formations` (
  `id` int NOT NULL AUTO_INCREMENT,
  `utilisateur_id` int DEFAULT NULL,
  `nom` varchar(255) DEFAULT NULL,
  `date_debut` date DEFAULT NULL,
  `date_fin` date DEFAULT NULL,
  `lieu` varchar(255) DEFAULT NULL,
  `description` text,
  PRIMARY KEY (`id`),
  KEY `utilisateur_id` (`utilisateur_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `friends`
--

DROP TABLE IF EXISTS `friends`;
CREATE TABLE IF NOT EXISTS `friends` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(255) DEFAULT NULL,
  `friend_name` varchar(255) DEFAULT NULL,
  `status` enum('pending','accepted') DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `username` (`username`(250)),
  KEY `friend_name` (`friend_name`(250))
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `parcours`
--

DROP TABLE IF EXISTS `parcours`;
CREATE TABLE IF NOT EXISTS `parcours` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `titre` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `date_debut` date NOT NULL,
  `date_fin` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `parcours`
--

INSERT INTO `parcours` (`id`, `username`, `titre`, `description`, `date_debut`, `date_fin`) VALUES
(2, 'louis.mahl', 'ECE', 'Etudiant', '2016-09-27', '2019-12-27'),
(3, 'louis.mahl', 'Chef d\'entreprise', '   ', '2024-05-31', '2024-06-09'),
(4, 'Maylis', 'ECE', 'Etudiante', '2022-09-02', '2024-05-27'),
(22, 'Maylis', 'Lycée Montalembert', 'Bac général Maths Physique NSI', '2019-09-02', '2022-07-01');

-- --------------------------------------------------------

--
-- Structure de la table `posts`
--

DROP TABLE IF EXISTS `posts`;
CREATE TABLE IF NOT EXISTS `posts` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `is_event` int NOT NULL,
  `media` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `date_debut` date NOT NULL,
  `date_fin` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=48 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `posts`
--

INSERT INTO `posts` (`id`, `username`, `content`, `created_at`, `is_event`, `media`, `date_debut`, `date_fin`) VALUES
(36, 'louis.mahl', 'NV POST LA TEAM', '2024-05-27 18:21:05', 0, '', '0000-00-00', '0000-00-00'),
(35, 'louis.mahl', 'EVENMENT', '2024-05-27 17:55:50', 1, 'images/images.jpg', '2024-05-27', '2024-06-02'),
(34, 'louis.mahl', 'Post', '2024-05-27 17:55:15', 0, 'images/GEDC0040.JPG', '0000-00-00', '0000-00-00'),
(39, 'louis.mahl', 'MEDUSA EH MEDUSA EH MEDUSA MEDUSA', '2024-05-28 07:25:50', 0, '', '0000-00-00', '0000-00-00'),
(42, 'louis.mahl', 'banger', '2024-05-28 07:44:43', 0, 'uploads/images/zelda.png', '0000-00-00', '0000-00-00'),
(43, 'louis.mahl', 'top meduse', '2024-05-28 07:45:34', 0, '', '0000-00-00', '0000-00-00'),
(47, 'louis.mahl', 'Votre note au projet xdddd', '2024-05-28 07:55:44', 0, 'uploads/images/0.png', '0000-00-00', '0000-00-00');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

DROP TABLE IF EXISTS `utilisateur`;
CREATE TABLE IF NOT EXISTS `utilisateur` (
  `username` text NOT NULL,
  `password` text NOT NULL,
  `photoProfil` text NOT NULL,
  `description` text NOT NULL,
  `demande_ami` varchar(255) NOT NULL,
  `cv` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `utilisateur`
--
INSERT INTO `evenements` (`Utilisateur`, `NomEvent`, `Date`, `Image`, `Description`) VALUES
('joss', 'Conférence sur les nouvelles technologies', '2024-06-15', 'conference.jpg', 'Conférence organisée par l\'Ecole sur les dernières tendances technologiques.');

INSERT INTO `utilisateur` (`username`, `password`, `photoProfil`, `description`, `demande_ami`, `cv`) VALUES
('Maylis', 'hello', 'uploads/WhatsApp Image 2024-05-27 à 15.55.35_78324e94.jpg', 'Y a pas de ralentir', '', ''),
('Elsa', 'star', 'uploads/1715340211668.jpg', '', '', ''),
('louis.mahl', 'ILoveAlgo', 'uploads/1688637975297.jpg', 'Meilleur prof de l\'ECE', 'Elsa', 'cv/Sujet 1 - Social Media.pdf'),
('joss','grosnibard','uploads/saut 3.jpg','influenceur beaute','','');
COMMIT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
