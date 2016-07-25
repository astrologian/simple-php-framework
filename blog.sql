SET FOREIGN_KEY_CHECKS=0;

DROP TABLE IF EXISTS `article`;
CREATE TABLE `article` (
  `article_id` int(11) NOT NULL AUTO_INCREMENT,
  `article_name` text COLLATE utf8_unicode_ci NOT NULL,
  `article_title` text COLLATE utf8_unicode_ci NOT NULL,
  `article_content` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`article_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `article` VALUES ('1', 'example-article', 'Example article', 'Lorem ipsum');
INSERT INTO `article` VALUES ('2', 'great', 'Great', 'Lorem ipsum');
SET FOREIGN_KEY_CHECKS=1;
