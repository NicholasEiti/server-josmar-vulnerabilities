SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

DROP TABLE IF EXISTS `drawers`;
CREATE TABLE `drawers` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `drawers` (`id`, `name`) VALUES
(1,	'Main drawer'),
(2,	'Secondary drawer');

DROP TABLE IF EXISTS `keys`;
CREATE TABLE `keys` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `drawer` int NOT NULL,
  `position` tinyint DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `keys` (`id`, `name`, `drawer`, `position`) VALUES
(1,	'Room-302',	1, NULL),
(2,	'Room-301',	1, 1),
(3,	'Room-303',	1, 2),
(4,	'Room-501',	2, 1);

DROP TABLE IF EXISTS `requests`;
CREATE TABLE `requests` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user` int NOT NULL,
  `key` int NOT NULL,
  `status` int NOT NULL,
  `date_start` datetime DEFAULT NULL,
  `date_expected_start` datetime NOT NULL,
  `date_end` datetime DEFAULT NULL,
  `date_expected_end` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `requests` (`id`, `user`, `key`, `status`, `date_start`, `date_expected_start`, `date_end`, `date_expected_end`) VALUES
(1,	1,	1,	1,	NULL,	'2022-09-10 23:59:59',	NULL,	'2022-09-20 23:59:59'),
(2,	2,	2,	1,	NULL,	'2022-09-12 12:00:00',	NULL,	'2022-09-12 14:00:00');

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(50) NOT NULL,
  `level` int NOT NULL,
  `expiretime` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `users` (`id`, `name`, `password`, `email`, `level`) VALUES
(1,	'Renan',	'$2y$12$yj9DXhcGipsz3WOVmONIT.Q7apNNnBUVyO/V1faj2a84fQguhAovW',	'renanpraga@gmail.com',	5, 120), -- password_of_renan
(2,	'Bruno',	'$2y$12$fucWqL46jyUjWaiWV4rGJOPJ3NngEqGUKgIyeiCzXBnxoeQmFW9zC',	'brunorocha@gmail.com',	5, 10), -- password_of_bruno
(3,	'admin',	'$2y$12$i6yB/QqcANQf6nZ.HOOBMuG1GYYJugBkTxQc8auWc/Z0JOoVcoaMi',	'admin@gmail.com',	15, NULL); -- password_of_admin
