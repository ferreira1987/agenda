-- ----------------------------
-- Table structure for `calendario`
-- ----------------------------
DROP TABLE IF EXISTS `calendario`;
CREATE TABLE `calendario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `date_start` timestamp NOT NULL DEFAULT,
  `date_end` timestamp NOT NULL DEFAULT,
  `descricao` text,
  `usuario` int(11) NOT NULL,
  PRIMARY KEY (`id`),
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;
