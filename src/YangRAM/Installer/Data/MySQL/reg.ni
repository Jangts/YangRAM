SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE IF NOT EXISTS `<%_dbp_%>reg_sys_appinfos` (
  `app_id` int(11) NOT NULL,
  `app_description` varchar(1000) DEFAULT ' no information',
  `app_keywords` varchar(256) NOT NULL,
  `app_authorname` char(32) NOT NULL DEFAULT 'YangRAM',
  `app_build_time` datetime NOT NULL,
  `app_last_runtime` datetime NOT NULL,
  `app_send_tips_allowed` int(11) NOT NULL DEFAULT '0',
  `app_search_by_smartian` int(11) NOT NULL DEFAULT '1',
  `app_remote_link_able` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

TRUNCATE TABLE `<%_dbp_%>reg_sys_appinfos`;
INSERT INTO `<%_dbp_%>reg_sys_appinfos` (`app_id`, `app_description`, `app_keywords`, `app_authorname`, `app_build_time`, `app_last_runtime`, `app_send_tips_allowed`, `app_search_by_smartian`, `app_remote_link_able`) VALUES
(0, ' no information', '', 'YangRAM', '2015-07-11 00:00:03', '2015-11-06 03:45:33', 0, 1, 0),
(1, ' no information', '', 'YangRAM', '2015-07-11 00:00:00', '2015-07-11 00:00:00', 0, 1, 0),
(2, ' no information', '', 'YangRAM', '2015-07-11 00:00:00', '2015-07-11 00:00:00', 0, 1, 0),
(3, ' no information', '', 'YangRAM', '2015-07-11 00:00:00', '2015-07-11 00:00:00', 0, 1, 0),
(4, ' no information', '', 'YangRAM', '2015-07-11 00:00:00', '2015-07-11 00:00:00', 0, 1, 0),
(5, ' no information', '', 'YangRAM', '2015-07-11 00:00:00', '2015-07-11 00:00:00', 0, 1, 0),
(6, ' no information', '', 'YangRAM', '2015-07-11 00:00:00', '2015-11-06 03:45:34', 0, 1, 0),
(7, ' no information', '', 'YangRAM', '2015-07-11 00:00:00', '2015-07-11 00:00:00', 0, 1, 0),
(8, ' no information', '', 'YangRAM', '2015-07-11 00:00:00', '2015-07-11 00:00:00', 0, 1, 0),
(9, ' no information', '', 'YangRAM', '2015-07-11 00:00:00', '2015-07-11 00:00:00', 0, 1, 0),
(10, ' no information', '', 'YangRAM', '2015-07-11 00:00:00', '2015-07-11 00:00:00', 0, 1, 0),
(12, ' no information', '', 'YangRAM', '2015-07-11 00:00:00', '2015-07-11 00:00:00', 0, 1, 0),
(14, ' no information', '', 'YangRAM', '2016-06-11 00:00:00', '2016-06-11 00:00:00', 0, 1, 0),
(21, ' no information', '', 'YangRAM', '2016-06-11 00:00:00', '2016-06-11 00:00:00', 0, 1, 0),
(28, ' no information', '', 'YangRAM', '2016-06-11 00:00:00', '2016-06-11 00:00:00', 0, 1, 0),
(35, ' no information', '', 'YangRAM', '2016-06-11 00:00:00', '2016-06-11 00:00:00', 0, 1, 0),
(42, ' no information', '', 'YangRAM', '2016-06-11 00:00:00', '2016-06-11 00:00:00', 0, 1, 0),
(49, ' no information', '', 'YangRAM', '2016-06-11 00:00:00', '2016-06-11 00:00:00', 0, 1, 0),
(52, ' no information', '', 'YangRAM', '2015-07-11 00:00:00', '2015-07-11 00:00:00', 0, 1, 0),
(56, ' no information', '', 'YangRAM', '2016-06-11 00:00:00', '2016-06-11 00:00:00', 0, 1, 0),
(87, ' no information', '', 'YangRAM', '2015-07-11 00:00:00', '2015-07-11 00:00:00', 0, 1, 0),
(1001, ' no information', '', 'YangRAM', '2015-07-11 00:00:00', '2015-08-17 06:58:28', 0, 1, 0),
(1002, ' no information', '', 'YangRAM', '2015-07-11 00:00:00', '2015-08-17 06:58:28', 0, 1, 0),
(1003, ' no information', '', 'YangRAM', '2015-07-11 00:00:00', '2015-07-11 00:00:00', 0, 1, 0),
(1004, ' no information', '', 'YangRAM', '2015-07-11 00:00:00', '2015-07-11 00:00:00', 0, 1, 0),
(1005, ' no information', '', 'YangRAM', '2015-07-11 00:00:00', '2015-08-17 06:58:28', 0, 1, 0),
(1006, ' no information', '', 'YangRAM', '2015-07-11 00:00:00', '2015-07-11 00:00:00', 0, 1, 0),
(1007, ' no information', '', 'YangRAM', '2015-07-11 00:00:00', '2015-07-11 00:00:00', 0, 1, 0),
(1015, ' no information', '', 'YangRAM', '2015-07-11 00:00:00', '2015-07-11 00:00:00', 0, 1, 0),
(1016, ' no information', '', 'YangRAM', '2015-07-11 00:00:00', '2015-07-11 00:00:00', 0, 1, 0),
(1017, ' no information', '', 'YangRAM', '2015-07-11 00:00:00', '2015-07-11 00:00:00', 0, 1, 0),
(1018, ' no information', '', 'YangRAM', '2015-07-11 00:00:00', '2015-07-11 00:00:00', 0, 1, 0),
(1019, ' no information', '', 'YangRAM', '2015-07-11 00:00:00', '2015-07-11 00:00:00', 0, 1, 0),
(1020, ' no information', '', 'YangRAM', '2015-07-11 00:00:00', '2015-07-11 00:00:00', 0, 1, 0),
(1021, ' no information', '', 'YangRAM', '2015-07-11 00:00:00', '2015-07-11 00:00:00', 0, 1, 0);

CREATE TABLE IF NOT EXISTS `<%_dbp_%>reg_sys_apppermissions` (
  `app_id` int(11) NOT NULL DEFAULT '0',
  `APP_ALLDB_READABLE` tinyint(4) NOT NULL DEFAULT '0',
  `APP_ALLDB_WRITEABLE` tinyint(4) NOT NULL DEFAULT '0',
  `APP_CATDB_READABLE` tinyint(4) NOT NULL DEFAULT '1',
  `APP_CATDB_WRITEABLE` tinyint(4) NOT NULL DEFAULT '1',
  `APP_CNTDB_READABLE` tinyint(4) NOT NULL DEFAULT '1',
  `APP_CNTDB_WRITEABLE` tinyint(4) NOT NULL DEFAULT '1',
  `APP_MAPDB_READABLE` tinyint(4) NOT NULL DEFAULT '1',
  `APP_MAPDB_WRITEABLE` tinyint(4) NOT NULL DEFAULT '1',
  `APP_SRCDB_READABLE` tinyint(4) NOT NULL DEFAULT '0',
  `APP_SRCDB_WRITEABLE` tinyint(4) NOT NULL DEFAULT '0',
  `APP_USRDB_READABLE` tinyint(4) NOT NULL DEFAULT '1',
  `APP_USRDB_WRITEABLE` tinyint(4) NOT NULL DEFAULT '0',
  `APP_SLFDB_READONLY` tinyint(4) NOT NULL DEFAULT '0',
  `APP_SLFDB_WRITEONLY` tinyint(4) NOT NULL DEFAULT '0',
  `APP_UPDOX_GETABLE` tinyint(4) NOT NULL DEFAULT '0',
  `APP_PDOXS_GETABLE` tinyint(4) NOT NULL DEFAULT '0',
  `APP_FOLLOW_READABLE` tinyint(4) NOT NULL DEFAULT '0',
  `APP_FOLLOW_WRITEABLE` tinyint(4) NOT NULL DEFAULT '0',
  `APP_USERFLD_WRITEABLE` tinyint(4) NOT NULL DEFAULT '0',
  `APP_USERSFLD_WRITEABLE` tinyint(4) NOT NULL DEFAULT '0',
  `APP_WALLET_READABLE` tinyint(4) NOT NULL DEFAULT '0',
  `APP_WALLET_WRITEABLE` tinyint(4) NOT NULL DEFAULT '0',
  `APP_TIPS_PUSHABLE` tinyint(4) NOT NULL DEFAULT '1',
  `APP_SOCKET_USABLE` tinyint(4) NOT NULL DEFAULT '0',
  `APP_REMOTE_GETABLE` tinyint(4) NOT NULL DEFAULT '1',
  `APP_REMOTE_POSTABLE` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `<%_dbp_%>reg_apps` (
  `app_id` int(11) NOT NULL,
  `app_name` varchar(128) NOT NULL,
  `app_abbr` char(2) NOT NULL,
  `app_icon` varchar(32) DEFAULT 'yangram-logo',
  `app_bgcolor` varchar(8) DEFAULT NULL,
  `app_authorname` char(32) NOT NULL DEFAULT 'YangRAM',
  `app_installpath` varchar(128) DEFAULT NULL,
  `app_usedb` int(11) NOT NULL DEFAULT '0',
  `app_releasetime` date NOT NULL,
  `app_count` int(11) NOT NULL DEFAULT '0',
  `app_is_ondock` int(11) NOT NULL DEFAULT '0',
  `app_is_new` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

TRUNCATE TABLE `<%_dbp_%>reg_apps`;
INSERT INTO `<%_dbp_%>reg_apps` (`app_id`, `app_name`, `app_abbr`, `app_icon`, `app_bgcolor`, `app_authorname`, `app_installpath`, `app_usedb`, `app_releasetime`, `app_count`, `app_is_ondock`, `app_is_new`) VALUES
(0, 'I4Plaza', 'Hp', 'screen-desktop', '0', 'YangRAM', 'OperatorInterface/I4Plaza/', 0, '2015-07-11', 3194, 0, 0),
(1, 'Explorer', 'De', 'compass', '0', 'YangRAM', 'Explorer/', 0, '2015-07-11', 1017, 1, 0),
(2, 'Trash Can', 'Tc', 'trash', '0', 'YangRAM', 'TrashCan/', 0, '2015-07-11', 276, 1, 0),
(3, 'Settings', 'Cp', 'settings', '0', 'YangRAM', 'ControlPanel/', 0, '2015-07-11', 449, 1, 0),
(4, 'Statistics', 'St', 'pie-chart', '0', 'YangRAM', 'Statistics/', 0, '2015-07-11', 0, 1, 0),
(5, 'Usermgr', 'Ur', 'users', '0', 'YangRAM', 'Passport/', 0, '2015-07-11', 0, 1, 0),
(6, 'Contacts', 'Ct', 'user', '0', 'YangRAM', 'Contacts/', 0, '2015-07-11', 1, 1, 0),
(7, 'Message Center', 'Mc', 'envelope', '0', 'YangRAM', 'Messager/', 0, '2015-07-11', 0, 1, 0),
(8, 'Market', 'Ym', 'bag', '0', 'YangRAM', 'Market/', 0, '2015-07-11', 7, 1, 0),
(9, 'Dataroom', 'Dt', 'feed', '0', 'YangRAM', 'Dataroom/', 0, '2015-07-11', 0, 1, 0),
(10, 'Pages', 'Yp', 'docs', '0', 'YangRAM', 'Pages/', 0, '2015-07-11', 565, 1, 0),
(12, 'Searcher', 'So', 'magnifier', '0', 'YangRAM', 'Smartian/', 0, '2016-04-08', 0, 1, 1),
(14, 'Listen', 'Ls', 'earphones', '0', 'YangRAM', 'NFA/Listen/', 0, '2016-06-11', 0, 1, 0),
(21, 'Watch', 'Wt', 'social-youtube', '0', 'YangRAM', 'NFA/Watch/', 0, '2016-06-11', 0, 1, 0),
(28, 'Read', 'Rd', '', '0', 'YangRAM', 'NFA/Read/', 0, '2016-06-11', 0, 1, 0),
(35, 'Play', 'Pl', 'game-controller', '0', 'YangRAM', 'NFA/Play/', 0, '2016-06-11', 0, 1, 0),
(42, 'News', 'Ns', 'book-open', '0', 'YangRAM', 'NFA/News/', 0, '2016-06-11', 0, 1, 0),
(49, 'Weather', 'Wr', '', '0', 'YangRAM', 'NFA/Weather/', 0, '2016-06-11', 0, 1, 0),
(52, 'Registry', 'Rg', 'key', '0', 'YangRAM', 'OperatorInterface/Registry/', 0, '2015-07-11', 0, 1, 0),
(56, 'Stock', 'Sk', 'graph', '0', 'YangRAM', 'NFA/Stock/', 0, '2016-06-11', 0, 1, 0),
(87, 'Web FTP', 'Wt', 'folder-alt', '0', 'YangRAM', 'OperatorInterface/WebFTP/', 0, '2015-07-11', 0, 1, 0),
(1001, 'Developer', 'Dv', 'dataroom-developer', '0', 'Tangram', 'Studio/Developer/', 0, '2015-07-11', 9, 1, 0),
(1002, 'Publisher', 'Pb', 'dataroom-publisher', '0', 'Tangram', 'Studio/Publisher/', 0, '2015-07-11', 1752, 1, 0),
(1003, 'Tabulater', 'Tb', 'dataroom-rapporteur', '0', 'Tangram', 'Studio/Tabulater/', 0, '2015-07-11', 12, 1, 0),
(1004, 'Bookkeeper', 'Bk', 'dataroom-investigator', '0', 'Tangram', 'Studio/Bookkeeper/', 0, '2015-07-11', 6, 1, 0),
(1005, 'Elems Manager', 'El', 'dataroom-elemsmgr', '0', 'Tangram', 'Studio/Elemsmgr/', 0, '2015-07-11', 336, 1, 0),
(1006, 'Designer', 'Ds', 'dataroom-designer', '0', 'Tangram', 'Studio/Designer/', 0, '2015-07-11', 215, 1, 0),
(1007, 'Tasker', 'St', 'dataroom-collector', '0', 'Tangram', 'Studio/Tasker/', 0, '2015-07-11', 8, 1, 0),
(1015, 'Book+', 'Bk', 'notebook', '0', 'Tangram', 'IP/BookPlus/', 0, '2015-07-11', 58, 1, 0),
(1016, 'Comment+', 'Cm', 'speech', '0', 'Tangram', 'IP/CommentPlus/', 0, '2015-07-11', 17, 1, 0),
(1017, 'Evaluate+', 'El', 'bar-chart', '0', 'Tangram', 'IP/EvaluatePlus/', 0, '2015-07-11', 17, 1, 0),
(1018, 'Like+', 'Lk', 'heart', '0', 'Tangram', 'IP/LikePlus/', 0, '2015-07-11', 0, 1, 0),
(1019, 'Support+', 'Sp', 'like', '0', 'Tangram', 'IP/SupportPlus/', 0, '2015-07-11', 0, 1, 0),
(1020, 'Favorite+', 'Fa', 'star', '0', 'Tangram', 'IP/FavoritePlus/', 0, '2015-07-11', 0, 1, 0),
(1021, 'Sign+', 'Sg', 'note', '0', 'Tangram', 'IP/SignPlus/', 0, '2015-07-11', 0, 1, 0);

CREATE TABLE IF NOT EXISTS `<%_dbp_%>reg_cat_folders` (
  `id` int(11) NOT NULL,
  `name` varchar(64) DEFAULT NULL,
  `parent` int(11) DEFAULT '0',
  `KEY_IS_RECYCLED` int(11) NOT NULL DEFAULT '0',
  `KEY_IS_READONLY` int(11) NOT NULL DEFAULT '0',
  `KEY_MTIME` datetime NOT NULL,
  `usr_id` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

TRUNCATE TABLE `<%_dbp_%>reg_cat_folders`;
INSERT INTO `<%_dbp_%>reg_cat_folders` (`id`, `name`, `parent`, `KEY_IS_RECYCLED`, `KEY_IS_READONLY`, `KEY_MTIME`, `usr_id`) VALUES
(1, 'System', 0, 0, 1, '2015-12-30 00:00:00', 0),
(2, 'Sample', 1, 0, 1, '2015-12-30 00:00:00', 0),
(3, 'UserAvatars', 1, 0, 1, '2015-12-30 00:00:00', 0),
(4, 'Applications', 0, 0, 1, '2016-01-04 09:50:36', 0),
(5, 'OurDocuments', 0, 0, 1, '2015-12-31 22:26:33', 0),
(6, 'UPLOADER', 4, 0, 1, '2016-01-09 20:08:45', 0);

CREATE TABLE IF NOT EXISTS `<%_dbp_%>reg_cat_languages` (
  `lct_id` int(11) NOT NULL,
  `lang` char(5) NOT NULL,
  `name` varchar(50) NOT NULL,
  `owner` varchar(60) NOT NULL,
  `addr` varchar(256) NOT NULL,
  `brief` longtext NOT NULL,
  `remark` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

TRUNCATE TABLE `<%_dbp_%>reg_cat_languages`;
INSERT INTO `<%_dbp_%>reg_cat_languages` (`lct_id`, `lang`, `name`, `owner`, `addr`, `brief`, `remark`) VALUES
(1, 'en-us', 'Your Tangram', 'your name', 'no content', 'no content', 'no content');

CREATE TABLE IF NOT EXISTS `<%_dbp_%>reg_cat_locations` (
  `id` int(11) NOT NULL,
  `tel1` varchar(24) DEFAULT NULL,
  `tel2` varchar(20) DEFAULT NULL,
  `email` varchar(256) NOT NULL,
  `lng` float(9,6) NOT NULL DEFAULT '0.000000',
  `lat` float(9,6) NOT NULL DEFAULT '0.000000'
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

TRUNCATE TABLE `<%_dbp_%>reg_cat_locations`;
INSERT INTO `<%_dbp_%>reg_cat_locations` (`id`, `tel1`, `tel2`, `email`, `lng`, `lat`) VALUES
(1, '12345678', '12345678', 'yourname@abc.com', 113.276245, 23.188196);

CREATE TABLE IF NOT EXISTS `<%_dbp_%>map_appthemes` (
  `app_id` int(11) NOT NULL DEFAULT '10',
  `thm_alias` char(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

TRUNCATE TABLE `<%_dbp_%>map_appthemes`;
INSERT INTO `<%_dbp_%>map_appthemes` (`app_id`, `thm_alias`) VALUES
(10, 'default');

CREATE TABLE IF NOT EXISTS `<%_dbp_%>reg_map_spctags` (
  `tag_alias` char(255) NOT NULL,
  `set_alias` char(32) NOT NULL,
  `ctt_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `<%_dbp_%>map_messages` (
  `id` int(11) NOT NULL,
  `usr_id` int(11) NOT NULL,
  `msg_id` int(11) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `<%_dbp_%>reg_map_usr_relations` (
  `id` int(11) NOT NULL,
  `usr_id` int(11) NOT NULL,
  ` relational_uid` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `f_group_id` int(11) NOT NULL,
  `rf_group_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `<%_dbp_%>reg_spc_categories` (
  `id` int(11) NOT NULL,
  `set_id` int(11) DEFAULT NULL,
  `parent` int(11) NOT NULL DEFAULT '0',
  `name` varchar(32) DEFAULT NULL,
  `title` char(64) DEFAULT NULL,
  `keywords` varchar(64) DEFAULT NULL,
  `description` varchar(256) DEFAULT NULL,
  `top_display_num` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

TRUNCATE TABLE `<%_dbp_%>reg_spc_categories`;
INSERT INTO `<%_dbp_%>reg_spc_categories` (`id`, `set_id`, `parent`, `name`, `title`, `keywords`, `description`, `top_display_num`) VALUES
(1, 1, 0, '系统文章', '系统文章', '', '', 1);

CREATE TABLE IF NOT EXISTS `<%_dbp_%>reg_spc_fields` (
  `id` int(11) NOT NULL,
  `field` char(128) NOT NULL,
  `name` varchar(128) NOT NULL,
  `field_type` char(16) NOT NULL DEFAULT 'varchar',
  `input_type` char(16) NOT NULL DEFAULT 'text',
  `default_value` longtext,
  `file_type` char(32) DEFAULT NULL,
  `row_num` int(11) NOT NULL DEFAULT '0',
  `option_value` longtext,
  `option_name` longtext,
  `tips` varchar(512) DEFAULT NULL,
  `sort` int(11) NOT NULL,
  `set_id` int(11) NOT NULL DEFAULT '0',
  `KEY_STATE` tinyint(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `<%_dbp_%>reg_spc_formats` (
  `id` int(11) NOT NULL,
  `set_id` int(11) NOT NULL,
  `MIME` varchar(50) NOT NULL,
  `template` longtext NOT NULL,
  `KEY_LIMIT` int(11) NOT NULL,
  `KEY_STATE` int(11) NOT NULL,
  `token` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `<%_dbp_%>reg_spc_presets` (
  `id` int(11) NOT NULL,
  `basic_type` char(4) NOT NULL DEFAULT 'arti',
  `name` varchar(128) NOT NULL,
  `alias` char(32) NOT NULL,
  `code` char(5) NOT NULL DEFAULT 'DEMO',
  `item_type` varchar(128) NOT NULL,
  `item_unit` varchar(32) DEFAULT NULL,
  `note` varchar(64) DEFAULT 'Manage Contens',
  `theme` char(128) NOT NULL DEFAULT 'default',
  `template` varchar(512) DEFAULT NULL,
  `contribute` int(11) NOT NULL DEFAULT '0',
  `nonaudit` int(11) NOT NULL DEFAULT '1',
  `top_display_num` int(11) NOT NULL DEFAULT '2',
  `app_id` int(11) NOT NULL DEFAULT '1',
  `KEY_STATE` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

TRUNCATE TABLE `<%_dbp_%>reg_spc_presets`;
INSERT INTO `<%_dbp_%>reg_spc_presets` (`id`, `basic_type`, `name`, `alias`, `code`, `item_type`, `item_unit`, `note`, `theme`, `template`, `contribute`, `nonaudit`, `top_display_num`, `app_id`, `KEY_STATE`) VALUES
(1, 'arti', '文章', 'articles', 'ARTI', '文章', '', '发布资讯文章', 'research', 'arti_content.php', 0, 0, 2, 1002, 0);

CREATE TABLE IF NOT EXISTS `<%_dbp_%>reg_src_notes` (
  `id` int(11) NOT NULL,
  `type` int(11) NOT NULL DEFAULT '1',
  `groupname` varchar(64) NOT NULL DEFAULT '',
  `name` varchar(32) NOT NULL,
  `label` char(16) NOT NULL,
  `code` longtext NOT NULL,
  `KEY_IS_RECYCLED` int(11) NOT NULL DEFAULT '0',
  `KEY_MTIME` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `<%_dbp_%>reg_src_themes` (
  `id` int(11) NOT NULL,
  `alias` char(16) NOT NULL,
  `type` int(11) NOT NULL,
  `opn_id` int(11) DEFAULT NULL,
  `name` varchar(64) NOT NULL,
  `description` varchar(256) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

TRUNCATE TABLE `<%_dbp_%>reg_src_themes`;
INSERT INTO `<%_dbp_%>reg_src_themes` (`id`, `alias`, `type`, `opn_id`, `name`, `description`) VALUES
(1, 'default', 1, NULL, '默认模板', '适用于FrontPages,Ip套件');


ALTER TABLE `<%_dbp_%>reg_sys_appinfos`
  ADD UNIQUE KEY `app_id` (`app_id`);

ALTER TABLE `<%_dbp_%>reg_sys_apppermissions`
  ADD UNIQUE KEY `app_id` (`app_id`);

ALTER TABLE `<%_dbp_%>reg_apps`
  ADD UNIQUE KEY `app_id` (`app_id`);

ALTER TABLE `<%_dbp_%>reg_cat_folders`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `<%_dbp_%>reg_cat_languages`
  ADD PRIMARY KEY (`lang`),
  ADD KEY `lct_id` (`lct_id`);

ALTER TABLE `<%_dbp_%>reg_cat_locations`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `<%_dbp_%>map_appthemes`
  ADD UNIQUE KEY `ALIAS` (`app_id`,`thm_alias`);

ALTER TABLE `<%_dbp_%>reg_map_spctags`
  ADD PRIMARY KEY (`tag_alias`,`ctt_id`);

ALTER TABLE `<%_dbp_%>map_messages`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `<%_dbp_%>reg_map_usr_relations`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `<%_dbp_%>reg_spc_categories`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `<%_dbp_%>reg_spc_fields`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `<%_dbp_%>reg_spc_formats`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `<%_dbp_%>reg_spc_presets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `SET_Alias` (`alias`);

ALTER TABLE `<%_dbp_%>reg_src_notes`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `<%_dbp_%>reg_src_themes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `alias` (`alias`);


ALTER TABLE `<%_dbp_%>reg_cat_folders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
ALTER TABLE `<%_dbp_%>reg_cat_locations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
ALTER TABLE `<%_dbp_%>reg_spc_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
ALTER TABLE `<%_dbp_%>reg_spc_fields`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `<%_dbp_%>reg_spc_formats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `<%_dbp_%>reg_spc_presets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
ALTER TABLE `<%_dbp_%>reg_src_notes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `<%_dbp_%>reg_src_themes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;

ALTER TABLE `<%_dbp_%>reg_sys_apppermissions`
  ADD CONSTRAINT `<%_DBP_%>sys_permissions_ibfk_1` FOREIGN KEY (`app_id`) REFERENCES `<%_dbp_%>reg_apps` (`app_id`);

ALTER TABLE `<%_dbp_%>reg_cat_languages`
  ADD CONSTRAINT `<%_DBP_%>reg_cat_languages_ibfk_1` FOREIGN KEY (`lct_id`) REFERENCES `<%_dbp_%>reg_cat_locations` (`id`);
