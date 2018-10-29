INSERT INTO t VALUES(UUID());

To work around the problem, do this instead:

SET @my_uuid = UUID();
INSERT INTO t VALUES(@my_uuid);




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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;