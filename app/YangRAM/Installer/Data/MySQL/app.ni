SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE IF NOT EXISTS `<%_dbp_%>app_a2_map` (
  `id` int(11) NOT NULL,
  `item_type` varchar(20) NOT NULL,
  `database_table` varchar(50) NOT NULL,
  `index_field` varchar(30) NOT NULL DEFAULT 'id',
  `title_field` varchar(30) NOT NULL,
  `recycled_state_field` varchar(30) NOT NULL DEFAULT 'KEY_IS_RECYCLED',
  `recycled_time_field` varchar(30) NOT NULL DEFAULT 'KEY_MTIME',
  `KEY_STATE` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `<%_dbp_%>app_a5_pages` (
  `id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `theme` char(128) DEFAULT NULL,
  `template` varchar(512) DEFAULT NULL,
  `description` varchar(256) DEFAULT NULL,
  `KEY_STATE` int(11) NOT NULL DEFAULT '1',
  `KEY_IS_RECYCLED` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `<%_dbp_%>app_a5_wedgits` (
  `app_id` int(11) NOT NULL,
  `api_link_alias` varchar(32) NOT NULL,
  `api_home_url` varchar(256) NOT NULL,
  `api_post_get` varchar(256) NOT NULL,
  `api_post_url` varchar(256) NOT NULL,
  `api_on_nav` tinyint(1) NOT NULL DEFAULT '1',
  `KEY_STATE` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `<%_dbp_%>app_a10_pages` (
  `pid` int(11) NOT NULL,
  `type` int(11) NOT NULL DEFAULT '1',
  `name` varchar(32) NOT NULL,
  `title` char(64) NOT NULL,
  `keywords` varchar(64) DEFAULT NULL,
  `description` varchar(256) DEFAULT NULL,
  `prepage` int(11) NOT NULL DEFAULT '8',
  `sort_order` int(11) NOT NULL DEFAULT '3',
  `dir` varchar(512) DEFAULT '/',
  `theme` char(128) NOT NULL DEFAULT 'default',
  `template` varchar(512) NOT NULL DEFAULT '',
  `remark` longtext,
  `KEY_IS_RECYCLED` int(11) NOT NULL DEFAULT '0',
  `KEY_COUNT` int(11) NOT NULL DEFAULT '0',
  `KEY_CTIME` datetime NOT NULL,
  `KEY_MTIME` datetime NOT NULL,
  `KEY_LIMIT` int(11) NOT NULL DEFAULT '0',
  `KEY_STATE` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `<%_dbp_%>app_a12_pages` (
  `pid` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `cover` char(255) DEFAULT NULL,
  `theme` char(128) DEFAULT NULL,
  `template` varchar(512) DEFAULT NULL,
  `initial` varchar(512) DEFAULT NULL,
  `description` varchar(256) DEFAULT NULL,
  `KEY_STATE` int(11) NOT NULL DEFAULT '1',
  `KEY_IS_RECYCLED` int(11) NOT NULL DEFAULT '0',
  `prepage` int(11) NOT NULL DEFAULT '12',
  `query_key` char(16) NOT NULL DEFAULT 'keywords'
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

TRUNCATE TABLE `<%_dbp_%>app_a12_pages`;
INSERT INTO `<%_dbp_%>app_a12_pages` (`pid`, `name`, `cover`, `theme`, `template`, `initial`, `description`, `KEY_STATE`, `KEY_IS_RECYCLED`, `prepage`, `query_key`) VALUES
(1, '通用搜索頁', NULL, 'default', 'search_result.php', 'search_init.php', NULL, 1, 0, 12, 'keywords');


ALTER TABLE `<%_dbp_%>app_a2_map`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `Recycle_Item` (`item_type`);

ALTER TABLE `<%_dbp_%>app_a5_pages`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `<%_dbp_%>app_a5_wedgits`
  ADD PRIMARY KEY (`api_link_alias`);

ALTER TABLE `<%_dbp_%>app_a10_pages`
  ADD PRIMARY KEY (`pid`);

ALTER TABLE `<%_dbp_%>app_a12_pages`
  ADD PRIMARY KEY (`pid`);


ALTER TABLE `<%_dbp_%>app_a2_map`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `<%_dbp_%>app_a5_pages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `<%_dbp_%>app_a10_pages`
  MODIFY `pid` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `<%_dbp_%>app_a12_pages`
  MODIFY `pid` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
