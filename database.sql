CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `UUID` char(36) NOT NULL,
  `uid` char(10) NOT NULL,
  `name` char(30) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UUID` (`UUID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

CREATE TABLE `gifts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object` char(15) NOT NULL,
  `giver` int(11) DEFAULT NULL,
  `recipient` int(11) DEFAULT NULL,
  `donation_time` int(10) unsigned DEFAULT NULL,
  `is_taken` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `gifts_iufk_1` (`recipient`),
  KEY `gifts_iufk_2` (`giver`),
  CONSTRAINT `gifts_iufk_2` FOREIGN KEY (`giver`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `gifts_iufk_1` FOREIGN KEY (`recipient`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;