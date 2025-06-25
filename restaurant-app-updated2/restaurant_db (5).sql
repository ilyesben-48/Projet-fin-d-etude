-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mer. 25 juin 2025 à 16:30
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
-- Base de données : `restaurant_db`
--

-- --------------------------------------------------------

--
-- Structure de la table `commande`
--

CREATE TABLE `commande` (
  `id_commande` int(11) NOT NULL,
  `date_commande` date NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `id_serveur` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `gerant`
--

CREATE TABLE `gerant` (
  `id_gerant` int(11) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `prénom` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `lignecommande`
--

CREATE TABLE `lignecommande` (
  `id` int(11) NOT NULL,
  `id_plat` int(11) NOT NULL,
  `id_commande` int(11) NOT NULL,
  `quantite` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `plat`
--

CREATE TABLE `plat` (
  `id_plat` int(11) NOT NULL,
  `nom_plat` varchar(100) NOT NULL,
  `prix` decimal(10,2) NOT NULL,
  `type` enum('pizza','pate','salade','burger') NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `plat`
--

INSERT INTO `plat` (`id_plat`, `nom_plat`, `prix`, `type`, `description`, `image`) VALUES
(1, 'Pizza Margherita', 80.00, 'pizza', 'Pizza classique avec sauce tomate, mozzarella et basilic frais.', 'img/pizza-margherita.jpg'),
(2, 'Pizza Pepperoni', 90.00, 'pizza', 'Pizza avec sauce tomate, mozzarella et pepperoni.', 'img/pizza_pepperoni.jpg'),
(3, 'Pizza Quatre Fromages', 100.00, 'pizza', 'Pizza aux quatre fromages : mozzarella, bleu, chèvre et parmesan.', 'img/pizzafromage.png'),
(4, 'Pâtes Carbonara', 120.00, 'pate', 'Pâtes fraîches avec sauce crémeuse, lardons et parmesan.', 'img/pates-carbonara.jpg'),
(5, 'Pâtes Bolognese', 115.00, 'pate', 'Pâtes avec sauce bolognaise à la viande et aux tomates.', 'img/pate-bolognaise-.jpg'),
(6, 'Pâtes Pesto', 110.00, 'pate', 'Pâtes avec sauce au pesto, basilic et pignons de pin.', 'img/Pâtes-Pesto.jpg'),
(7, 'Salade César', 75.00, 'salade', 'Salade verte avec poulet, croûtons et sauce César.', 'img/salade.jpg'),
(8, 'Salade Grecque', 70.00, 'salade', 'Salade avec tomates, concombres, feta et olives.', 'img/Salade-Grecque.jpg'),
(9, 'Salade Niçoise', 80.00, 'salade', 'Salade avec thon, haricots verts, œufs et olives.', 'img/Salade-Niçoise.webp'),
(10, 'Burger Gourmet', 90.00, 'burger', 'Burger au bœuf, fromage, salade et sauce spéciale.', 'img/images.jpg'),
(11, 'Burger Poulet', 85.00, 'burger', 'Burger au poulet croustillant, fromage et mayonnaise.', 'img/Burger-Poulet.jpg'),
(12, 'Burger Végétarien', 80.00, 'burger', 'Burger aux légumes grillés, fromage et sauce épicée.', 'img/Burger-Végétarien.jpg'),
(13, 'Pizza Fruits de Mer', 105.00, 'pizza', 'Pizza avec fruits de mer frais, mozzarella et ail.', 'img/pizza-fruits-mer.jpg'),
(14, 'Pâtes aux Fruits de Mer', 130.00, 'pate', 'Pâtes avec crevettes, calamars et sauce ail-persil.', 'img/pates-fruits-mer.jpg'),
(15, 'Salade de Quinoa', 85.00, 'salade', 'Salade saine au quinoa, légumes croquants et vinaigrette.', 'img/salade-quinoa.jpg'),
(16, 'Burger Fromage Double', 95.00, 'burger', 'Burger au double steak et fromage fondant.', 'img/burger-double.jpg'),
(21, 'pate dinde', 65.00, '', 'sdfghjkl', 'uploads/IMG-6852ab225490b6.22686519.png'),
(22, 'pate dinde', 70.00, '', 'cvgdezja eyfa', 'uploads/IMG-6852ab4b41a2f8.72414685.png'),
(23, 'salde', 60.00, '', 'sdfg', 'uploads/IMG-6852ab6d2a0355.78003309.png'),
(25, 'pate dinde', 80.00, '', 'dfghjkl', 'uploads/IMG-6852d528703887.34747677.jpg'),
(26, 'pate dinde', 50.00, '', 'jkliu gèt', 'uploads/IMG-6852d56d098cb8.83941181.jpg'),
(28, 'pate dinde', 99.00, '', 'pate', 'uploads/IMG-6852d5eae613d8.13746248.jpg'),
(29, 'salde', 50.00, '', 'fghjk', 'uploads/IMG-68554b0c235d46.16362795.jpg');

-- --------------------------------------------------------

--
-- Structure de la table `serveur`
--

CREATE TABLE `serveur` (
  `id_serveur` int(11) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `prénom` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `serveur`
--

INSERT INTO `serveur` (`id_serveur`, `nom`, `prénom`, `email`, `mot_de_passe`) VALUES
(1, 'benali', 'ilyes', 'ilyes.ilyesbenali2002@gmail.com', '$2y$10$vcLaEO5pTfsJJAaomMt47e6Oc.NpbzsHvPatJqZfDBKCbtdt/qzzm'),
(2, 'benali', 'ilyes', 'ilyes.ilyesbenali2002@gmail.com', '$2y$10$N3dcQI1wsUwP1uTFmTRqEuf3BleprXFsusLQ9FvynFOJg1frCHFoe'),
(3, 'ayoub', 'jlita', 'aoufoe@gmail.com', '$2y$10$ObYfcA3Emml17u7cpgSdbuPDQP1N0uR1GFsw1DW0g93pGhgHqEt5K'),
(4, 'benali', 'ilyes', 'ilyes.ilyesbenali2002@gmail.com', '$2y$10$QS81CujlM4oO0nyOQfkaLu2K5k7fxG2LGdb0xsT7NTCXmAD/FIZ/S'),
(5, 'ayoub', 'jlita', 'aoufoe@gmail.com', '$2y$10$vBAjhyOD8Ha1YryywUAimuklt36m7ETbuKBGxxk.YbqtDW5DxS9W6'),
(6, 'sjfoize', 'uyoiyiuyhi', 'qsfazef@gmail.com', '$2y$10$SJ3fEL7Nl.S06B0Uev2KB.pv/3v0Z3.gOUEorJrHVzuk6iGWlKBCq'),
(7, 'sjfoize', 'uyoiyiuyhi', 'qsfazef@gmail.com', '$2y$10$VDFFaIKF0JZXynP//II5O.PgXHxpRipJQFKkouqnGU0Hkh6fDT2qe'),
(8, 'dsgjlmqskdf', 'qsdfsdljf om', 'qsdfqsdf@gmail.com', '$2y$10$T4vwnUEqoc73jn3YCBjIZuf/y40mIiCl2RL3pV3suKg2Sr5rwSI0W');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `commande`
--
ALTER TABLE `commande`
  ADD PRIMARY KEY (`id_commande`),
  ADD KEY `fk_commande_serveur` (`id_serveur`);

--
-- Index pour la table `gerant`
--
ALTER TABLE `gerant`
  ADD PRIMARY KEY (`id_gerant`);

--
-- Index pour la table `lignecommande`
--
ALTER TABLE `lignecommande`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_lignecommande_plat` (`id_plat`),
  ADD KEY `fk_lignecommande_commande` (`id_commande`);

--
-- Index pour la table `plat`
--
ALTER TABLE `plat`
  ADD PRIMARY KEY (`id_plat`);

--
-- Index pour la table `serveur`
--
ALTER TABLE `serveur`
  ADD PRIMARY KEY (`id_serveur`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `commande`
--
ALTER TABLE `commande`
  MODIFY `id_commande` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `gerant`
--
ALTER TABLE `gerant`
  MODIFY `id_gerant` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `lignecommande`
--
ALTER TABLE `lignecommande`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `plat`
--
ALTER TABLE `plat`
  MODIFY `id_plat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT pour la table `serveur`
--
ALTER TABLE `serveur`
  MODIFY `id_serveur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `commande`
--
ALTER TABLE `commande`
  ADD CONSTRAINT `fk_commande_serveur` FOREIGN KEY (`id_serveur`) REFERENCES `serveur` (`id_serveur`) ON UPDATE CASCADE;

--
-- Contraintes pour la table `lignecommande`
--
ALTER TABLE `lignecommande`
  ADD CONSTRAINT `fk_lignecommande_commande` FOREIGN KEY (`id_commande`) REFERENCES `commande` (`id_commande`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_lignecommande_plat` FOREIGN KEY (`id_plat`) REFERENCES `plat` (`id_plat`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
