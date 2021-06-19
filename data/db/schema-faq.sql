DROP TABLE IF EXISTS `faq`;
CREATE TABLE `faq` (
  `faq_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id otazky',
  `question` text COLLATE utf8_czech_ci NOT NULL,
  `answer` text COLLATE utf8_czech_ci NOT NULL,
  `lang` enum('cs','en') COLLATE utf8_czech_ci NOT NULL COMMENT 'jazyk',
  `category` varchar(150) COLLATE utf8_czech_ci DEFAULT NULL COMMENT 'typ otazky',
  PRIMARY KEY (`faq_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;