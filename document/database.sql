-- Création de la base de données et exportation
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
  MODIFY `id_game` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

ALTER TABLE `reviews`
  MODIFY `id_review` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

-- Contraintes pour les tables déchargées
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`id_game`) REFERENCES `games` (`id_game`) ON DELETE CASCADE;

-- Insertion de données de test
-- Le mot de passe correspond à 'azerty' pour les deux comptes
INSERT INTO `users` (`id_user`, `username`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'AdminVault', 'admin@clickvault.local', 'azerty', 'admin', CURRENT_TIMESTAMP),
(2, 'Guybrush99', 'guybrush@clickvault.local', 'azerty', 'user', CURRENT_TIMESTAMP);

-- Catalogue de 30 jeux d'aventure point-and-click réellement disponibles sur Steam.
-- cover_image suit une convention de nommage prévisible : déposez vos propres images
-- dans le dossier images/ sous ce même nom (ex. « full-throttle.jpg ») pour qu'elles
-- s'affichent automatiquement ; en leur absence, une jaquette de repli s'affiche.
INSERT INTO `games` (`id_game`, `title`, `description`, `release_year`, `developer`, `cover_image`, `created_at`) VALUES
(1, 'The Secret of Monkey Island', 'Un apprenti pirate arrive sur l\'île de Mêlée pour passer les trois épreuves de la piraterie.', 1990, 'Lucasfilm Games', 'monkey1.svg', CURRENT_TIMESTAMP),
(2, 'Day of the Tentacle', 'Trois amis voyagent dans le temps pour empêcher un tentacule pourpre muté de dominer le monde.', 1993, 'LucasArts', 'dott.svg', CURRENT_TIMESTAMP),
(3, 'Monkey Island 2: LeChuck\'s Revenge', 'Guybrush part à la recherche du trésor mythique de Big Whoop tout en tentant d\'échapper au pirate fantôme LeChuck.', 1991, 'LucasArts', 'monkey2.jpg', CURRENT_TIMESTAMP),
(4, 'The Curse of Monkey Island', 'Guybrush doit briser une malédiction qui a transformé sa fiancée Elaine en statue d\'or.', 1997, 'LucasArts', 'monkey3.jpg', CURRENT_TIMESTAMP),
(5, 'Escape from Monkey Island', 'De retour sur l\'île de Mêlée, Guybrush doit déjouer les plans d\'un promoteur immobilier peu scrupuleux.', 2000, 'LucasArts', 'monkey4.jpg', CURRENT_TIMESTAMP),
(6, 'Return to Monkey Island', 'Des décennies après ses premières aventures, Guybrush reprend sa quête du secret ultime de l\'île aux Singes.', 2022, 'Terrible Toybox', 'monkey5.jpg', CURRENT_TIMESTAMP),
(7, 'Full Throttle', 'Ben, chef d\'un gang de motards, doit prouver son innocence après avoir été accusé du meurtre d\'un fabricant de motos.', 1995, 'LucasArts', 'full-throttle.jpg', CURRENT_TIMESTAMP),
(8, 'Grim Fandango', 'Manny Calavera, agent funéraire dans le monde des morts, enquête sur une escroquerie touchant les âmes en route vers l\'au-delà.', 1998, 'LucasArts', 'grim-fandango.jpg', CURRENT_TIMESTAMP),
(9, 'Sam & Max Hit the Road', 'Un chien détective et un lapin déjanté sillonnent les routes américaines à la recherche d\'un bigfoot échappé d\'un cirque.', 1993, 'LucasArts', 'sam-and-max.jpg', CURRENT_TIMESTAMP),
(10, 'Loom', 'Un jeune tisserand doit percer les secrets de sa guilde à l\'aide de sorts composés de notes de musique.', 1990, 'Lucasfilm Games', 'loom.jpg', CURRENT_TIMESTAMP),
(11, 'Indiana Jones and the Fate of Atlantis', 'Indiana Jones part sur les traces de la cité perdue de l\'Atlantide avant que les nazis ne s\'en emparent.', 1992, 'LucasArts', 'indiana-jones-fate-of-atlantis.jpg', CURRENT_TIMESTAMP),
(12, 'Broken Sword: The Shadow of the Templars', 'Un touriste américain se retrouve mêlé à un complot remontant à l\'époque des Templiers après un attentat à Paris.', 1996, 'Revolution Software', 'broken-sword-1.jpg', CURRENT_TIMESTAMP),
(13, 'Broken Sword II: The Smoking Mirror', 'George et Nico enquêtent sur le vol d\'une statuette maya qui les entraîne dans une chasse au trésor mondiale.', 1997, 'Revolution Software', 'broken-sword-2.jpg', CURRENT_TIMESTAMP),
(14, 'Beneath a Steel Sky', 'Dans une cité futuriste sous contrôle des machines, un homme élevé loin de la civilisation cherche à percer les secrets de son passé.', 1994, 'Revolution Software', 'beneath-a-steel-sky.jpg', CURRENT_TIMESTAMP),
(15, 'Gabriel Knight: Sins of the Fathers', 'Un romancier de La Nouvelle-Orléans enquête sur une série de meurtres rituels liés au vaudou et à sa propre lignée.', 1993, 'Sierra On-Line', 'gabriel-knight.jpg', CURRENT_TIMESTAMP),
(16, 'King\'s Quest VI: Heir Today, Gone Tomorrow', 'Le prince Alexander doit sauver la princesse Cassima et briser une malédiction pesant sur son royaume insulaire.', 1992, 'Sierra On-Line', 'kings-quest-6.jpg', CURRENT_TIMESTAMP),
(17, 'Space Quest IV: Roger Wilco and the Time Rippers', 'Le concierge spatial Roger Wilco voyage dans le temps pour empêcher la destruction de son propre présent.', 1991, 'Sierra On-Line', 'space-quest-4.jpg', CURRENT_TIMESTAMP),
(18, 'Leisure Suit Larry in the Land of the Lounge Lizards', 'Larry Laffer, célibataire maladroit, tente sa chance dans les bars et boîtes de nuit d\'une ville fictive.', 1987, 'Sierra On-Line', 'leisure-suit-larry.jpg', CURRENT_TIMESTAMP),
(19, 'Discworld', 'Rincevent, sorcier raté du Disque-Monde, doit affronter un dragon menaçant la cité d\'Ankh-Morpork.', 1995, 'Perfect Entertainment', 'discworld.jpg', CURRENT_TIMESTAMP),
(20, 'The Longest Journey', 'April Ryan découvre qu\'elle peut voyager entre deux mondes parallèles et qu\'elle est la clé de leur équilibre.', 1999, 'Funcom', 'the-longest-journey.jpg', CURRENT_TIMESTAMP),
(21, 'Dreamfall: The Longest Journey', 'Une jeune femme part à la recherche d\'April Ryan, disparue depuis des années, à travers deux mondes en crise.', 2006, 'Funcom', 'dreamfall.jpg', CURRENT_TIMESTAMP),
(22, 'Machinarium', 'Un petit robot, exilé loin de sa cité, doit y retourner pour sauver sa bien-aimée et déjouer un complot criminel.', 2009, 'Amanita Design', 'machinarium.jpg', CURRENT_TIMESTAMP),
(23, 'Botanicula', 'Cinq créatures végétales partent sauver la dernière graine de leur arbre, menacé par une infestation parasitaire.', 2012, 'Amanita Design', 'botanicula.jpg', CURRENT_TIMESTAMP),
(24, 'Samorost 3', 'Un petit gnome part explorer plusieurs planètes à l\'aide d\'une flûte magique aux pouvoirs mystérieux.', 2016, 'Amanita Design', 'samorost-3.jpg', CURRENT_TIMESTAMP),
(25, 'The Whispered World', 'Sadwick, un clown mélancolique, part accomplir son destin de héros aux côtés d\'une chenille philosophe.', 2009, 'Daedalic Entertainment', 'whispered-world.jpg', CURRENT_TIMESTAMP),
(26, 'Deponia', 'Rufus rêve de quitter sa planète-décharge Deponia, quitte à bouleverser malgré lui le destin de tout un monde.', 2012, 'Daedalic Entertainment', 'deponia.jpg', CURRENT_TIMESTAMP),
(27, 'Thimbleweed Park', 'Deux agents fédéraux enquêtent sur un meurtre mystérieux dans une petite ville américaine hantée par son passé industriel.', 2017, 'Terrible Toybox', 'thimbleweed-park.jpg', CURRENT_TIMESTAMP),
(28, 'Kathy Rain', 'Une étudiante motarde enquête sur la mort suspecte de son grand-père dans sa ville natale.', 2016, 'Clifftop Games', 'kathy-rain.jpg', CURRENT_TIMESTAMP),
(29, 'Unavowed', 'Un ancien possédé par un démon rejoint une société secrète pour traquer les forces surnaturelles menaçant New York.', 2018, 'Wadjet Eye Games', 'unavowed.jpg', CURRENT_TIMESTAMP),
(30, 'Gemini Rue', 'Un ancien agent infiltré et un patient amnésique voient leurs destins se croiser sur deux planètes différentes.', 2011, 'Wadjet Eye Games', 'gemini-rue.jpg', CURRENT_TIMESTAMP);

-- Avis de test
INSERT INTO `reviews` (`id_review`, `id_user`, `id_game`, `rating`, `comment`, `created_at`) VALUES
(1, 2, 1, 5, 'Un classique indémodable, l\'humour de Guybrush n\'a pas pris une ride.', CURRENT_TIMESTAMP),
(2, 1, 1, 4, 'Certaines énigmes sont vicieuses mais l\'ambiance vaut largement le détour.', CURRENT_TIMESTAMP),
(3, 2, 2, 5, 'Le double scénario temporel à travers les époques est un modèle du genre.', CURRENT_TIMESTAMP),
(4, 2, 8, 5, 'Une ambiance mexicaine unique et une histoire bouleversante, un chef-d\'œuvre absolu.', CURRENT_TIMESTAMP),
(5, 1, 22, 5, 'Des puzzles ingénieux et une direction artistique adorable, un vrai bijou indépendant.', CURRENT_TIMESTAMP),
(6, 2, 27, 4, 'Un bel hommage aux classiques LucasArts, même si le rythme est parfois inégal.', CURRENT_TIMESTAMP);

COMMIT;
