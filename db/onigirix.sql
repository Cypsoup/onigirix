-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : lun. 16 mars 2026 à 04:00
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `onigirix`
--

-- --------------------------------------------------------

--
-- Structure de la table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `isOpen` tinyint(1) DEFAULT 0,
  `canOrder` tinyint(1) DEFAULT 0,
  `dateEvent` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `events`
--

INSERT INTO `events` (`id`, `name`, `isOpen`, `canOrder`, `dateEvent`) VALUES
(1, 'Vente Inauguration', 0, 0, '2026-03-16 02:19:32'),
(2, 'Vente de Printemps', 0, 0, '2026-03-16 02:19:32'),
(3, 'Service de Midi', 1, 0, '2026-03-16 02:55:12');

-- --------------------------------------------------------

--
-- Structure de la table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `eventId` int(11) NOT NULL,
  `status` enum('attente','prepa','pret','archive') NOT NULL,
  `totalAmount` decimal(10,2) NOT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `orders`
--

INSERT INTO `orders` (`id`, `userId`, `eventId`, `status`, `totalAmount`, `createdAt`) VALUES
(1, 2, 1, 'archive', 11.50, '2026-03-16 02:19:33'),
(2, 3, 1, 'archive', 7.00, '2026-03-16 02:19:33'),
(3, 4, 1, 'archive', 15.00, '2026-03-16 02:19:33'),
(4, 5, 1, 'archive', 4.50, '2026-03-16 02:19:33'),
(5, 2, 1, 'archive', 8.00, '2026-03-16 02:19:33'),
(6, 3, 1, 'archive', 12.00, '2026-03-16 02:19:33'),
(7, 4, 1, 'archive', 3.50, '2026-03-16 02:19:33'),
(8, 5, 1, 'archive', 9.00, '2026-03-16 02:19:33'),
(9, 2, 2, 'pret', 13.50, '2026-03-16 02:19:33'),
(10, 3, 2, 'pret', 4.00, '2026-03-16 02:19:33'),
(11, 4, 2, 'archive', 10.00, '2026-03-16 02:19:33'),
(12, 5, 2, 'archive', 7.00, '2026-03-16 02:19:33'),
(13, 2, 2, 'pret', 5.00, '2026-03-16 02:19:33'),
(14, 3, 2, 'pret', 8.50, '2026-03-16 02:19:33'),
(15, 4, 2, 'archive', 12.00, '2026-03-16 02:19:33'),
(16, 5, 2, 'archive', 3.50, '2026-03-16 02:19:33'),
(17, 2, 3, 'prepa', 12.00, '2026-03-16 02:19:33'),
(18, 3, 3, 'attente', 8.00, '2026-03-16 02:19:33'),
(19, 4, 3, 'prepa', 15.00, '2026-03-16 02:19:33'),
(20, 5, 3, 'attente', 4.50, '2026-03-16 02:19:33'),
(21, 2, 3, 'attente', 9.00, '2026-03-16 02:19:33'),
(22, 3, 3, 'prepa', 3.50, '2026-03-16 02:19:33'),
(23, 4, 3, 'attente', 10.00, '2026-03-16 02:19:33');

-- --------------------------------------------------------

--
-- Structure de la table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `orderId` int(11) NOT NULL,
  `recipeId` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `order_items`
--

INSERT INTO `order_items` (`id`, `orderId`, `recipeId`, `quantity`) VALUES
(1, 1, 1, 2),
(2, 1, 2, 1),
(3, 2, 3, 1),
(4, 2, 4, 1),
(5, 3, 5, 3),
(6, 4, 2, 1),
(7, 5, 3, 2),
(8, 6, 3, 3),
(9, 7, 1, 1),
(10, 8, 2, 2),
(11, 9, 1, 1),
(12, 9, 5, 2),
(13, 10, 3, 1),
(14, 11, 5, 2),
(15, 12, 1, 2),
(16, 13, 5, 1),
(17, 14, 2, 1),
(18, 14, 4, 1),
(19, 15, 3, 3),
(20, 16, 1, 1),
(21, 17, 3, 3),
(22, 18, 3, 2),
(23, 19, 5, 3),
(24, 20, 2, 1),
(25, 21, 2, 2),
(26, 22, 4, 1),
(27, 23, 5, 2);

-- --------------------------------------------------------

--
-- Structure de la table `recipes`
--

CREATE TABLE `recipes` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `fileName` varchar(12) DEFAULT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `available` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `recipes`
--

INSERT INTO `recipes` (`id`, `name`, `fileName`, `description`, `price`, `stock`, `available`) VALUES
(1, 'Thon Mayo', '1.png', 'Thon frais, mayonnaise japonaise, oignons verts', 3.50, 50, 1),
(2, 'Boeuf Gyudon', '2.png', 'Fines tranches de boeuf marinées au soja et gingembre', 4.50, 30, 1),
(3, 'Poulet Teriyaki', '3.png', 'Poulet grillé, sauce teriyaki maison, sésame', 4.00, 40, 1),
(4, 'Aubergine Miso', '4.png', 'Aubergines fondantes glacées au miso (Végétarien)', 3.50, 25, 1),
(5, 'Delamama', '5.png', 'Recette secrète épicée de la mama', 5.00, 15, 0);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `trigramme` varchar(3) NOT NULL,
  `name` varchar(255) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` text NOT NULL,
  `role` enum('user','admin') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `trigramme`, `name`, `firstname`, `email`, `password`, `role`) VALUES
(1, 'ALI', 'Admin', 'Alice', 'alice@onigirix.fr', '$2y$10$qIUvs.6Yt3CWcQrFqAKDDe3wo0GDUy2LsubkvRRXROgR3TJ8jEcqu', 'admin'),
(2, 'BOB', 'User', 'Bob', 'bob@polytechnique.edu', '$2y$10$6geG8fp1zxtNS93XXw.gKOdUB/PMdKh/r9LQ2c.hL7wxRkodqmCdi', 'user'),
(3, 'CHA', 'Charles', 'Charlie', 'charlie@gmail.com', '$2y$10$uYRVHEB/4lR9zy7ISDKlYOggHi0xu1dS3UYaIZX33EI7Yk0NQFdPW', 'user'),
(4, 'DAV', 'Davis', 'David', 'david@gmail.com', '$2y$10$qLL5o42x3KZ4uiVttUmGH.EniPIhZVQdA4kiwkJ28jilEfYBUjAdO', 'user'),
(5, 'EVE', 'Everly', 'Eve', 'eve@gmail.com', '$2y$10$I4Db/umznkoMJkqPbjqXgOc.78pd4.yt7T5g1Rav2PgNZRCC7RgKK', 'user');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `recipes`
--
ALTER TABLE `recipes`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `trigramme` (`trigramme`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT pour la table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT pour la table `recipes`
--
ALTER TABLE `recipes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
