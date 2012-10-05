--
-- Структура таблицы `prefix_topic_tag_group`
--

CREATE TABLE IF NOT EXISTS `prefix_topic_tag_group` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `keyword` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `enabled` int(1) unsigned NOT NULL DEFAULT '0',
  `order_num` int(3) unsigned NOT NULL DEFAULT '0',
  `extra` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `enabled` (`enabled`),
  KEY `order_num` (`order_num`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Структура таблицы `prefix_topic_tag_meta`
--

CREATE TABLE IF NOT EXISTS `prefix_topic_tag_meta` (
  `topic_tag_text` varchar(64) NOT NULL,
  `topic_tag_title` varchar(200) DEFAULT NULL,
  `topic_tag_alt_title` varchar(200) DEFAULT NULL,
  `topic_tag_meta_title` varchar(200) DEFAULT NULL,
  `topic_tag_meta_keywords` text,
  `topic_tag_meta_description` text,
  PRIMARY KEY (`topic_tag_text`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;