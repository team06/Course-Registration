CREATE TABLE IF NOT EXISTS `active_users` (
	`username` varchar(30) NOT NULL,
	`timestamp` int(11) unsigned NOT NULL,
	PRIMARY KEY (`username`)
);

CREATE TABLE IF NOT EXISTS `courses` (
	`cid` int(11) NOT NULL AUTO_INCREMENT,
	`title` varchar(100) NOT NULL,
	`number` varchar(7) NOT NULL,
	`section` varchar(3) NOT NULL,
	`time` varchar(50) NOT NULL,
	`days` varchar(5) NOT NULL,
	`credits` varchar(5) NOT NULL,
	`teacher` varchar(50) NOT NULL,
	`description` text NOT NULL,
	PRIMARY KEY (`cid`)
);

CREATE TABLE IF NOT EXISTS `dates` (
	`start_time` int(11) unsigned NOT NULL,
	`end_time` int(11) unsigned NOT NULL,
	PRIMARY KEY (`start_time`,`end_time`)
);

CREATE TABLE IF NOT EXISTS `lab` (
	`cid` int(11) NOT NULL,
	`lab` varchar(50) NOT NULL,
	`time` varchar(50) NOT NULL,
	PRIMARY KEY (`cid`)
);

CREATE TABLE IF NOT EXISTS `seats` (
	`cid` int(11) NOT NULL,
	`available` int(11) NOT NULL,
	`max` int(11) NOT NULL,
	PRIMARY KEY (`cid`)
);

CREATE TABLE IF NOT EXISTS `signups` (
	`cid` int(11) NOT NULL,
	`username` varchar(30) NOT NULL,
	`time` int(10) unsigned NOT NULL,
	PRIMARY KEY (`cid`,`username`)
);

CREATE TABLE IF NOT EXISTS `users` (
	`sid` varchar(12) NOT NULL,
	`username` varchar(30) NOT NULL,
	`password` varchar(32) DEFAULT NULL,
	`userid` varchar(32) DEFAULT NULL,
	`userlevel` tinyint(1) unsigned NOT NULL,
	`email` varchar(50) DEFAULT NULL,
	`timestamp` int(11) unsigned NOT NULL,
	`first_name` varchar(30) NOT NULL,
	`last_name` varchar(30) NOT NULL,
	`honors_status` varchar(10) NOT NULL,
	PRIMARY KEY (`username`)
);

CREATE TABLE IF NOT EXISTS `years` (
	`cid` int(11) NOT NULL,
	`semester` char(1) NOT NULL,
	`year` int(11) NOT NULL,
	PRIMARY KEY (`cid`)
);

INSERT INTO users (username, password) VALUES('admin', '21232f297a57a5a743894a0e4a801fc3');
