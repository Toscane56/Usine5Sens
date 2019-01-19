-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  sam. 19 jan. 2019 à 23:27
-- Version du serveur :  5.7.23
-- Version de PHP :  7.2.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `usine`
--

-- --------------------------------------------------------

--
-- Structure de la table `expositions`
--

DROP TABLE IF EXISTS `expositions`;
CREATE TABLE IF NOT EXISTS `expositions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL,
  `img` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `starting_at` date NOT NULL,
  `ending_at` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `expositions`
--

INSERT INTO `expositions` (`id`, `name`, `img`, `description`, `starting_at`, `ending_at`) VALUES
(1, 'L\'eau', 'water.png', 'Née sur le site de l’ancienne usine de pompage d’Eau de Seine, l’Usine des 5 Sens vous fera découvrir l\'eau dans tous ses états et dans tous les sens !', '2019-01-03', '2019-03-03'),
(2, 'Psychédélisme', 'psychedelisme.png', 'Immédiatement, vous pensez Flower Power, Hippies, Années 60’. Vous voyez des couleurs vives, vous sentez le patchouli – mais pas uniquement…  Mais savez-vous que le mot psychédélique a été créé à partir du grec ancien, psyché « âme », et dëloun « rendre visible, montrer » ?  Son objet principal est l’étude des corrélations entre les sens et les activités psychiques, dans un contexte artistique. Soyez prêts pour 2 mois psychédéliques à l’Usine des 5 sens !', '2019-03-04', '2019-06-03');

-- --------------------------------------------------------

--
-- Structure de la table `senses`
--

DROP TABLE IF EXISTS `senses`;
CREATE TABLE IF NOT EXISTS `senses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL,
  `color` varchar(6) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `senses`
--

INSERT INTO `senses` (`id`, `name`, `color`) VALUES
(1, 'ouïe', 'f7d74b'),
(2, 'toucher', 'b8e986'),
(3, 'goût', 'f5a624'),
(4, 'vue', '2fe6c2'),
(5, 'odorat', 'cd1aeb'),
(6, 'sens cachés', '708ef1');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `firstname` varchar(40) NOT NULL,
  `lastname` varchar(40) NOT NULL,
  `password` varchar(40) NOT NULL,
  `token` varchar(40) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `email`, `firstname`, `lastname`, `password`, `token`) VALUES
(1, 'jeanne.dupont@gmail.com', 'Jeanne', 'Dupont', 'a7ee1890faa97997816b6a57cf4b9546d3553309', '40bf8e8aeb910829394eac1f3f8504f9');

-- --------------------------------------------------------

--
-- Structure de la table `workshops`
--

DROP TABLE IF EXISTS `workshops`;
CREATE TABLE IF NOT EXISTS `workshops` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL,
  `img` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `starting_at` date NOT NULL,
  `ending_at` date NOT NULL,
  `scheduled_at` time NOT NULL,
  `duration` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `workshops`
--

INSERT INTO `workshops` (`id`, `name`, `img`, `description`, `starting_at`, `ending_at`, `scheduled_at`, `duration`) VALUES
(1, 'Photographie et nature', 'atelier_photo.jpg', 'Apprenez à faire de jolies photographies aux côtés de nos professionnels et repartez avec vos plus belles créations ! A partir de 12 ans.', '2019-01-01', '2019-06-19', '16:00:00', 2),
(2, 'Atelier cuisine', 'atelier_cuisine.jpg', 'Au menu de nos cours, découvrez des saveurs qui raviront vos papilles. Que vous soyez bec sucré ou salé, globe-trotter culinaire ou amateur de saveurs traditionnelles, gastronomie ou cuisine moderne, il y en a pour tous les goûts ! A partir de 14 ans.', '2019-01-01', '2019-08-15', '11:00:00', 3),
(3, 'Création de parfum', 'atelier_parfum.jpg', 'Qui n’a jamais rêvé d’une senteur personnelle et exclusive ? Faite par vous et juste pour vous ! Imaginez une fragrance qui chamboule les codes, qui ne ressemble à aucune autre, et qui se démarque avec subtilité des autres eaux de parfum. A partir de 16 ans.\r\n', '2019-01-03', '2019-04-17', '16:30:00', 2),
(4, 'Somesthesia', 'somesthesia.jpg', 'Participez à l\'Espace Game en réalité virtuelle dans la salle multimédia de l\'Usine des Sens et révélez vos sens cachés.', '2019-01-04', '2019-06-14', '18:00:00', 1),
(5, 'Dîner dans le noir', 'diner.jpg', 'Le concept est simple : vous arrivez au bar où vous passerez commande. Par la suite, vous accéderez à une salle obscure par un sas : c’est là que vous dégusterez votre repas. Oubliez la vue et éveillez vos autres sens : la dégustation vous procurera de nouvelles sensations.', '2019-01-09', '2019-04-30', '19:00:00', 2);

-- --------------------------------------------------------

--
-- Structure de la table `workshop_reservations`
--

DROP TABLE IF EXISTS `workshop_reservations`;
CREATE TABLE IF NOT EXISTS `workshop_reservations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `workshop_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `workshop_id` (`workshop_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `workshop_reservations`
--

INSERT INTO `workshop_reservations` (`id`, `workshop_id`, `user_id`, `date`) VALUES
(3, 1, 1, '2019-01-19'),
(7, 2, 1, '2019-01-19');

-- --------------------------------------------------------

--
-- Structure de la table `workshop_senses`
--

DROP TABLE IF EXISTS `workshop_senses`;
CREATE TABLE IF NOT EXISTS `workshop_senses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `workshop_id` int(11) NOT NULL,
  `sense_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `workshop_id` (`workshop_id`),
  KEY `sense_id` (`sense_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `workshop_senses`
--

INSERT INTO `workshop_senses` (`id`, `workshop_id`, `sense_id`) VALUES
(1, 1, 4),
(2, 1, 2),
(3, 2, 3),
(4, 2, 5),
(5, 2, 2),
(6, 3, 5),
(7, 4, 6),
(8, 5, 3),
(9, 5, 5);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `workshop_reservations`
--
ALTER TABLE `workshop_reservations`
  ADD CONSTRAINT `workshop_reservations_ibfk_1` FOREIGN KEY (`workshop_id`) REFERENCES `workshops` (`id`),
  ADD CONSTRAINT `workshop_reservations_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `workshop_senses`
--
ALTER TABLE `workshop_senses`
  ADD CONSTRAINT `workshop_senses_ibfk_1` FOREIGN KEY (`workshop_id`) REFERENCES `workshops` (`id`),
  ADD CONSTRAINT `workshop_senses_ibfk_2` FOREIGN KEY (`sense_id`) REFERENCES `senses` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
