CREATE TABLE IF NOT EXISTS `mc_cashback_requests` (
  `id` varchar(36) NOT NULL,
  `model` varchar(128) NOT NULL,
  `foreign_key` varchar(36) NOT NULL,
  `price` double NOT NULL,
  `description` text,
  `extra` text,
  `processed` datetime DEFAULT NULL,
  `deleted` datetime DEFAULT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `belongsTo` (`model`,`foreign_key`),
  KEY `processed` (`processed`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- テーブルの構造 `mc_virtual_moneys`
--

CREATE TABLE IF NOT EXISTS `mc_virtual_moneys` (
  `id` varchar(36) NOT NULL,
  `model` varchar(128) NOT NULL,
  `foreign_key` varchar(36) NOT NULL,
  `price` double NOT NULL,
  `description` text,
  `extra` text,
  `deleted` datetime DEFAULT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `belongsTo` (`model`,`foreign_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
