-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : ven. 30 jan. 2026 à 11:31
-- Version du serveur : 10.4.28-MariaDB
-- Version de PHP : 8.0.28

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
  `nom` varchar(255) NOT NULL,
  `is_open` tinyint(1) NOT NULL,
  `date_event` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `events`
--

INSERT INTO `events` (`id`, `nom`, `is_open`, `date_event`) VALUES
(1, 'Vente 1', 1, '2026-01-27 11:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `statut` enum('attente','prepa','pret','archive') NOT NULL,
  `montant_total` decimal(10,0) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `event_id`, `statut`, `montant_total`, `created_at`) VALUES
(1, 1, 1, 'prepa', 12, '2026-01-27 19:05:29'),
(2, 2, 1, 'pret', 4, '2026-01-27 19:05:05'),
(3, 3, 1, 'archive', 13, '2026-01-27 19:05:34'),
(4, 3, 1, 'attente', 16, '2026-01-27 14:26:49'),
(5, 3, 1, 'archive', 9, '2026-01-27 10:00:00'),
(6, 2, 1, 'archive', 7, '2026-01-27 10:15:00'),
(7, 1, 1, 'archive', 12, '2026-01-27 10:30:00'),
(8, 3, 1, 'pret', 13, '2026-01-29 20:53:52'),
(9, 2, 1, 'prepa', 13, '2026-01-27 18:50:42');

-- --------------------------------------------------------

--
-- Structure de la table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `recipe_id` int(11) NOT NULL,
  `quantite` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `recipe_id`, `quantite`) VALUES
(1, 1, 1, 2),
(2, 1, 2, 1),
(3, 2, 3, 1),
(4, 3, 5, 2),
(5, 3, 1, 1),
(6, 4, 4, 3),
(7, 4, 5, 1),
(8, 5, 3, 1),
(9, 5, 5, 1),
(10, 6, 1, 2),
(11, 7, 2, 2),
(12, 7, 4, 1),
(13, 8, 1, 1),
(14, 8, 2, 2),
(15, 9, 1, 1),
(16, 9, 2, 2);

-- --------------------------------------------------------

--
-- Structure de la table `recipes`
--

CREATE TABLE `recipes` (
  `id` int(11) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `prix` decimal(10,0) NOT NULL,
  `stock` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `recipes`
--

INSERT INTO `recipes` (`id`, `nom`, `description`, `prix`, `stock`) VALUES
(1, 'Thon Mayo', 'Thon frais, mayonnaise japonaise, oignons verts', 4, 50),
(2, 'Boeuf Gyudon', 'Fines tranches de boeuf marinées au soja et gingembre', 5, 30),
(3, 'Poulet Teriyaki', 'Poulet grillé, sauce teriyaki maison, sésame', 4, 40),
(4, 'Aubergine Miso', 'Aubergines fondantes glacées au miso (Végétarien)', 4, 25),
(5, 'Delamama', 'Recette secrète épicée de la mama', 5, 15);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `trigramme` varchar(3) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` text NOT NULL,
  `role` enum('user','admin') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `trigramme`, `nom`, `email`, `password`, `role`) VALUES
(1, 'JDO', 'Jean Dupont', 'jean.dupont@ecole.fr', 'hash_user1', 'user'),
(2, 'ABC', 'Alice Bernard', 'alice.bernard@ecole.fr', 'hash_user2', 'user'),
(3, 'XYZ', 'Xavier Yvan Zola', 'xavier.zola@ecole.fr', 'hash_user3', 'user');

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
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT pour la table `recipes`
--
ALTER TABLE `recipes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
