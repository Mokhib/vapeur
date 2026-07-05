-- Création de la base de données et exportation[cite: 7]
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE DATABASE IF NOT EXISTS `clickvault` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `clickvault`;

-- Structure de la table `games`
CREATE TABLE `games` (
  `id_game` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `release_year` int(4) NOT NULL,
  `developer` varchar(255) NOT NULL,
  `cover_image` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Structure de la table `reviews`
CREATE TABLE `reviews` (
  `id_review` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_game` int(11) NOT NULL,
  `rating` tinyint(1) NOT NULL,
  `comment` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Structure de la table `users`
CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') NOT NULL DEFAULT 'user',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Index pour les tables
ALTER TABLE `games`
  ADD PRIMARY KEY (`id_game`);

ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id_review`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_game` (`id_game`);

ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

-- AUTO_INCREMENT pour les tables
ALTER TABLE `games`
  MODIFY `id_game` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

ALTER TABLE `reviews`
  MODIFY `id_review` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

-- Contraintes pour les tables déchargées
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`id_game`) REFERENCES `games` (`id_game`) ON DELETE CASCADE;

-- Insertion de données de test
-- Le mot de passe haché correspond à 'azerty' pour les deux comptes
INSERT INTO `users` (`id_user`, `username`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'AdminVault', 'admin@clickvault.local', '$2y$10$D8b4u26WkE/B5Q3/Vw4Vqu9n5l02U.U8/182H.g0/T0h1O6qG6u8m', 'admin', CURRENT_TIMESTAMP),
(2, 'Guybrush99', 'guybrush@clickvault.local', '$2y$10$D8b4u26WkE/B5Q3/Vw4Vqu9n5l02U.U8/182H.g0/T0h1O6qG6u8m', 'user', CURRENT_TIMESTAMP);

INSERT INTO `games` (`id_game`, `title`, `description`, `release_year`, `developer`, `cover_image`, `created_at`) VALUES
(1, 'The Secret of Monkey Island', 'Un apprenti pirate arrive sur l\'île de Mêlée pour passer les trois épreuves de la piraterie.', 1990, 'Lucasfilm Games', 'monkey1.jpg', CURRENT_TIMESTAMP),
(2, 'Day of the Tentacle', 'Trois amis voyagent dans le temps pour empêcher un tentacule pourpre muté de dominer le monde.', 1993, 'LucasArts', 'dott.jpg', CURRENT_TIMESTAMP);

COMMIT;