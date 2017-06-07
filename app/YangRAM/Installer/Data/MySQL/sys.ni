SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE IF NOT EXISTS `<%_dbp_%>msg_server` (
  `id` int(11) NOT NULL,
  `sender` varchar(50) DEFAULT NULL,
  `title` varchar(200) DEFAULT NULL,
  `content` longtext,
  `time` datetime DEFAULT NULL,
  `url` varchar(256) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `<%_dbp_%>msg_u0` (
  `id` int(11) NOT NULL,
  `usr_id` int(11) NOT NULL,
  `sender` int(11) NOT NULL,
  `title` varchar(200) DEFAULT NULL,
  `content` longtext,
  `sendtime` datetime NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `KEY_IS_RECYCLED` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `<%_dbp_%>msg_u1` (
  `id` int(11) NOT NULL,
  `usr_id` int(11) NOT NULL,
  `sender` int(11) NOT NULL,
  `title` varchar(200) DEFAULT NULL,
  `content` longtext,
  `sendtime` datetime NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `KEY_IS_RECYCLED` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `<%_dbp_%>msg_u2` (
  `id` int(11) NOT NULL,
  `usr_id` int(11) NOT NULL,
  `sender` int(11) NOT NULL,
  `title` varchar(200) DEFAULT NULL,
  `content` longtext,
  `sendtime` datetime NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `KEY_IS_RECYCLED` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `<%_dbp_%>msg_u3` (
  `id` int(11) NOT NULL,
  `usr_id` int(11) NOT NULL,
  `sender` int(11) NOT NULL,
  `title` varchar(200) DEFAULT NULL,
  `content` longtext,
  `sendtime` datetime NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `KEY_IS_RECYCLED` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `<%_dbp_%>msg_u4` (
  `id` int(11) NOT NULL,
  `usr_id` int(11) NOT NULL,
  `sender` int(11) NOT NULL,
  `title` varchar(200) DEFAULT NULL,
  `content` longtext,
  `sendtime` datetime NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `KEY_IS_RECYCLED` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `<%_dbp_%>msg_u5` (
  `id` int(11) NOT NULL,
  `usr_id` int(11) NOT NULL,
  `sender` int(11) NOT NULL,
  `title` varchar(200) DEFAULT NULL,
  `content` longtext,
  `sendtime` datetime NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `KEY_IS_RECYCLED` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `<%_dbp_%>msg_u6` (
  `id` int(11) NOT NULL,
  `usr_id` int(11) NOT NULL,
  `sender` int(11) NOT NULL,
  `title` varchar(200) DEFAULT NULL,
  `content` longtext,
  `sendtime` datetime NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `KEY_IS_RECYCLED` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `<%_dbp_%>msg_u7` (
  `id` int(11) NOT NULL,
  `usr_id` int(11) NOT NULL,
  `sender` int(11) NOT NULL,
  `title` varchar(200) DEFAULT NULL,
  `content` longtext,
  `sendtime` datetime NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `KEY_IS_RECYCLED` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `<%_dbp_%>msg_u8` (
  `id` int(11) NOT NULL,
  `usr_id` int(11) NOT NULL,
  `sender` int(11) NOT NULL,
  `title` varchar(200) DEFAULT NULL,
  `content` longtext,
  `sendtime` datetime NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `KEY_IS_RECYCLED` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `<%_dbp_%>msg_u9` (
  `id` int(11) NOT NULL,
  `usr_id` int(11) NOT NULL,
  `sender` int(11) NOT NULL,
  `title` varchar(200) DEFAULT NULL,
  `content` longtext,
  `sendtime` datetime NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `KEY_IS_RECYCLED` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `<%_dbp_%>sys_accounts` (
  `DOMAIN` varchar(256) NOT NULL,
  `ACCOUNT` varchar(256) NOT NULL,
  `PASSWORD` varchar(128) NOT NULL,
  `TOKEN` varchar(512) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `<%_dbp_%>sys_channels` (
  `ID` int(11) NOT NULL,
  `NAME` varchar(64) NOT NULL,
  `LANGUAGE` varchar(5) NOT NULL DEFAULT 'multi',
  `HEADER` longtext NOT NULL,
  `DESCRIPTION` varchar(128) NOT NULL,
  `LOGO` varchar(512) NOT NULL,
  `FOOTER` longtext NOT NULL,
  `DOMAIN` varchar(512) NOT NULL,
  `BRIEF` char(32) NOT NULL DEFAULT 'A Channel, Subsite or Terminal.'
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

TRUNCATE TABLE `<%_dbp_%>sys_channels`;
INSERT INTO `<%_dbp_%>sys_channels` (`ID`, `NAME`, `LANGUAGE`, `HEADER`, `DESCRIPTION`, `LOGO`, `FOOTER`, `DOMAIN`, `BRIEF`) VALUES
(1, 'Default', 'multi', '', '', '', '', '', '默认频道，数据站的首选频道<br>就单频道站点而言，即是整个站点');

CREATE TABLE IF NOT EXISTS `<%_dbp_%>sys_columns` (
  `ID` int(11) NOT NULL,
  `CHANNEL` int(11) NOT NULL DEFAULT '1',
  `PARENT` int(11) NOT NULL DEFAULT '0',
  `ALIAS` char(32) NOT NULL,
  `NAME` varchar(64) NOT NULL,
  `URL` varchar(256) NOT NULL,
  `KEYWORDS` varchar(64) NOT NULL,
  `DESCRIPTION` varchar(128) NOT NULL,
  `HEADER` longtext NOT NULL,
  `FOOTER` longtext NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

TRUNCATE TABLE `<%_dbp_%>sys_columns`;
INSERT INTO `<%_dbp_%>sys_columns` (`ID`, `CHANNEL`, `PARENT`, `ALIAS`, `NAME`, `URL`, `KEYWORDS`, `DESCRIPTION`, `HEADER`, `FOOTER`) VALUES
(1, 1, 0, 'default', 'Root', '/', '', '', '', '');

CREATE TABLE IF NOT EXISTS `<%_dbp_%>sys_operators` (
  `UID` int(11) NOT NULL,
  `OPERATORNAME` varchar(30) NOT NULL,
  `CAPTCHA` varchar(50) NOT NULL,
  `OGROUP` int(11) NOT NULL DEFAULT '1',
  `AVATAR` varchar(300) DEFAULT NULL,
  `LANGUAGE` varchar(5) DEFAULT NULL,
  `PIN` char(6) NOT NULL DEFAULT '262144'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

TRUNCATE TABLE `<%_dbp_%>sys_operators`;
INSERT INTO `<%_dbp_%>sys_operators` (`UID`, `OPERATORNAME`, `CAPTCHA`, `OGROUP`, `AVATAR`, `LANGUAGE`, `PIN`) VALUES
(1, 'OS', '0192023a7bbd73250516f069df18b500', 1, '/o/files/img/cc26775220c32188228.jpg', '', '262144');

CREATE TABLE IF NOT EXISTS `<%_dbp_%>sys_routemap` (
  `ID` int(11) NOT NULL,
  `MAP_ID` int(11) NOT NULL DEFAULT '0',
  `HDL_TYPE` varchar(3) NOT NULL DEFAULT 'APP',
  `HDL_ID` int(11) NOT NULL DEFAULT '10',
  `SORT` int(11) NOT NULL,
  `HLD_OK` int(11) NOT NULL DEFAULT '0',
  `TYPE` int(11) NOT NULL DEFAULT '2',
  `PATTERN` varchar(512) NOT NULL,
  `DOMAINS` varchar(256) DEFAULT NULL,
  `DIR_ALIASES` varchar(256) NOT NULL DEFAULT 'host',
  `PRM_NAMES` varchar(256) DEFAULT NULL,
  `COL_ALIAS` varchar(32) DEFAULT '_FREE_PAGE_',
  `GRP_CODE` varchar(64) DEFAULT NULL,
  `SET_ALIAS` varchar(32) DEFAULT NULL,
  `CAT_ID` int(11) NOT NULL DEFAULT '-1',
  `DEFAULTS` longtext,
  `KEY_STATE` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `<%_dbp_%>sys_users` (
  `APPLICABLE_LANG` varchar(5) NOT NULL,
  `GUEST_GROUP_NAME` varchar(100) NOT NULL,
  `GUEST_PRONOUN` varchar(100) NOT NULL,
  `GUEST_NICKNAME` varchar(100) NOT NULL,
  `GUEST_AVATAR` varchar(300) NOT NULL,
  `DEFAULT_USER_AVATAR` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

TRUNCATE TABLE `<%_dbp_%>sys_users`;
INSERT INTO `<%_dbp_%>sys_users` (`APPLICABLE_LANG`, `GUEST_GROUP_NAME`, `GUEST_PRONOUN`, `GUEST_NICKNAME`, `GUEST_AVATAR`, `DEFAULT_USER_AVATAR`) VALUES
('en-HK', 'Guests', 'Guest', 'Guest', YangRAM.RequestDIR + 'files/img/1ca28525a8b386236136.jpg', YangRAM.RequestDIR + 'files/img/4b8512215a30cac5346.jpg'),
('en-UK', 'Guests', 'Guest', 'Guest', YangRAM.RequestDIR + 'files/img/1ca28525a8b386236136.jpg', YangRAM.RequestDIR + 'files/img/4b8512215a30cac5346.jpg'),
('en-US', 'Guests', 'Guest', 'Guest', YangRAM.RequestDIR + 'files/img/1ca28525a8b386236136.jpg', YangRAM.RequestDIR + 'files/img/4b8512215a30cac5346.jpg'),
('zh-CN', '来宾组', '来宾', '游客', YangRAM.RequestDIR + 'files/img/1ca28525a8b386236136.jpg', YangRAM.RequestDIR + 'files/img/4b8512215a30cac5346.jpg');

CREATE TABLE IF NOT EXISTS `<%_dbp_%>usr_accounts` (
  `uid` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `username` varchar(20) NOT NULL,
  `unicodename` varchar(32) NOT NULL,
  `nickname` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `mobile` varchar(20) DEFAULT NULL,
  `avatar` longtext NOT NULL,
  `password` varchar(32) NOT NULL,
  `authorization_code` varchar(40) DEFAULT 'd41d8cd98f00b204e9800998ecf8427e',
  `regtime` datetime NOT NULL,
  `lasttime` datetime NOT NULL,
  `remark` varchar(150) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

TRUNCATE TABLE `<%_dbp_%>usr_accounts`;
INSERT INTO `<%_dbp_%>usr_accounts` (`uid`, `status`, `username`, `unicodename`, `nickname`, `email`, `mobile`, `avatar`, `password`, `authorization_code`, `regtime`, `lasttime`, `remark`) VALUES
(1, 0, 'specialist', '运营专员', 'Specialist', NULL, NULL, '/o/files/img/cc26775220c32188228.jpg', '9188fd3d1405c6b80d86b35689a58614', 'd41d8cd98f00b204e9800998ecf8427e', '2015-07-12 18:17:37', '2015-07-12 18:17:37', '这个家伙很懒'),
(2, 0, 'assistant', '运营助理', 'Assistant', NULL, NULL, '/o/files/img/cc26775220c32188228.jpg', '9188fd3d1405c6b80d86b35689a58614', 'd41d8cd98f00b204e9800998ecf8427e', '2015-07-12 18:17:37', '2015-07-12 18:17:37', '这个家伙很懒'),
(3, 0, 'financialcontroller', '财务主管', 'Financial Controller', NULL, NULL, '/o/files/img/cc26775220c32188228.jpg', '9188fd3d1405c6b80d86b35689a58614', 'd41d8cd98f00b204e9800998ecf8427e', '2015-07-12 18:17:37', '2015-07-12 18:17:37', '这个家伙很懒');

CREATE TABLE IF NOT EXISTS `<%_dbp_%>usr_associated_accounts` (
  `uid` int(11) NOT NULL DEFAULT '0',
  `code` varchar(7) NOT NULL,
  `oid` varchar(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `<%_dbp_%>usr_events` (
  `usr_id` int(11) NOT NULL,
  `KEY_CTIME` datetime NOT NULL,
  `SYS_EXCTIME` datetime NOT NULL,
  `starttime` datetime NOT NULL,
  `endtime` datetime NOT NULL,
  `repeat_type` int(11) NOT NULL,
  `kld_type` int(11) NOT NULL,
  `title` varchar(30) NOT NULL,
  `remark` varchar(200) NOT NULL,
  `notice_type` int(2) NOT NULL,
  `url` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `<%_dbp_%>usr_followgroup` (
  `id` int(11) NOT NULL,
  `groupname` varchar(50) NOT NULL,
  `sort` int(11) NOT NULL,
  `usr_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `<%_dbp_%>usr_guest` (
  `id` int(11) NOT NULL,
  `usr_id` int(11) NOT NULL,
  `gst_id` int(11) NOT NULL DEFAULT '0',
  `app_id` int(11) NOT NULL DEFAULT '10',
  `col_id` int(11) NOT NULL,
  `uri` varchar(512) NOT NULL,
  `accesstime` datetime NOT NULL,
  `is_mobile` int(11) NOT NULL DEFAULT '0',
  `ip` varchar(15) NOT NULL,
  `is_new` int(11) NOT NULL,
  `source` varchar(512) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `<%_dbp_%>usr_informations` (
  `uid` int(11) NOT NULL,
  `realname` varchar(60) DEFAULT NULL,
  `firstname` varchar(20) DEFAULT NULL,
  `surname` varchar(20) DEFAULT NULL,
  `country` varchar(30) DEFAULT NULL,
  `language` varchar(5) DEFAULT '',
  `state` varchar(30) DEFAULT NULL,
  `province` varchar(30) DEFAULT NULL,
  `county` varchar(30) DEFAULT NULL,
  `city` varchar(30) DEFAULT NULL,
  `company` varchar(100) DEFAULT NULL,
  `job` varchar(50) DEFAULT NULL,
  `certificate_type` int(11) DEFAULT NULL,
  `certificate_id` int(30) DEFAULT NULL,
  `sex` int(11) DEFAULT '0',
  `brief` longtext
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `<%_dbp_%>usr_sessions` (
  `id` varchar(32) NOT NULL,
  `KEY_STAMP` int(32) NOT NULL,
  `data` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `<%_dbp_%>usr_tokens` (
  `id` int(11) NOT NULL,
  `type` int(11) NOT NULL DEFAULT '0',
  `token` varchar(32) NOT NULL,
  `usr_id` int(11) NOT NULL,
  `dateline` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

TRUNCATE TABLE `<%_dbp_%>usr_tokens`;
INSERT INTO `<%_dbp_%>usr_tokens` (`id`, `type`, `token`, `usr_id`, `dateline`) VALUES
(1, 1, '3e9f927d40f5fabab03c766eed79e87e', 1, '2015-11-09 11:26:04');

CREATE TABLE IF NOT EXISTS `<%_dbp_%>usr_wallets` (
  `uid` int(11) NOT NULL,
  `pw_pay` varchar(32) DEFAULT NULL,
  `currency` varchar(8) NOT NULL DEFAULT 'usd',
  `balance` int(11) NOT NULL DEFAULT '0',
  `frozen` int(11) NOT NULL DEFAULT '0',
  `KEY_STATE` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE `<%_dbp_%>msg_server`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `<%_dbp_%>msg_u0`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `<%_dbp_%>msg_u1`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `<%_dbp_%>msg_u2`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `<%_dbp_%>msg_u3`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `<%_dbp_%>msg_u4`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `<%_dbp_%>msg_u5`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `<%_dbp_%>msg_u6`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `<%_dbp_%>msg_u7`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `<%_dbp_%>msg_u8`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `<%_dbp_%>msg_u9`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `<%_dbp_%>sys_channels`
  ADD PRIMARY KEY (`ID`);

ALTER TABLE `<%_dbp_%>sys_columns`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `CHANNEL` (`CHANNEL`);

ALTER TABLE `<%_dbp_%>sys_operators`
  ADD PRIMARY KEY (`UID`);

ALTER TABLE `<%_dbp_%>sys_routemap`
  ADD PRIMARY KEY (`ID`);

ALTER TABLE `<%_dbp_%>sys_users`
  ADD PRIMARY KEY (`APPLICABLE_LANG`);

ALTER TABLE `<%_dbp_%>usr_accounts`
  ADD PRIMARY KEY (`uid`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `mb` (`mobile`),
  ADD KEY `alias` (`unicodename`);

ALTER TABLE `<%_dbp_%>usr_associated_accounts`
  ADD PRIMARY KEY (`uid`,`code`);

ALTER TABLE `<%_dbp_%>usr_followgroup`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `<%_dbp_%>usr_guest`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `<%_dbp_%>usr_informations`
  ADD PRIMARY KEY (`uid`);

ALTER TABLE `<%_dbp_%>usr_sessions`
  ADD UNIQUE KEY `id` (`id`);

ALTER TABLE `<%_dbp_%>usr_tokens`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `<%_dbp_%>msg_server`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `<%_dbp_%>msg_u0`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `<%_dbp_%>msg_u1`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `<%_dbp_%>msg_u2`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `<%_dbp_%>msg_u3`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `<%_dbp_%>msg_u4`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `<%_dbp_%>msg_u5`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `<%_dbp_%>msg_u6`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `<%_dbp_%>msg_u7`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `<%_dbp_%>msg_u8`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `<%_dbp_%>msg_u9`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `<%_dbp_%>sys_channels`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
ALTER TABLE `<%_dbp_%>sys_columns`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
ALTER TABLE `<%_dbp_%>sys_routemap`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=48;
ALTER TABLE `<%_dbp_%>usr_accounts`
  MODIFY `uid` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
ALTER TABLE `<%_dbp_%>usr_followgroup`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `<%_dbp_%>usr_guest`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `<%_dbp_%>sys_columns`
  ADD CONSTRAINT `<%_DBP_%>sys_columns_ibfk_1` FOREIGN KEY (`CHANNEL`) REFERENCES `<%_dbp_%>sys_channels` (`ID`);

ALTER TABLE `<%_dbp_%>sys_operators`
  ADD CONSTRAINT `<%_DBP_%>sys_operators_ibfk_1` FOREIGN KEY (`UID`) REFERENCES `<%_dbp_%>usr_accounts` (`uid`);
