CREATE TABLE IF NOT EXISTS `currentTtns` (
  `terminal` int(10) unsigned NOT NULL,
  `ttn` varchar(400) NOT NULL,
  `ttnAbout` varchar(400) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `relayStatus` (
  `ip` varchar(250) DEFAULT NULL,
  `register` int(10) DEFAULT NULL,
  `value` int(10) DEFAULT NULL,
  `date` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `sessions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) NOT NULL,
  `terminal` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `settings` (
  `name` varchar(50) DEFAULT NULL,
  `value` varchar(100) DEFAULT NULL,
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `terminals` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `relayIp` varchar(200) DEFAULT NULL,
  `gateIn` int(11) NOT NULL DEFAULT '0',
  `gateOut` int(11) NOT NULL DEFAULT '0',
  `stateIn` int(11) NOT NULL DEFAULT '0',
  `stateOut` int(11) NOT NULL DEFAULT '0',
  `normDirection` tinyint(1) NOT NULL DEFAULT '0',
  `ip` varchar(255) NOT NULL DEFAULT '',
  `plateRecIn` int(11) DEFAULT '0',
  `plateRecOut` int(11) DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `realPassword` varchar(50) NOT NULL,
  `role` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `login` (`login`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `visits` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(250) NOT NULL,
  `auto` varchar(100) NOT NULL,
  `weight` int(11) NOT NULL,
  `weightA` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `terminal` int(11) NOT NULL,
  `direction` int(11) NOT NULL,
  `dateStart` int(11) NOT NULL,
  `dateEnd` int(11) NOT NULL,
  `close` tinyint(1) NOT NULL DEFAULT '0',
  `seal` varchar(250) DEFAULT NULL,
  `autoWeit` int(100) DEFAULT NULL,
  `itemWeit` int(100) DEFAULT NULL,
  `planWeight` int(100) DEFAULT NULL,
  `autoType` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `visits`
    ADD INDEX `close_index` (`close`) USING BTREE;

CREATE TABLE `warehouseCargos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ts` int(10) unsigned NOT NULL,
  `tsEnd` int(10) unsigned DEFAULT '0',
  `terminal` int(10) unsigned NOT NULL,
  `side` int(10) unsigned NOT NULL,
  `barcode` varchar(255) NOT NULL,
  `weight` int(10) unsigned NOT NULL,
  `force` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `warehouseSituation` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `terminal` int(10) unsigned NOT NULL,
  `side` int(10) unsigned NOT NULL,
  `barrierFrontRaised` int(10) unsigned NOT NULL,
  `barrierFrontLocked` int(10) unsigned NOT NULL,
  `barrierRearRaised` int(10) unsigned NOT NULL,
  `barrierRearLocked` int(10) unsigned NOT NULL,
  `weightOnScales` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `weightStatus` (
  `ip` varchar(250) DEFAULT NULL,
  `value` int(10) DEFAULT NULL,
  `date` int(10) DEFAULT NULL,
  UNIQUE KEY `ip` (`ip`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `log_calls` (
 `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
 `resource` VARCHAR(150) NULL DEFAULT NULL,
 `activity` VARCHAR(150) NULL DEFAULT NULL,
 `sended_time` DATETIME NULL DEFAULT NULL,
 `sended_data` VARCHAR(5000) NULL DEFAULT NULL,
 `received_time` DATETIME NULL DEFAULT NULL,
 `received_data` VARCHAR(5000) NULL DEFAULT NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


INSERT INTO `users` (`id`, `login`, `password`, `role`) VALUES
(1, 'root', '', 1);

INSERT INTO `terminals` (`id`, `relayIp`, `gateIn`, `gateOut`, `stateIn`, `stateOut`, `normDirection`, `ip`, `plateRecIn`, `plateRecOut`, `name`) VALUES
(1, '192.168.1.101', 0, 1, 512, 513, 1, '192.168.1.201', 3, 4, 'Шлюз №1'),
(2, '192.168.1.102', 1, 0, 513, 512, 0, '192.168.1.202', 1, 2, 'Шлюз №2'),
(3, '192.168.1.103', 0, 1, 512, 513, 1, '192.168.1.203', 5, 6, 'Шлюз №3');

INSERT INTO `settings` (`name`, `value`) VALUES
	('navision', '192.168.1.51:7047');