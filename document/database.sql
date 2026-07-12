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
  `support` varchar(100) NOT NULL DEFAULT 'PC',
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
  MODIFY `id_review` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=98;

ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

-- Contraintes pour les tables déchargées
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`id_game`) REFERENCES `games` (`id_game`) ON DELETE CASCADE;

-- Insertion de données de test
-- Le mot de passe correspond à 'azerty' pour tous les comptes
INSERT INTO `users` (`id_user`, `username`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'AdminVault', 'admin@clickvault.local', 'azerty', 'admin', '2025-08-03 09:12:00'),
(2, 'Guybrush99', 'guybrush@clickvault.local', 'azerty', 'user', '2025-08-05 19:40:00'),
(3, 'PixelHunter77', 'pixelhunter@clickvault.local', 'azerty', 'user', '2025-08-19 21:03:00'),
(4, 'SerialClicker', 'serialclicker@clickvault.local', 'azerty', 'user', '2025-09-02 14:27:00'),
(5, 'GrogMaster', 'grogmaster@clickvault.local', 'azerty', 'user', '2025-09-14 22:51:00'),
(6, 'InventoryHoarder', 'inventoryhoarder@clickvault.local', 'azerty', 'user', '2025-09-30 11:38:00'),
(7, 'PointNClickQueen', 'pointnclickqueen@clickvault.local', 'azerty', 'user', '2025-10-11 16:59:00'),
(8, 'CathodeRay', 'cathoderay@clickvault.local', 'azerty', 'user', '2025-10-27 20:14:00'),
(9, 'FloppyDiskFan', 'floppydiskfan@clickvault.local', 'azerty', 'user', '2025-11-08 13:22:00'),
(10, 'MojoRising', 'mojorising@clickvault.local', 'azerty', 'user', '2025-11-21 18:05:00');

-- Catalogue de 30 jeux d'aventure point-and-click réellement disponibles sur Steam.
-- cover_image suit une convention de nommage prévisible : déposez vos propres images
-- dans le dossier images/ sous ce même nom (ex. « full-throttle.jpg ») pour qu'elles
-- s'affichent automatiquement ; en leur absence, une jaquette de repli s'affiche.
INSERT INTO `games` (`id_game`, `title`, `description`, `release_year`, `developer`, `support`, `cover_image`, `created_at`) VALUES
(1, 'The Secret of Monkey Island', 'Un apprenti pirate arrive sur l\'île de Mêlée pour passer les trois épreuves de la piraterie.', 1990, 'Lucasfilm Games', 'PC,Console', 'monkey1.svg', CURRENT_TIMESTAMP),
(2, 'Day of the Tentacle', 'Trois amis voyagent dans le temps pour empêcher un tentacule pourpre muté de dominer le monde.', 1993, 'LucasArts', 'PC,Console', 'dott.svg', CURRENT_TIMESTAMP),
(3, 'Monkey Island 2: LeChuck\'s Revenge', 'Guybrush part à la recherche du trésor mythique de Big Whoop tout en tentant d\'échapper au pirate fantôme LeChuck.', 1991, 'LucasArts', 'PC', 'monkey2.jpg', CURRENT_TIMESTAMP),
(4, 'The Curse of Monkey Island', 'Guybrush doit briser une malédiction qui a transformé sa fiancée Elaine en statue d\'or.', 1997, 'LucasArts', 'PC', 'monkey3.jpg', CURRENT_TIMESTAMP),
(5, 'Escape from Monkey Island', 'De retour sur l\'île de Mêlée, Guybrush doit déjouer les plans d\'un promoteur immobilier peu scrupuleux.', 2000, 'LucasArts', 'PC,Console', 'monkey4.jpg', CURRENT_TIMESTAMP),
(6, 'Return to Monkey Island', 'Des décennies après ses premières aventures, Guybrush reprend sa quête du secret ultime de l\'île aux Singes.', 2022, 'Terrible Toybox', 'PC,Console,Mobile', 'monkey5.jpg', CURRENT_TIMESTAMP),
(7, 'Full Throttle', 'Ben, chef d\'un gang de motards, doit prouver son innocence après avoir été accusé du meurtre d\'un fabricant de motos.', 1995, 'LucasArts', 'PC', 'full-throttle.jpg', CURRENT_TIMESTAMP),
(8, 'Grim Fandango', 'Manny Calavera, agent funéraire dans le monde des morts, enquête sur une escroquerie touchant les âmes en route vers l\'au-delà.', 1998, 'LucasArts', 'PC,Console', 'grim-fandango.jpg', CURRENT_TIMESTAMP),
(9, 'Sam & Max Hit the Road', 'Un chien détective et un lapin déjanté sillonnent les routes américaines à la recherche d\'un bigfoot échappé d\'un cirque.', 1993, 'LucasArts', 'PC', 'sam-and-max.jpg', CURRENT_TIMESTAMP),
(10, 'Loom', 'Un jeune tisserand doit percer les secrets de sa guilde à l\'aide de sorts composés de notes de musique.', 1990, 'Lucasfilm Games', 'PC', 'loom.jpg', CURRENT_TIMESTAMP),
(11, 'Indiana Jones and the Fate of Atlantis', 'Indiana Jones part sur les traces de la cité perdue de l\'Atlantide avant que les nazis ne s\'en emparent.', 1992, 'LucasArts', 'PC', 'indiana-jones-fate-of-atlantis.jpg', CURRENT_TIMESTAMP),
(12, 'Broken Sword: The Shadow of the Templars', 'Un touriste américain se retrouve mêlé à un complot remontant à l\'époque des Templiers après un attentat à Paris.', 1996, 'Revolution Software', 'PC,Console,Mobile', 'broken-sword-1.jpg', CURRENT_TIMESTAMP),
(13, 'Broken Sword II: The Smoking Mirror', 'George et Nico enquêtent sur le vol d\'une statuette maya qui les entraîne dans une chasse au trésor mondiale.', 1997, 'Revolution Software', 'PC,Console,Mobile', 'broken-sword-2.jpg', CURRENT_TIMESTAMP),
(14, 'Beneath a Steel Sky', 'Dans une cité futuriste sous contrôle des machines, un homme élevé loin de la civilisation cherche à percer les secrets de son passé.', 1994, 'Revolution Software', 'PC,Mobile', 'beneath-a-steel-sky.jpg', CURRENT_TIMESTAMP),
(15, 'Gabriel Knight: Sins of the Fathers', 'Un romancier de La Nouvelle-Orléans enquête sur une série de meurtres rituels liés au vaudou et à sa propre lignée.', 1993, 'Sierra On-Line', 'PC', 'gabriel-knight.jpg', CURRENT_TIMESTAMP),
(16, 'King\'s Quest VI: Heir Today, Gone Tomorrow', 'Le prince Alexander doit sauver la princesse Cassima et briser une malédiction pesant sur son royaume insulaire.', 1992, 'Sierra On-Line', 'PC', 'kings-quest-6.jpg', CURRENT_TIMESTAMP),
(17, 'Space Quest IV: Roger Wilco and the Time Rippers', 'Le concierge spatial Roger Wilco voyage dans le temps pour empêcher la destruction de son propre présent.', 1991, 'Sierra On-Line', 'PC', 'space-quest-4.jpg', CURRENT_TIMESTAMP),
(18, 'Leisure Suit Larry in the Land of the Lounge Lizards', 'Larry Laffer, célibataire maladroit, tente sa chance dans les bars et boîtes de nuit d\'une ville fictive.', 1987, 'Sierra On-Line', 'PC', 'leisure-suit-larry.jpg', CURRENT_TIMESTAMP),
(19, 'Discworld', 'Rincevent, sorcier raté du Disque-Monde, doit affronter un dragon menaçant la cité d\'Ankh-Morpork.', 1995, 'Perfect Entertainment', 'PC,Console', 'discworld.jpg', CURRENT_TIMESTAMP),
(20, 'The Longest Journey', 'April Ryan découvre qu\'elle peut voyager entre deux mondes parallèles et qu\'elle est la clé de leur équilibre.', 1999, 'Funcom', 'PC', 'the-longest-journey.jpg', CURRENT_TIMESTAMP),
(21, 'Dreamfall: The Longest Journey', 'Une jeune femme part à la recherche d\'April Ryan, disparue depuis des années, à travers deux mondes en crise.', 2006, 'Funcom', 'PC,Console', 'dreamfall.jpg', CURRENT_TIMESTAMP),
(22, 'Machinarium', 'Un petit robot, exilé loin de sa cité, doit y retourner pour sauver sa bien-aimée et déjouer un complot criminel.', 2009, 'Amanita Design', 'PC,Console,Mobile', 'machinarium.jpg', CURRENT_TIMESTAMP),
(23, 'Botanicula', 'Cinq créatures végétales partent sauver la dernière graine de leur arbre, menacé par une infestation parasitaire.', 2012, 'Amanita Design', 'PC,Mobile', 'botanicula.jpg', CURRENT_TIMESTAMP),
(24, 'Samorost 3', 'Un petit gnome part explorer plusieurs planètes à l\'aide d\'une flûte magique aux pouvoirs mystérieux.', 2016, 'Amanita Design', 'PC,Mobile', 'samorost-3.jpg', CURRENT_TIMESTAMP),
(25, 'The Whispered World', 'Sadwick, un clown mélancolique, part accomplir son destin de héros aux côtés d\'une chenille philosophe.', 2009, 'Daedalic Entertainment', 'PC', 'whispered-world.jpg', CURRENT_TIMESTAMP),
(26, 'Deponia', 'Rufus rêve de quitter sa planète-décharge Deponia, quitte à bouleverser malgré lui le destin de tout un monde.', 2012, 'Daedalic Entertainment', 'PC,Console,Mobile', 'deponia.jpg', CURRENT_TIMESTAMP),
(27, 'Thimbleweed Park', 'Deux agents fédéraux enquêtent sur un meurtre mystérieux dans une petite ville américaine hantée par son passé industriel.', 2017, 'Terrible Toybox', 'PC,Console,Mobile', 'thimbleweed-park.jpg', CURRENT_TIMESTAMP),
(28, 'Kathy Rain', 'Une étudiante motarde enquête sur la mort suspecte de son grand-père dans sa ville natale.', 2016, 'Clifftop Games', 'PC,Console,Mobile', 'kathy-rain.jpg', CURRENT_TIMESTAMP),
(29, 'Unavowed', 'Un ancien possédé par un démon rejoint une société secrète pour traquer les forces surnaturelles menaçant New York.', 2018, 'Wadjet Eye Games', 'PC', 'unavowed.jpg', CURRENT_TIMESTAMP),
(30, 'Gemini Rue', 'Un ancien agent infiltré et un patient amnésique voient leurs destins se croiser sur deux planètes différentes.', 2011, 'Wadjet Eye Games', 'PC,Mobile', 'gemini-rue.jpg', CURRENT_TIMESTAMP);

-- Avis de test
INSERT INTO `reviews` (`id_review`, `id_user`, `id_game`, `rating`, `comment`, `created_at`) VALUES
(1, 2, 1, 5, 'Un classique indémodable, l\'humour de Guybrush n\'a pas pris une ride.', CURRENT_TIMESTAMP),
(2, 1, 1, 4, 'Certaines énigmes sont vicieuses mais l\'ambiance vaut largement le détour.', CURRENT_TIMESTAMP),
(3, 2, 2, 5, 'Le double scénario temporel à travers les époques est un modèle du genre.', CURRENT_TIMESTAMP),
(4, 2, 8, 5, 'Une ambiance mexicaine unique et une histoire bouleversante, un chef-d\'œuvre absolu.', CURRENT_TIMESTAMP),
(5, 1, 22, 5, 'Des puzzles ingénieux et une direction artistique adorable, un vrai bijou indépendant.', CURRENT_TIMESTAMP),
(6, 2, 27, 4, 'Un bel hommage aux classiques LucasArts, même si le rythme est parfois inégal.', CURRENT_TIMESTAMP),
(7, 6, 1, 3, 'Pas mauvais, mais je m\'attendais à mieux vu la réputation du studio.', '2025-10-29 18:27:00'),
(8, 7, 1, 5, 'Le combat d\'insultes reste un des moments les plus drôles de toute l\'histoire du jeu vidéo.', '2026-05-31 18:01:00'),
(9, 9, 1, 5, 'Les dialogues sont écrits avec beaucoup de finesse, quel plaisir à lire.', '2026-05-02 18:17:00'),
(10, 10, 1, 5, 'Le combat d\'insultes reste un des moments les plus drôles de toute l\'histoire du jeu vidéo.', '2026-04-19 14:17:00'),
(11, 6, 2, 3, 'Sympa à faire une fois, sans être un indispensable du genre à mes yeux.', '2026-05-08 17:07:00'),
(12, 4, 2, 2, 'Je m\'attendais à beaucoup mieux, l\'histoire ne décolle jamais vraiment.', '2026-07-07 12:45:00'),
(13, 3, 2, 5, 'Le genre d\'histoire qui reste en tête des semaines après avoir fini le jeu.', '2026-01-12 22:06:00'),
(14, 1, 3, 3, 'L\'histoire est intéressante mais le rythme s\'essouffle sur la fin.', '2025-12-11 17:46:00'),
(15, 4, 3, 5, 'Chaque nouvelle zone amène son lot de surprises, jamais un temps mort.', '2026-06-27 12:43:00'),
(16, 10, 3, 4, 'Les dialogues sont écrits avec beaucoup de finesse, quel plaisir à lire.', '2025-10-01 21:20:00'),
(17, 1, 4, 5, 'Direction artistique superbe, chaque écran donne envie de s\'arrêter pour l\'admirer.', '2026-01-28 11:15:00'),
(18, 8, 4, 3, 'Pas mauvais, mais je m\'attendais à mieux vu la réputation du studio.', '2026-01-27 20:37:00'),
(19, 7, 4, 5, 'Les puzzles demandent de vraiment observer le décor, très satisfaisant à résoudre.', '2026-01-05 11:32:00'),
(20, 5, 5, 5, 'Chaque personnage secondaire a sa petite histoire, le monde est incroyablement vivant.', '2026-01-21 17:55:00'),
(21, 9, 5, 1, 'Le pixel hunting permanent gâche un peu le plaisir de l\'exploration.', '2026-03-08 10:18:00'),
(22, 3, 5, 5, 'Les énigmes sont retorses mais jamais injustes, j\'ai adoré chaque minute.', '2026-01-27 17:48:00'),
(23, 3, 6, 3, 'Pas mauvais, mais je m\'attendais à mieux vu la réputation du studio.', '2026-06-13 23:00:00'),
(24, 4, 6, 5, 'Les énigmes sont retorses mais jamais injustes, j\'ai adoré chaque minute.', '2025-11-11 23:23:00'),
(25, 1, 6, 2, 'Certaines combinaisons d\'objets n\'ont ni queue ni tête, très frustrant.', '2025-10-14 12:56:00'),
(26, 3, 7, 1, 'Le pixel hunting permanent gâche un peu le plaisir de l\'exploration.', '2026-06-12 22:38:00'),
(27, 4, 7, 4, 'Le genre de jeu qu\'on a envie de refaire juste pour le plaisir des dialogues.', '2025-12-26 20:19:00'),
(28, 6, 7, 5, 'Un jeu qui respecte l\'intelligence du joueur, aucune énigme absurde ou tirée par les cheveux.', '2026-06-06 16:07:00'),
(29, 9, 7, 4, 'Un scénario qui prend son temps mais qui paie chaque rebondissement.', '2025-09-25 18:35:00'),
(30, 10, 8, 5, 'Le Jour des Morts version Manny Calavera, une ambiance qu\'on ne retrouve nulle part ailleurs.', '2026-02-04 19:31:00'),
(31, 4, 8, 5, 'Le Jour des Morts version Manny Calavera, une ambiance qu\'on ne retrouve nulle part ailleurs.', '2026-01-17 21:30:00'),
(32, 6, 8, 5, 'Le Jour des Morts version Manny Calavera, une ambiance qu\'on ne retrouve nulle part ailleurs.', '2026-04-23 14:27:00'),
(33, 10, 9, 5, 'Un vrai plaisir de retrouver ce style d\'aventure, l\'interface point-and-click est intuitive.', '2026-02-04 16:15:00'),
(34, 5, 9, 4, 'Sam et Max forment un duo absurde et hilarant, l\'écriture est mordante du début à la fin.', '2025-10-10 19:34:00'),
(35, 6, 9, 3, 'Quelques passages franchement obscurs question logique, dommage.', '2025-12-09 15:31:00'),
(36, 5, 10, 5, 'Direction artistique superbe, chaque écran donne envie de s\'arrêter pour l\'admirer.', '2025-12-21 13:13:00'),
(37, 3, 10, 1, 'Je m\'attendais à beaucoup mieux, l\'histoire ne décolle jamais vraiment.', '2025-10-14 09:37:00'),
(38, 9, 10, 4, 'L\'ambiance sonore à elle seule justifie d\'y jouer, un vrai régal pour les oreilles.', '2026-06-02 10:54:00'),
(39, 7, 11, 3, 'Un peu court, j\'aurais aimé passer plus de temps avec ces personnages.', '2026-07-10 18:33:00'),
(40, 6, 11, 5, 'Le rythme est parfait, ni trop lent ni trop rapide, on prend le temps d\'explorer.', '2026-02-22 12:16:00'),
(41, 3, 11, 5, 'Un jeu qui respecte l\'intelligence du joueur, aucune énigme absurde ou tirée par les cheveux.', '2026-02-23 23:48:00'),
(42, 4, 12, 2, 'Le pixel hunting permanent gâche un peu le plaisir de l\'exploration.', '2025-12-04 16:53:00'),
(43, 6, 12, 4, 'L\'intrigue autour des Templiers est bien plus sérieuse que prévu, agréable surprise.', '2026-06-25 13:59:00'),
(44, 6, 13, 3, 'Sympa à faire une fois, sans être un indispensable du genre à mes yeux.', '2026-03-09 12:43:00'),
(45, 9, 13, 3, 'Correct sans plus, ça manque d\'un vrai moment marquant selon moi.', '2026-05-31 16:16:00'),
(46, 4, 13, 1, 'Trop daté à mon goût, ça n\'a pas bien vieilli comparé à d\'autres classiques du genre.', '2026-04-19 22:17:00'),
(47, 10, 13, 5, 'Direction artistique superbe, chaque écran donne envie de s\'arrêter pour l\'admirer.', '2026-01-27 11:47:00'),
(48, 3, 14, 4, 'Le doublage donne vraiment vie aux personnages, on s\'attache très vite.', '2026-03-20 23:59:00'),
(49, 4, 14, 3, 'Sympathique dans l\'ensemble, mais deux ou trois énigmes m\'ont fait sortir un guide.', '2026-03-17 12:43:00'),
(50, 5, 14, 4, 'Les puzzles demandent de vraiment observer le décor, très satisfaisant à résoudre.', '2026-06-28 23:55:00'),
(51, 5, 15, 3, 'L\'humour ne fait pas toujours mouche mais l\'univers reste attachant.', '2026-04-13 21:42:00'),
(52, 9, 15, 1, 'Le pixel hunting permanent gâche un peu le plaisir de l\'exploration.', '2025-12-05 21:44:00'),
(53, 1, 15, 4, 'La bande-son reste en tête bien après avoir terminé le jeu.', '2026-01-06 12:52:00'),
(54, 10, 16, 2, 'Je m\'attendais à beaucoup mieux, l\'histoire ne décolle jamais vraiment.', '2026-06-02 15:43:00'),
(55, 3, 16, 2, 'Je suis resté bloqué sur une énigme totalement illogique, j\'ai fini par abandonner.', '2025-11-13 23:16:00'),
(56, 9, 16, 5, 'L\'ambiance sonore à elle seule justifie d\'y jouer, un vrai régal pour les oreilles.', '2025-11-09 18:27:00'),
(57, 9, 17, 4, 'Les puzzles demandent de vraiment observer le décor, très satisfaisant à résoudre.', '2026-04-23 10:42:00'),
(58, 10, 17, 2, 'Trop daté à mon goût, ça n\'a pas bien vieilli comparé à d\'autres classiques du genre.', '2026-02-15 17:19:00'),
(59, 1, 17, 3, 'L\'humour ne fait pas toujours mouche mais l\'univers reste attachant.', '2026-04-09 20:18:00'),
(60, 8, 18, 4, 'L\'humour est fin sans jamais tomber dans la facilité, du très bon travail d\'écriture.', '2026-07-08 18:41:00'),
(61, 1, 18, 5, 'Un jeu qui respecte l\'intelligence du joueur, aucune énigme absurde ou tirée par les cheveux.', '2026-01-02 17:30:00'),
(62, 5, 18, 3, 'Sympa à faire une fois, sans être un indispensable du genre à mes yeux.', '2025-12-10 19:05:00'),
(63, 10, 18, 5, 'Le genre d\'histoire qui reste en tête des semaines après avoir fini le jeu.', '2026-01-13 19:19:00'),
(64, 5, 19, 1, 'Trop de backtracking inutile entre les zones, ça casse le rythme.', '2026-05-26 15:15:00'),
(65, 9, 19, 4, 'J\'ai ri à voix haute devant mon écran plus d\'une fois, l\'écriture est excellente.', '2026-04-20 12:11:00'),
(66, 6, 19, 3, 'Sympa à faire une fois, sans être un indispensable du genre à mes yeux.', '2026-06-07 16:03:00'),
(67, 1, 20, 3, 'Pas mauvais, mais je m\'attendais à mieux vu la réputation du studio.', '2026-05-01 23:10:00'),
(68, 6, 20, 3, 'Les allers-retours entre les zones deviennent vite répétitifs sur la deuxième moitié.', '2026-05-03 13:48:00'),
(69, 7, 20, 5, 'Chaque personnage secondaire a sa petite histoire, le monde est incroyablement vivant.', '2026-05-21 19:15:00'),
(70, 3, 20, 4, 'Un classique qui mérite amplement sa réputation, à faire au moins une fois dans sa vie.', '2026-02-08 12:17:00'),
(71, 5, 21, 3, 'L\'histoire est intéressante mais le rythme s\'essouffle sur la fin.', '2026-04-15 15:21:00'),
(72, 1, 21, 5, 'L\'ambiance sonore à elle seule justifie d\'y jouer, un vrai régal pour les oreilles.', '2025-12-29 22:26:00'),
(73, 3, 21, 4, 'Techniquement simple mais artistiquement inspiré, une vraie réussite.', '2026-03-28 16:00:00'),
(74, 4, 22, 5, 'Un scénario qui prend son temps mais qui paie chaque rebondissement.', '2026-04-10 20:10:00'),
(75, 2, 22, 3, 'Il y a de bonnes idées, mais l\'exécution manque parfois de finition.', '2026-06-15 09:58:00'),
(76, 7, 22, 4, 'Le genre d\'histoire qui reste en tête des semaines après avoir fini le jeu.', '2026-04-22 11:55:00'),
(77, 9, 23, 3, 'Un peu court, j\'aurais aimé passer plus de temps avec ces personnages.', '2026-02-04 21:53:00'),
(78, 6, 23, 4, 'La bande-son reste en tête bien après avoir terminé le jeu.', '2025-09-24 20:34:00'),
(79, 8, 23, 5, 'Les dialogues sont écrits avec beaucoup de finesse, quel plaisir à lire.', '2025-10-20 21:41:00'),
(80, 8, 24, 4, 'Techniquement simple mais artistiquement inspiré, une vraie réussite.', '2026-01-04 16:44:00'),
(81, 3, 24, 5, 'Un vrai plaisir de retrouver ce style d\'aventure, l\'interface point-and-click est intuitive.', '2025-11-12 21:52:00'),
(82, 9, 25, 4, 'J\'ai ri à voix haute devant mon écran plus d\'une fois, l\'écriture est excellente.', '2026-02-16 22:43:00'),
(83, 8, 25, 3, 'L\'histoire est intéressante mais le rythme s\'essouffle sur la fin.', '2026-07-01 21:02:00'),
(84, 7, 25, 5, 'Le twist final m\'a scotché, je ne l\'ai pas vu venir une seule seconde.', '2026-03-23 10:32:00'),
(85, 5, 26, 3, 'Un peu court, j\'aurais aimé passer plus de temps avec ces personnages.', '2025-12-14 20:33:00'),
(86, 3, 26, 2, 'Pas pour moi, la difficulté vient plus de la logique tordue que du vrai défi.', '2026-05-11 15:52:00'),
(87, 2, 26, 3, 'Correct sans plus, ça manque d\'un vrai moment marquant selon moi.', '2026-02-27 22:15:00'),
(88, 10, 26, 3, 'L\'histoire est intéressante mais le rythme s\'essouffle sur la fin.', '2026-02-04 23:28:00'),
(89, 6, 27, 5, 'Difficile de lâcher la souris une fois lancé, j\'ai fait toute la partie en une soirée.', '2026-02-03 17:00:00'),
(90, 3, 27, 4, 'Toutes les références aux classiques LucasArts font sourire sans jamais paraître forcées.', '2026-04-11 16:35:00'),
(91, 7, 27, 3, 'Le début est excellent, la fin un peu expédiée en comparaison.', '2026-05-24 16:50:00'),
(92, 1, 28, 5, 'L\'humour est fin sans jamais tomber dans la facilité, du très bon travail d\'écriture.', '2026-06-23 14:22:00'),
(93, 9, 28, 3, 'Correct sans plus, ça manque d\'un vrai moment marquant selon moi.', '2026-02-18 13:14:00'),
(94, 3, 29, 5, 'J\'ai ri à voix haute devant mon écran plus d\'une fois, l\'écriture est excellente.', '2025-12-23 13:14:00'),
(95, 10, 29, 5, 'Les énigmes sont retorses mais jamais injustes, j\'ai adoré chaque minute.', '2026-06-15 11:17:00'),
(96, 3, 30, 5, 'Un scénario qui prend son temps mais qui paie chaque rebondissement.', '2025-12-18 09:16:00'),
(97, 7, 30, 2, 'Trop daté à mon goût, ça n\'a pas bien vieilli comparé à d\'autres classiques du genre.', '2025-10-18 15:31:00');

COMMIT;
