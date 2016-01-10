CREATE TABLE IF NOT EXISTS `events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `start` datetime DEFAULT NULL,
  `end` datetime DEFAULT NULL,
  `type` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`id`)
) AUTO_INCREMENT=14;

INSERT INTO `events` (`id`, `start`, `end`, `type`) VALUES


