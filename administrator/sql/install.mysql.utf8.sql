CREATE TABLE IF NOT EXISTS `#__gform_steps` (  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,  `next_step` varchar(255) NOT NULL,  `ordering` int(11) NOT NULL,  `state` tinyint(1) NOT NULL DEFAULT '1',  `checked_out` int(11) NOT NULL,  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',  `created_by` int(11) NOT NULL,  `html` text NOT NULL,  `countdown` int(11) NOT NULL,  `title` varchar(255) NOT NULL,  `showTitle` int(2) NOT NULL,  PRIMARY KEY (`id`)) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;