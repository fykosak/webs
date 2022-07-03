DROP TABLE IF EXISTS `faq`;
CREATE TABLE `faq`
(
    `faq_id`   int(11) NOT NULL AUTO_INCREMENT COMMENT 'id otazky',
    `question` text COLLATE utf8_czech_ci NOT NULL,
    `answer`   text COLLATE utf8_czech_ci NOT NULL,
    `lang`     enum('cs','en') COLLATE utf8_czech_ci NOT NULL COMMENT 'jazyk',
    `category` varchar(150) COLLATE utf8_czech_ci DEFAULT NULL COMMENT 'typ otazky',
    PRIMARY KEY (`faq_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

DROP TABLE IF EXISTS `report`;
CREATE TABLE `report`
(
    `report_id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT COMMENT 'id reportu',
    `event_id`  int(11) NOT NULL,
    `text`      text COLLATE utf8_czech_ci NOT NULL COMMENT 'obsah reportu v html',
    `lang`      enum('cs','en') COLLATE utf8_czech_ci NOT NULL COMMENT 'jazyk'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci COMMENT='reporty tymu';


DROP TABLE IF EXISTS `report_image`;
CREATE TABLE `report_image`
(
    `report_image_id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `report_id`       int(11) NOT NULL COMMENT 'id reportu',
    `caption`         varchar(150) COLLATE utf8_czech_ci DEFAULT NULL COMMENT 'popisek obrazku',
    `filename`        varchar(150) COLLATE utf8_czech_ci NOT NULL COMMENT 'nazev souboru ve slozce',
    CONSTRAINT `report_image_ibfk_1` FOREIGN KEY (`report_id`) REFERENCES `report` (`report_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci COMMENT='M:N mapovani obrazku a reportu';


DROP TABLE IF EXISTS `report_team`;
CREATE TABLE `report_team`
(
    `report_team_id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `report_id`      int(11) NOT NULL,
    `team_id`        int(11) NOT NULL,
    CONSTRAINT `report_team_fk1` FOREIGN KEY (`report_id`) REFERENCES `report` (`report_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci COMMENT='M:N mapovani reportu a tymu';
