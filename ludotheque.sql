-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:8889
-- Généré le : dim. 17 déc. 2023 à 12:03
-- Version du serveur : 5.7.39
-- Version de PHP : 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `ludotheque`
--

-- --------------------------------------------------------

--
-- Structure de la table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `client` int(11) NOT NULL,
  `products` json NOT NULL,
  `status` int(11) NOT NULL,
  `creationDate` datetime NOT NULL,
  `closeDate` datetime DEFAULT NULL,
  `payementMode` int(11) DEFAULT NULL,
  `totalPrice` double NOT NULL,
  `address` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `orders`
--

INSERT INTO `orders` (`id`, `client`, `products`, `status`, `creationDate`, `closeDate`, `payementMode`, `totalPrice`, `address`) VALUES
(16, 3, '[[31, 1, 0]]', 0, '2023-12-16 10:16:28', '2023-12-16 10:17:23', 1, 25, 'uirhuierhguirgregre - 87000 - Limoges | France'),
(18, 3, '[[31, 1, 0]]', 0, '2023-12-16 10:28:42', '2023-12-16 10:29:15', 1, 25, 'hgbrejgberg - 87000 - France | France'),
(19, 4, '[[30, 1, 0]]', 0, '2023-12-16 10:35:15', '2023-12-16 10:36:35', 2, 10, 'hgber - 87000 - ergn | France'),
(20, 6, '[[40, 1, 1], [34, 2, 2], [37, 1, 1]]', 2, '2023-12-16 18:01:17', '2023-12-16 18:02:02', 1, 91, 'Rue sans nom - 87000 - Limoges | France'),
(21, 6, '[[31, 1, 1]]', 2, '2023-12-16 18:02:06', '2023-12-16 18:03:09', 2, 46, 'Rue sans nom une nouvelle fois - 87000 - Limoges | France'),
(22, 6, '[[31, 1, 1]]', 1, '2023-12-16 18:03:15', NULL, NULL, 46, NULL),
(23, 3, '[[32, 1, 1], [33, 1, 1]]', 1, '2023-12-17 11:22:52', NULL, NULL, 21, NULL),
(24, 8, '[[31, 1, 1], [32, 2, 2]]', 1, '2023-12-17 11:25:14', NULL, NULL, 62, NULL),
(25, 9, '[[33, 1, 1]]', 2, '2023-12-17 11:28:04', '2023-12-17 11:28:34', 1, 13, '11 Bis Rue des Palmiers - 87000 - Limoges | France'),
(26, 9, '[[34, 1, 1], [37, 1, 1], [39, 2, 2], [31, 1, 1]]', 2, '2023-12-17 11:28:40', '2023-12-17 11:33:03', 2, 136, 'Adresse secrète - 72000 - Paris | France'),
(27, 9, '[[31, 1, 1], [37, 1, 1]]', 1, '2023-12-17 11:33:11', NULL, NULL, 71, NULL),
(28, 10, '[[38, 1, 1], [35, 3, 3]]', 1, '2023-12-17 11:34:31', NULL, NULL, 55, NULL),
(29, 11, '[[39, 1, 1], [33, 1, 1]]', 2, '2023-12-17 11:35:44', '2023-12-17 11:36:21', 1, 32, 'Rue de Bordeaux - 30000 - Bordeaux | France'),
(30, 11, '[[35, 1, 1]]', 1, '2023-12-17 11:36:26', NULL, NULL, 11, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(255) NOT NULL,
  `editor` varchar(75) NOT NULL,
  `recommendedAge` int(11) NOT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `editor`, `recommendedAge`, `image`) VALUES
(31, 'Chiot télécommander - Chien Robot - Eléctronique', 'Chiot télécommander - Chien Robot - Eléctronique', 'Robocop', 5, '/assets/uploads/657d950a5d126__chiot.png'),
(32, 'UNO - Jeu de société, Jeu de cartes pour enfants e', 'UNO - Jeu de société, Jeu de cartes pour enfants et adultes', 'Uno', 7, '/assets/uploads/657d93abb58aa__uno.png'),
(33, 'Jenga, Jeu de voyage branlante en bois pour Enfant', 'Jenga, Jeu de voyage branlante en bois pour Enfants', 'Jenga', 10, '/assets/uploads/657d93d4c0973__jenga.png'),
(34, 'Dujardin - Tu ris Tu perds - Jeu de société - Jeu ', 'Dujardin - Tu ris Tu perds - Jeu de société - Jeu d’ambiance', 'Dujardin', 10, '/assets/uploads/657d9407c8e01__ris-perd.png'),
(35, 'Hasbro Puissance 4, Jeu de Societe de strategie po', 'Hasbro Puissance 4, Jeu de Societe de strategie pour Enfants', 'Puissance 4', 7, '/assets/uploads/657d94394036e__puissance-4.png'),
(36, 'Jouet Bébé - Cube apprentissage - Trieur de formes', 'Jouet Bébé - Cube apprentissage - Trieur de formes', 'Bebe', 2, '/assets/uploads/657d9471350c7__jeu-lego.png'),
(37, 'Jeu éducatif - Jeu éveil - Apprendre les animaux', 'Jeu éducatif - Jeu éveil - Apprendre les animaux', 'Educ', 2, '/assets/uploads/657d94af0e697__jeu-ludique.png'),
(38, 'Véhicule construction - Télécommander - Voiture mi', 'Véhicule construction - Télécommander - Voiture militaire pour enfants', 'Construction ', 5, '/assets/uploads/657d94dcf2d49__voiture.png'),
(39, 'UNO Deluxe Edition', 'Dans cette version du UNO, découvre de nouvelles cartes et de nouvelles façons de t&#039;amuser en famille ou avec tes amis !', 'Matel', 4, '/assets/uploads/657de4a402cc8__uno.png'),
(40, 'Jenga Deluxe Edition', 'Jenga Deluxe Edition c&#039;est un jeu qui mettra tes réflexes à rude épreuve !', 'Jenga', 4, '/assets/uploads/657de5a0cb54a__jenga.png');

-- --------------------------------------------------------

--
-- Structure de la table `productsmeta`
--

CREATE TABLE `productsmeta` (
  `id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `sells` int(11) NOT NULL,
  `price` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `productsmeta`
--

INSERT INTO `productsmeta` (`id`, `quantity`, `sells`, `price`) VALUES
(31, 49, 4, 46),
(32, 48, 0, 8),
(33, 48, 2, 13),
(34, 48, 2, 27),
(35, 47, 0, 11),
(36, 50, 0, 27),
(37, 47, 2, 25),
(38, 50, 0, 22),
(39, 38, 3, 19),
(40, 18, 1, 12);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `lastName` varchar(255) NOT NULL,
  `firstName` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `creationDate` datetime NOT NULL,
  `lastAccess` datetime DEFAULT NULL,
  `role` varchar(50) NOT NULL,
  `confirmationToken` text,
  `resetToken` text,
  `resetAt` datetime DEFAULT NULL,
  `rememberToken` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `lastName`, `firstName`, `email`, `password`, `creationDate`, `lastAccess`, `role`, `confirmationToken`, `resetToken`, `resetAt`, `rememberToken`) VALUES
(3, 'Leger Achard', 'Aymeric', 'aymeric.leger@etu.unilim.fr', '$2y$10$yDYwNS9iXsWPNYmDqULUUO/IJG6u/mLAyJ3lffo8gd/QKX8.xbPKS', '2023-12-15 18:43:22', '2023-12-17 12:39:13', 'admin', NULL, NULL, NULL, NULL),
(4, 'Test', 'Jean', 'jeantest@demo.com', '$2y$10$/UqI2dR3IsCVDoPrjhhWbecaNUBBWVAh41T/0VkbffgQmCWEYS4NG', '2023-12-16 11:32:33', '2023-12-16 11:35:02', 'user', NULL, NULL, NULL, NULL),
(5, 'El Bouamali', 'Niamatallah', 'niamatallah.el-bouamali@etu.unilim.fr', '$2y$10$gbHquiNu9y1gL2UwGLpPhOpA./hJF0J5bZP4tvuoUH384RuGZ9PFK', '2023-12-16 12:12:15', '2023-12-16 15:59:05', 'admin', NULL, NULL, NULL, NULL),
(6, 'User', 'Test', 'user@demo.com', '$2y$10$WWtP6mJz0Bu0UgH96A33q.uA4L5jfEU7QZ1WHwWacCNrpmQgsgFzK', '2023-12-16 18:52:53', '2023-12-16 19:01:13', 'user', NULL, NULL, NULL, NULL),
(7, 'Admin', 'Super Testeur', 'admin@demo.com', '$2y$10$DKOZ7ZR1qhHK/oVWUkxTOupQyuIalLyLWw3yCvRk5Avg3lY4.H7SG', '2023-12-16 18:53:19', NULL, 'admin', NULL, NULL, NULL, NULL),
(8, 'Letesteur', 'Leon', 'leonletesteur@demo.com', '$2y$10$S1QExToC7/m8YqYmjyULV.ip5lTkeIyVLfPt.Paaq2KEx/hYAYgcW', '2023-12-17 12:24:52', '2023-12-17 12:25:11', 'user', NULL, NULL, NULL, NULL),
(9, 'Polette', 'Laurenne', 'polette@demo.com', '$2y$10$Lp0ljFw9gtj6VatJ2Dvswui7Q1qEeGGAwftBwGt.jT2uVtBaiX92O', '2023-12-17 12:25:58', '2023-12-17 12:26:14', 'user', NULL, NULL, NULL, NULL),
(10, 'Perot', 'Mattieu', 'mattieu@demo.com', '$2y$10$WMSkruY4fnyq4IFK5KVXuusYVsxIniS0/YYtd3py4Ncdrld/rQ7vy', '2023-12-17 12:34:05', '2023-12-17 12:34:25', 'user', NULL, NULL, NULL, NULL),
(11, 'Marechal', 'Aaron', 'aaron@demo.com', '$2y$10$zF3.WbFD5XWGn0ctNdcojeQqm6Txam33SySG9Jwc6C32Epx9rb/f2', '2023-12-17 12:35:21', '2023-12-17 12:35:34', 'user', NULL, NULL, NULL, NULL),
(12, 'Neige', 'Blanche', 'blancheneige@demo.com', '$2y$10$yG5nEzS9L4WeNsgonI5V/.1f2n8M7aSB5GAqsy8FuEYQuWmwhNPcu', '2023-12-17 12:37:00', '2023-12-17 12:37:12', 'user', NULL, NULL, NULL, NULL),
(13, 'memoire', 'oubliee', 'memoire@demo.com', '$2y$10$QXh5XYDpmOUWsl/WsQuNbOea1A5ndgeMMTLf/upL3EDpdebQD2uxy', '2023-12-17 12:37:46', '2023-12-17 00:00:00', 'user', NULL, NULL, '2023-12-17 12:38:00', NULL),
(14, 'Non', 'Confirme', 'nonconfirme@demo.com', '$2y$10$Ng9Xao3S1JnmPInVDK/6DevviDxCKibaOUoOGMg0jajqxG/vuZ9x6', '2023-12-17 12:38:58', NULL, 'user', 'vNArQmPPGbiwu5ljzMjTJlpSAnVzgUGzu6x0Chz2ejMBDD65J7EV1xRgr27F', NULL, NULL, NULL);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client` (`client`);

--
-- Index pour la table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `productsmeta`
--
ALTER TABLE `productsmeta`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT pour la table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT pour la table `productsmeta`
--
ALTER TABLE `productsmeta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`client`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`id`) REFERENCES `productsmeta` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
