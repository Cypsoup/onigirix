-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : lun. 16 mars 2026 à 04:55
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
  `dateEvent` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `events`
--

INSERT INTO `events` (`id`, `name`, `isOpen`, `canOrder`, `dateEvent`) VALUES
(1, 'Vente de Test 1', 0, 0, '2026-03-16 03:50:57'),
(2, 'Vente de Test 2', 0, 0, '2026-03-16 03:50:57'),
(3, 'Service Actif', 1, 1, '2026-03-16 03:50:57');

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
(1, 1, 3, 'attente', 3.50, '2026-03-16 03:50:58'),
(2, 2, 3, 'attente', 4.50, '2026-03-16 03:50:58'),
(3, 3, 3, 'prepa', 7.00, '2026-03-16 03:50:58'),
(4, 4, 3, 'pret', 8.00, '2026-03-16 03:50:58'),
(5, 5, 3, 'archive', 5.00, '2026-03-16 03:50:58'),
(6, 6, 1, 'archive', 3.50, '2026-03-16 03:50:58'),
(7, 7, 1, 'archive', 4.50, '2026-03-16 03:50:58'),
(8, 8, 1, 'archive', 4.00, '2026-03-16 03:50:58'),
(9, 9, 2, 'archive', 3.50, '2026-03-16 03:50:58'),
(10, 10, 2, 'archive', 5.00, '2026-03-16 03:50:58'),
(11, 11, 3, 'attente', 3.50, '2026-03-16 03:50:58'),
(12, 12, 3, 'prepa', 4.50, '2026-03-16 03:50:58'),
(13, 13, 3, 'pret', 4.00, '2026-03-16 03:50:58'),
(14, 14, 3, 'archive', 3.50, '2026-03-16 03:50:58'),
(15, 15, 3, 'attente', 5.00, '2026-03-16 03:50:58'),
(16, 16, 1, 'archive', 3.50, '2026-03-16 03:50:58'),
(17, 17, 1, 'archive', 4.50, '2026-03-16 03:50:58'),
(18, 18, 2, 'archive', 4.00, '2026-03-16 03:50:58'),
(19, 19, 2, 'archive', 3.50, '2026-03-16 03:50:58'),
(20, 20, 3, 'prepa', 5.00, '2026-03-16 03:50:58');

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
(1, 1, 5, 1),
(2, 2, 4, 1),
(3, 3, 3, 1),
(4, 4, 5, 1),
(5, 5, 3, 1),
(6, 6, 1, 1),
(7, 7, 1, 1),
(8, 8, 3, 1),
(9, 9, 4, 1),
(10, 10, 2, 1),
(11, 11, 1, 1),
(12, 12, 1, 1),
(13, 13, 2, 1),
(14, 14, 4, 1),
(15, 15, 3, 1),
(16, 16, 2, 1),
(17, 17, 5, 1),
(18, 18, 4, 1),
(19, 19, 4, 1),
(20, 20, 3, 1);

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
(1, 'Thon Mayo', '1.png', 'Thon frais, mayonnaise japonaise', 3.50, 100, 1),
(2, 'Boeuf Gyudon', '2.png', 'Boeuf mariné au soja', 4.50, 100, 1),
(3, 'Poulet Teriyaki', '3.png', 'Poulet grillé sauce soja sucrée', 4.00, 100, 1),
(4, 'Aubergine Miso', '4.png', 'Aubergines glacées au miso', 3.50, 100, 1),
(5, 'Delamama', '5.png', 'Recette épicée spéciale', 5.00, 100, 1);

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
(1, 'ALI', 'Admin', 'Alice', 'alice@onigirix.fr', '$2y$10$FDuXsbljVi492QTNM4HZfO7/HQKytIbj.WiacXNrhfm2u7CT1VHla', 'admin'),
(2, 'BOB', 'User', 'Bob', 'bob@polytechnique.edu', '$2y$10$7osK5zsXR5Uanxpcz8H7duMf/t5ll6qB9yU2Saj77Rsd/kuL3dz32', 'user'),
(3, 'CHA', 'Charles', 'Charlie', 'charlie@gmail.com', '$2y$10$hash_charlie', 'user'),
(4, 'DAV', 'Davis', 'David', 'david@gmail.com', '$2y$10$hash_david', 'user'),
(5, 'EVE', 'Everly', 'Eve', 'eve@gmail.com', '$2y$10$hash_eve', 'user'),
(6, 'FAB', 'Fabrice', 'Dupont', 'fab@test.fr', '$2y$10$hash', 'user'),
(7, 'GAB', 'Gabriel', 'Martin', 'gab@test.fr', '$2y$10$hash', 'user'),
(8, 'HEL', 'Helene', 'Petit', 'hel@test.fr', '$2y$10$hash', 'user'),
(9, 'IDA', 'Ida', 'Moreau', 'ida@test.fr', '$2y$10$hash', 'user'),
(10, 'JUL', 'Jules', 'Lefebvre', 'jul@test.fr', '$2y$10$hash', 'user'),
(11, 'KEV', 'Kevin', 'Garcia', 'kev@test.fr', '$2y$10$hash', 'user'),
(12, 'LEO', 'Leo', 'Roux', 'leo@test.fr', '$2y$10$hash', 'user'),
(13, 'MIA', 'Mia', 'Legrand', 'mia@test.fr', '$2y$10$hash', 'user'),
(14, 'NOA', 'Noa', 'Garnier', 'noa@test.fr', '$2y$10$hash', 'user'),
(15, 'OLI', 'Olive', 'Faure', 'oli@test.fr', '$2y$10$hash', 'user'),
(16, 'PIA', 'Pia', 'Rousseau', 'pia@test.fr', '$2y$10$hash', 'user'),
(17, 'QUY', 'Quy', 'Blanc', 'quy@test.fr', '$2y$10$hash', 'user'),
(18, 'REM', 'Remi', 'Guerin', 'rem@test.fr', '$2y$10$hash', 'user'),
(19, 'SAM', 'Sam', 'Muller', 'sam@test.fr', '$2y$10$hash', 'user'),
(20, 'TOM', 'Tom', 'Lambert', 'tom@test.fr', '$2y$10$hash', 'user');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT pour la table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT pour la table `recipes`
--
ALTER TABLE `recipes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
