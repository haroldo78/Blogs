CREATE TABLE IF NOT EXISTS `#__egoltvoting` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`service` int(3) NOT NULL,
	`cid` int(11) NOT NULL,
	`lastip` varchar(50) NOT NULL,
	`lastdate` datetime NOT NULL,
	`counter` int(10) NOT NULL DEFAULT '0',
	`sum` int(10) NOT NULL DEFAULT '0',
	PRIMARY KEY (`id`),
	KEY `egoltvoting_idx` (`cid`)
);

CREATE TABLE IF NOT EXISTS `#__egoltvoting_logs` (
	`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	`service` int(3) NOT NULL,
	`cid` int(11) NOT NULL,
	`logip` varchar(50) NOT NULL,
	`logdate` datetime NOT NULL,
	`rate` int(2) NOT NULL,
	PRIMARY KEY (`id`),
	KEY `egoltvoting_idx` (`cid`)
);
	
INSERT INTO `#__egoltvoting` (`service`, `cid`, `lastip`, `lastdate`, `sum`, `counter`) 
SELECT 1, `content_id`, `lastip`, NOW(), `rating_sum`, `rating_count` from `#__content_rating`;
 