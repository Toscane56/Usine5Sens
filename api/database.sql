SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

CREATE TABLE `expositions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL,
  `description` text NOT NULL,
  `starting_at` date NOT NULL,
  `ending_at` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `expositions` (`id`, `name`, `description`, `starting_at`, `ending_at`) VALUES
(1,	'L\'eau',	'Née sur le site de l’ancienne usine de pompage d’Eau de Seine, l’Usine des 5 Sens vous fera découvrir l\'eau dans tous ses états et dans tous les sens !',	'2019-01-02',	'2019-03-02');

CREATE TABLE `senses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `senses` (`id`, `name`) VALUES
(1,	'Ouïe'),
(2,	'Touché'),
(3,	'Goût'),
(4,	'Vue'),
(5,	'Odorat');

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `firstname` varchar(40) NOT NULL,
  `lastname` varchar(40) NOT NULL,
  `password` varchar(40) NOT NULL,
  `token` varchar(40) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `users` (`id`, `email`, `firstname`, `lastname`, `password`, `token`) VALUES
(1,	'jeanne@dupuis.fr',	'Jeanne',	'Dupuis',	'0cdb30099d955a133b220227c850805379a36384',	'f16ede8ac1e06c8bb95a66e7517023f3');

CREATE TABLE `workshops` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL,
  `description` text NOT NULL,
  `starting_at` date NOT NULL,
  `ending_at` date NOT NULL,
  `scheduled_at` time NOT NULL,
  `duration` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `workshops` (`id`, `name`, `description`, `starting_at`, `ending_at`, `scheduled_at`, `duration`) VALUES
(1,	'Création de parfum',	'Lorem ipsum',	'2019-01-05',	'2019-04-05',	'14:00:00',	2),
(2,	'Atelier cuisine',	'Lorem ipsum',	'2019-01-02',	'2019-04-05',	'16:00:00',	2),
(3,	'Photographie et nature',	'Lorem ipsum',	'2019-01-10',	'2019-03-10',	'18:00:00',	1);

CREATE TABLE `workshop_reservations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `workshop_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `workshop_id` (`workshop_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `workshop_reservations_ibfk_1` FOREIGN KEY (`workshop_id`) REFERENCES `workshops` (`id`),
  CONSTRAINT `workshop_reservations_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `workshop_senses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `workshop_id` int(11) NOT NULL,
  `sense_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `workshop_id` (`workshop_id`),
  KEY `sense_id` (`sense_id`),
  CONSTRAINT `workshop_senses_ibfk_1` FOREIGN KEY (`workshop_id`) REFERENCES `workshops` (`id`),
  CONSTRAINT `workshop_senses_ibfk_2` FOREIGN KEY (`sense_id`) REFERENCES `senses` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `workshop_senses` (`id`, `workshop_id`, `sense_id`) VALUES
(1,	1,	5),
(2,	2,	3),
(3,	3,	4);