SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE IF NOT EXISTS `<%_dbp_%>cnt_in_common_use` (
  `ID` int(11) NOT NULL,
  `GROUPCODE` varchar(64) NOT NULL DEFAULT '',
  `ALIAS` char(16) NOT NULL,
  `BANNER` varchar(256) DEFAULT NULL,
  `TITLE` char(64) DEFAULT NULL,
  `KEYWORDS` varchar(64) DEFAULT NULL,
  `DESCRIPTION` varchar(128) DEFAULT NULL,
  `CONTENT` longtext NOT NULL,
  `CUSTOM_I` longtext,
  `CUSTOM_II` longtext,
  `KEY_COUNT` int(11) NOT NULL DEFAULT '0',
  `KEY_CTIME` datetime NOT NULL,
  `KEY_MTIME` datetime NOT NULL,
  `KEY_IS_RECYCLED` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `<%_dbp_%>cnt_in_custom_use` (
  `ID` int(11) NOT NULL,
  `ALIAS` varchar(16) NOT NULL,
  `TITLE` char(64) DEFAULT NULL,
  `TAGS` varchar(512) NOT NULL,
  `DESCRIPTION` varchar(128) DEFAULT NULL,
  `KEY_CTIME` datetime DEFAULT NULL,
  `KEY_MTIME` datetime DEFAULT NULL,
  `KEY_STATE` int(11) DEFAULT '1',
  `KEY_IS_RECYCLED` int(11) DEFAULT '0',
  `USR_ID` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `<%_dbp_%>cnt_in_special_use` (
  `ID` int(11) NOT NULL,
  `SET_ALIAS` varchar(64) NOT NULL,
  `CAT_ID` int(11) DEFAULT '0',
  `TITLE` char(64) DEFAULT NULL,
  `TAGS` varchar(512) NOT NULL,
  `DESCRIPTION` varchar(128) DEFAULT NULL,
  `PUBTIME` datetime DEFAULT NULL,
  `RANK` int(11) DEFAULT '4',
  `IS_TOP` int(11) NOT NULL DEFAULT '0',
  `KEY_MTIME` datetime DEFAULT NULL,
  `KEY_STATE` int(11) DEFAULT '1',
  `KEY_COUNT` int(11) DEFAULT '0',
  `KEY_IS_RECYCLED` int(11) DEFAULT '0',
  `USR_ID` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

TRUNCATE TABLE `<%_dbp_%>cnt_in_special_use`;
INSERT INTO `<%_dbp_%>cnt_in_special_use` (`ID`, `SET_ALIAS`, `CAT_ID`, `TITLE`, `TAGS`, `DESCRIPTION`, `PUBTIME`, `RANK`, `IS_TOP`, `KEY_MTIME`, `KEY_STATE`, `KEY_COUNT`, `KEY_IS_RECYCLED`, `USR_ID`) VALUES
(1, 'articles', 1, 'Welcome to use YangRAM  I4s', '', '', '2016-11-18 00:00:00', 4, 0, NULL, 1, 0, 0, 0),
(2, 'articles', 1, 'About YangRAM I4s', '', '', '2016-11-20 00:00:00', 4, 0, NULL, 1, 0, 0, 0),
(3, 'articles', 1, 'New features of YangRAM 2.1.0.0', '', '', '2016-11-19 00:00:00', 4, 0, NULL, 1, 0, 0, 0),
(4, 'articles', 1, 'Sample Article', '', '', '2016-11-21 00:00:00', 4, 0, NULL, 1, 0, 0, 0);

CREATE TABLE IF NOT EXISTS `<%_dbp_%>cnt_of_articles` (
  `CNT_ID` int(11) NOT NULL,
  `KEYWORDS` varchar(64) DEFAULT NULL,
  `CHARGE_TYPE` int(11) DEFAULT '1',
  `CHARGE_VALUE` int(11) NOT NULL DEFAULT '0',
  `RECHARGE_HOURS` int(11) DEFAULT '0',
  `RECHARGE_TIMES` int(11) DEFAULT '0',
  `RELATES` longtext,
  `PARTICIPANT` longtext,
  `KEY_CTIME` datetime NOT NULL,
  `KEY_LIMIT` int(11) DEFAULT '0',
  `PRIMER` varchar(256) DEFAULT NULL,
  `SUBTITLE` varchar(256) NOT NULL,
  `AUTHOR` varchar(64) DEFAULT NULL,
  `SOURCE` varchar(512) DEFAULT '',
  `CONTENT` longtext
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

TRUNCATE TABLE `<%_dbp_%>cnt_of_articles`;
INSERT INTO `<%_dbp_%>cnt_of_articles` (`CNT_ID`, `KEYWORDS`, `CHARGE_TYPE`, `CHARGE_VALUE`, `RECHARGE_HOURS`, `RECHARGE_TIMES`, `RELATES`, `PARTICIPANT`, `KEY_CTIME`, `KEY_LIMIT`, `PRIMER`, `SUBTITLE`, `AUTHOR`, `SOURCE`, `CONTENT`) VALUES
(1, '', 1, 0, 0, 0, '', '', '2016-08-05 11:22:39', 0, '', '', '', '', 'DDDDDDDDDDDDDDDDDDDD'),
(2, '', 1, 0, 0, 0, '', '', '2016-08-05 11:22:39', 0, '', '', '', '', 'DDDDDDDDDDDDDDDDDDDD'),
(3, '', 1, 0, 0, 0, '', '', '2016-08-05 11:22:39', 0, '', '', '', '', 'DDDDDDDDDDDDDDDDDDDD'),
(4, '', 1, 0, 0, 0, '', '', '2016-08-05 11:22:39', 0, '', '', '', '', 'DDDDDDDDDDDDDDDDDDDD');

CREATE TABLE IF NOT EXISTS `<%_dbp_%>src_doc` (
  `ID` int(11) NOT NULL,
  `ALIAS` char(32) NOT NULL,
  `FLD_ID` int(11) NOT NULL DEFAULT '6',
  `FILE_NAME` varchar(128) NOT NULL,
  `FILE_TYPE` char(32) NOT NULL DEFAULT 'archive',
  `FILE_SIZE` int(11) NOT NULL DEFAULT '0',
  `SUFFIX` char(32) DEFAULT NULL,
  `KEY_IS_RECYCLED` int(11) NOT NULL DEFAULT '0',
  `KEY_MTIME` datetime NOT NULL,
  `USR_ID` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

TRUNCATE TABLE `<%_dbp_%>src_doc`;
INSERT INTO `<%_dbp_%>src_doc` (`ID`, `ALIAS`, `FLD_ID`, `FILE_NAME`, `FILE_TYPE`, `FILE_SIZE`, `SUFFIX`, `KEY_IS_RECYCLED`, `KEY_MTIME`, `USR_ID`) VALUES
(1, '0SampleDocument', 2, 'License.doc', 'compressed', 9216, 'zip', 0, '2015-12-23 00:00:00', 1);

CREATE TABLE IF NOT EXISTS `<%_dbp_%>src_doc_0` (
  `DOC_ID` int(11) NOT NULL,
  `LOCATION` varchar(256) DEFAULT NULL,
  `MIME` char(128) NOT NULL,
  `KEY_CTIME` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

TRUNCATE TABLE `<%_dbp_%>src_doc_0`;
INSERT INTO `<%_dbp_%>src_doc_0` (`DOC_ID`, `LOCATION`, `MIME`, `KEY_CTIME`) VALUES
(1, 'Docs/Smaple/License.doc', 'application/msword', '2015-12-23 00:00:00');

CREATE TABLE IF NOT EXISTS `<%_dbp_%>src_doc_1` (
  `DOC_ID` int(11) NOT NULL,
  `LOCATION` varchar(256) DEFAULT NULL,
  `MIME` char(128) NOT NULL,
  `KEY_CTIME` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `<%_dbp_%>src_doc_2` (
  `DOC_ID` int(11) NOT NULL,
  `LOCATION` varchar(256) DEFAULT NULL,
  `MIME` char(128) NOT NULL,
  `KEY_CTIME` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `<%_dbp_%>src_doc_3` (
  `DOC_ID` int(11) NOT NULL,
  `LOCATION` varchar(256) DEFAULT NULL,
  `MIME` char(128) NOT NULL,
  `KEY_CTIME` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `<%_dbp_%>src_doc_4` (
  `DOC_ID` int(11) NOT NULL,
  `LOCATION` varchar(256) DEFAULT NULL,
  `MIME` char(128) NOT NULL,
  `KEY_CTIME` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `<%_dbp_%>src_doc_5` (
  `DOC_ID` int(11) NOT NULL,
  `LOCATION` varchar(256) DEFAULT NULL,
  `MIME` char(128) NOT NULL,
  `KEY_CTIME` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `<%_dbp_%>src_img` (
  `ID` int(11) NOT NULL,
  `ALIAS` char(32) NOT NULL,
  `FLD_ID` int(11) NOT NULL DEFAULT '6',
  `FILE_NAME` varchar(128) NOT NULL,
  `FILE_TYPE` char(32) NOT NULL DEFAULT 'archive',
  `FILE_SIZE` int(11) NOT NULL DEFAULT '0',
  `SUFFIX` char(32) DEFAULT NULL,
  `KEY_MTIME` datetime NOT NULL,
  `KEY_IS_RECYCLED` int(11) NOT NULL DEFAULT '0',
  `USR_ID` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=66 DEFAULT CHARSET=utf8;

TRUNCATE TABLE `<%_dbp_%>src_img`;
INSERT INTO `<%_dbp_%>src_img` (`ID`, `ALIAS`, `FLD_ID`, `FILE_NAME`, `FILE_TYPE`, `FILE_SIZE`, `SUFFIX`, `KEY_MTIME`, `KEY_IS_RECYCLED`, `USR_ID`) VALUES
(1, '0SamplePicture01', 2, 'trees.jpg', 'image', 370490, 'jpg', '2015-12-31 00:00:00', 0, 1),
(2, '0SamplePicture02', 2, 'magic.jpg', 'image', 339220, 'jpg', '2015-12-23 00:00:00', 0, 1),
(3, '1ca28525a8b386236136', 3, 'guest.jpg', 'image', 73401, 'jpg', '2016-03-23 18:59:34', 0, 1),
(4, 'cc26775220c32188228', 3, 'operator.jpg', 'image', 139485, 'jpg', '2016-03-23 18:59:35', 0, 1),
(5, '04b8512215a30cac5346', 3, 'user.jpg', 'image', 66707, 'jpg', '2016-03-23 18:59:35', 0, 1),
(6, '2d78cf72c9a8f4731217', 3, 'yangram.jpg', 'image', 134581, 'jpg', '2016-03-23 18:59:35', 0, 1),
(7, '1f7f476c3a12b1f1f130', 3, 'fish.jpg', 'image', 199597, 'jpg', '2016-03-23 18:59:35', 0, 1),
(8, '294e4347ccf5c0f24112', 3, 'captain.jpg', 'image', 298702, 'jpg', '2016-03-23 18:59:35', 0, 1),
(9, '2f05685142ac1eee018', 7, '1.jpg', 'image', 153189, 'jpg', '2016-04-02 14:51:54', 0, 1),
(10, '0eb7e85d8c178da40252', 7, '2.jpg', 'image', 408068, 'jpg', '2016-05-24 20:17:45', 0, 1),
(11, '214f8e79887a4ced798', 7, '3.jpg', 'image', 450043, 'jpg', '2016-05-24 20:17:49', 0, 1),
(12, '0086f1f9d45c460cd3', 8, '56f353f712828.jpg', 'image', 57233, 'jpg', '2016-04-03 14:51:26', 0, 1),
(13, '11607bac8667a2e91131', 8, '56f353f712828.jpg(1)', 'image', 57233, 'jpg', '2016-04-03 14:57:22', 0, 1),
(14, '1dbcd6d79545c0503125', 8, '56f353f712828.jpg(2)', 'image', 57233, 'jpg', '2016-04-03 15:01:13', 0, 1),
(15, '2c98bab5380ca946850', 8, 'Desktop.jpg', 'image', 1427157, 'jpg', '2016-04-03 15:01:32', 0, 1),
(16, '0ce0e3fc07c620c0b267', 8, 'Desktop.jpg(1)', 'image', 1427157, 'jpg', '2016-04-03 15:04:32', 0, 1),
(17, '1de2148c49ec3599b32', 12, 'source_03.jpg', 'image', 102174, 'jpg', '2016-05-24 11:34:02', 0, 1),
(18, '20e678e533ac041f1250', 12, 'source_04.jpg', 'image', 133230, 'jpg', '2016-05-24 11:34:02', 0, 1),
(19, '1c49d1119ce66939d209', 12, 'source_06.jpg', 'image', 189626, 'jpg', '2016-05-24 11:34:02', 0, 1),
(20, '29e4cc47a3d9d96ea387', 12, 'source_05.jpg', 'image', 229083, 'jpg', '2016-05-24 11:34:03', 0, 1),
(21, '19ba36616b2e5eca1275', 12, 'source_01.jpg', 'image', 188777, 'jpg', '2016-05-24 12:16:19', 0, 1),
(22, '16593b1b616a85b087', 8, 'ywtx_01.jpg', 'image', 165162, 'jpg', '2016-05-24 12:22:34', 0, 1),
(23, '19e32d9bab018aa43301', 8, 'ywtx_04.jpg', 'image', 141796, 'jpg', '2016-05-24 12:22:34', 0, 1),
(24, '0b571f4c51be67383186', 8, 'ywtx_03.jpg', 'image', 117001, 'jpg', '2016-05-24 12:22:34', 0, 1),
(25, '2cd19847ecedecf1153', 8, 'ywtx_05.jpg', 'image', 74003, 'jpg', '2016-05-24 12:22:34', 0, 1),
(26, '028bfbf565bd5a39a322', 10, 'zk_03.jpg', 'image', 196323, 'jpg', '2016-05-24 12:32:05', 0, 1),
(27, '1723bcc14cf6722b7124', 10, 'zk2_04.jpg', 'image', 223005, 'jpg', '2016-05-24 12:32:01', 0, 1),
(28, '01f1ddb69fcf3eafc369', 10, 'zk2_03.jpg', 'image', 201937, 'jpg', '2016-05-24 12:31:57', 0, 1),
(29, '14e5d10af6f56111191', 10, 'zk_04.jpg', 'image', 209372, 'jpg', '2016-05-24 12:32:09', 0, 1),
(30, '11a1c88ce083eede3338', 10, 'zk3_03.jpg', 'image', 208950, 'jpg', '2016-05-24 12:32:16', 0, 1),
(31, '0c342cab3cabfa89a244', 10, 'zk3_04.jpg', 'image', 207485, 'jpg', '2016-05-24 12:32:12', 0, 1),
(32, '0aff2607a3358d601372', 8, 'tx_08.jpg', 'image', 317641, 'jpg', '2016-05-24 12:37:38', 0, 1),
(33, '166e269eb00423259172', 8, 'tx_06.jpg', 'image', 247643, 'jpg', '2016-05-24 12:37:50', 0, 1),
(46, '1bc7b236d9afd604257', 13, '1.jpg.jpg', 'image', 11767, 'jpg', '2016-07-08 16:10:41', 0, 1),
(47, '204bbd0a4b34afcca347', 13, '2.jpg.jpg', 'image', 6623, 'jpg', '2016-07-08 16:10:42', 0, 1),
(48, '03c2b49be5014dd32192', 13, '5.jpg.jpg', 'image', 10690, 'jpg', '2016-07-08 16:10:42', 0, 1),
(49, '0873d1df8d21eb5f7395', 13, '4.jpg.jpg', 'image', 10267, 'jpg', '2016-07-08 16:10:42', 0, 1),
(50, '204a7b6126746390321', 13, '3.jpg.jpg', 'image', 18662, 'jpg', '2016-07-08 16:10:42', 0, 1),
(51, '13cf28524c697b071144', 13, '6.jpg.jpg', 'image', 13426, 'jpg', '2016-07-08 16:10:42', 0, 1),
(52, '2435237d00a20bf3c359', 13, '7.jpg.jpg', 'image', 10060, 'jpg', '2016-07-08 16:10:42', 0, 1),
(53, '1a80dcdd3b046de4f366', 13, '8.jpg.jpg', 'image', 12635, 'jpg', '2016-07-08 16:10:42', 0, 1),
(54, '030bb034f3181e7f4102', 13, '9.jpg.jpg', 'image', 11624, 'jpg', '2016-07-08 16:10:42', 0, 1),
(55, '2af40372b3819b05f111', 13, '10.jpg.jpg', 'image', 16482, 'jpg', '2016-07-08 16:10:42', 0, 1),
(56, '1d6815cb514592861307', 13, '11.jpg.jpg', 'image', 11961, 'jpg', '2016-07-08 16:10:42', 0, 1),
(57, '2e94d9f4506e03445207', 13, '12.jpg.jpg', 'image', 11547, 'jpg', '2016-07-08 16:10:43', 0, 1),
(58, '18659dec508dd7e23367', 13, '13.jpg.png', 'image', 8703, 'png', '2016-07-08 16:10:43', 0, 1),
(59, '21d06c18d07656964240', 13, '14.jpg.jpg', 'image', 7145, 'jpg', '2016-07-08 16:10:43', 0, 1),
(60, '0b65d7c3877ac6fb449', 13, '15.jpg.jpg', 'image', 8933, 'jpg', '2016-07-08 16:10:43', 0, 1),
(61, '05521918eacc85a35228', 13, '16.jpg.jpg', 'image', 10857, 'jpg', '2016-07-08 16:10:43', 0, 1),
(62, '07799f5f60e0c3db4271', 13, '17.jpg.jpg', 'image', 8872, 'jpg', '2016-07-08 16:10:43', 0, 1),
(63, '0a2b67548b53a283a112', 13, '18.jpg.png', 'image', 2534, 'png', '2016-07-08 16:10:43', 0, 1),
(64, '1d7e3fc2791dbeba4331', 13, '19.jpg.jpg', 'image', 5676, 'jpg', '2016-07-08 16:10:43', 0, 1),
(65, '18a936f7c031b76bd278', 13, '白云区      18.jpg.jpg', 'image', 6143, 'jpg', '2016-07-08 16:10:44', 0, 1);

CREATE TABLE IF NOT EXISTS `<%_dbp_%>src_img_0` (
  `DOC_ID` int(11) NOT NULL,
  `LOCATION` varchar(256) DEFAULT NULL,
  `MIME` char(128) NOT NULL,
  `IMAGE_SIZE` varchar(64) NOT NULL,
  `WIDTH` int(11) NOT NULL,
  `HEIGHT` int(11) NOT NULL,
  `KEY_CTIME` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

TRUNCATE TABLE `<%_dbp_%>src_img_0`;
INSERT INTO `<%_dbp_%>src_img_0` (`DOC_ID`, `LOCATION`, `MIME`, `IMAGE_SIZE`, `WIDTH`, `HEIGHT`, `KEY_CTIME`) VALUES
(1, 'Images/Sample/trees.jpg', 'image/jpeg', 'width="800" height="600"', 800, 600, '2015-12-31 00:00:00'),
(2, 'Images/Sample/magic.jpg', 'image/jpeg', 'width="960" height="600"', 960, 600, '2015-12-23 00:00:00'),
(4, 'Images/DefultAvatars/operator.jpg', 'image/jpeg', 'width="800" height="800"', 800, 800, '2016-03-23 18:59:35'),
(5, 'Images/DefultAvatars/user.jpg', 'image/jpeg', 'width="800" height="800"', 800, 800, '2016-03-23 18:59:35'),
(10, 'Images/2016-05-24/2cff6350a66986705dea53cd3933f9155744466956ed4.jpg', 'image/jpeg', 'width="1180" height="320"', 1180, 320, '2016-04-02 14:51:59'),
(12, 'Images/2016-04-03/9015fe25937e47f2efcd2eaa2c32e0875700bd6e39e08.jpg', 'image/jpeg', 'width="1328" height="747"', 1328, 747, '2016-04-03 14:51:26'),
(16, 'Images/2016-04-03/546b168d7820e408d7d22a50df6248dd5700c080a4f7d.jpg', 'image/jpeg', 'width="1920" height="1200"', 1920, 1200, '2016-04-03 15:04:32'),
(24, 'Images/2016-05-24/f2646b1e3bbbc16771a07ef499dcb55f5743d70a73e31.jpg', 'image/jpeg', 'width="720" height="440"', 720, 440, '2016-05-24 12:22:34'),
(26, 'Images/2016-05-24/88358d747f8036fbe069f272c30019045743d9459a2c4.jpg', 'image/jpeg', 'width="720" height="750"', 720, 750, '2016-05-24 12:27:21'),
(28, 'Images/2016-05-24/75821059d6ffe212322645552389cd775743d93d9428b.jpg', 'image/jpeg', 'width="720" height="750"', 720, 750, '2016-05-24 12:27:21'),
(31, 'Images/2016-05-24/855191123396e137e812cbbce52cdd855743d94d0224b.jpg', 'image/jpeg', 'width="720" height="750"', 720, 750, '2016-05-24 12:27:22'),
(32, 'Images/2016-05-24/1cc7c586195945925cc7ae10e245a2955743da924cfe2.jpg', 'image/jpeg', 'width="720" height="780"', 720, 780, '2016-05-24 12:37:38'),
(48, 'Images/2016-07-08/9f4ababcc1b130859d6b125722f8188c577f60021c921.jpg', 'image/jpeg', 'width="499" height="75"', 499, 75, '2016-07-08 16:10:42'),
(49, 'Images/2016-07-08/3fead3db11de083608687eddb928f76d577f600231df3.jpg', 'image/jpeg', 'width="484" height="77"', 484, 77, '2016-07-08 16:10:42'),
(54, 'Images/2016-07-08/ac8133a0df7cfe40e606e3ff98766f13577f60029d487.jpg', 'image/jpeg', 'width="485" height="96"', 485, 96, '2016-07-08 16:10:42'),
(60, 'Images/2016-07-08/3cdae7c1d80158c7f232dedd8f793fd7577f600375c5c.jpg', 'image/jpeg', 'width="345" height="96"', 345, 96, '2016-07-08 16:10:43'),
(61, 'Images/2016-07-08/9e65d7a04417f734c6abb9361e547d47577f6003867cd.jpg', 'image/jpeg', 'width="417" height="106"', 417, 106, '2016-07-08 16:10:43'),
(62, 'Images/2016-07-08/40cebc527a1c99754ae31e7973703363577f60039db71.jpg', 'image/jpeg', 'width="401" height="65"', 401, 65, '2016-07-08 16:10:43'),
(63, 'Images/2016-07-08/e6d988025c35169461b8868c6d03af5e577f6003be1ef.png', 'image/png', 'width="326" height="44"', 326, 44, '2016-07-08 16:10:43');

CREATE TABLE IF NOT EXISTS `<%_dbp_%>src_img_1` (
  `DOC_ID` int(11) NOT NULL,
  `LOCATION` varchar(256) DEFAULT NULL,
  `MIME` char(128) NOT NULL,
  `IMAGE_SIZE` varchar(64) NOT NULL,
  `WIDTH` int(11) NOT NULL,
  `HEIGHT` int(11) NOT NULL,
  `KEY_CTIME` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

TRUNCATE TABLE `<%_dbp_%>src_img_1`;
INSERT INTO `<%_dbp_%>src_img_1` (`DOC_ID`, `LOCATION`, `MIME`, `IMAGE_SIZE`, `WIDTH`, `HEIGHT`, `KEY_CTIME`) VALUES
(3, 'Images/DefultAvatars/guest.jpg', 'image/jpeg', 'width="800" height="800"', 800, 800, '2016-03-23 18:59:34'),
(7, 'Images/DefultAvatars/fish.jpg', 'image/jpeg', 'width="800" height="800"', 800, 800, '2016-03-23 18:59:35'),
(13, 'Images/2016-04-03/9015fe25937e47f2efcd2eaa2c32e0875700bed27e176.jpg', 'image/jpeg', 'width="1328" height="747"', 1328, 747, '2016-04-03 14:57:22'),
(14, 'Images/2016-04-03/9015fe25937e47f2efcd2eaa2c32e0875700bfb983f8b.jpg', 'image/jpeg', 'width="1328" height="747"', 1328, 747, '2016-04-03 15:01:13'),
(17, 'Images/2016-05-24/dc4492da7eb5cf714e3e1eea0f15a5aa5743cbaaa36b2.jpg', 'image/jpeg', 'width="720" height="326"', 720, 326, '2016-05-24 11:34:02'),
(19, 'Images/2016-05-24/e815ebab82a71300f27bf74d88ad38b65743cbaae8d21.jpg', 'image/jpeg', 'width="720" height="473"', 720, 473, '2016-05-24 11:34:02'),
(21, 'Images/2016-05-24/4fb3dbe0278abe0eacb85e87ce3d0d865743d593a8050.jpg', 'image/jpeg', 'width="720" height="627"', 720, 627, '2016-05-24 12:16:19'),
(22, 'Images/2016-05-24/392b4d1bbc5b70aaf979b906bebec3b25743d70a305b7.jpg', 'image/jpeg', 'width="720" height="670"', 720, 670, '2016-05-24 12:22:34'),
(23, 'Images/2016-05-24/2d69256ca1dcb5cfe07304adf6475ec95743d70a53f1a.jpg', 'image/jpeg', 'width="720" height="320"', 720, 320, '2016-05-24 12:22:34'),
(27, 'Images/2016-05-24/9ba33c47dc2021430bd7db2b35dbf2c05743d94150bb6.jpg', 'image/jpeg', 'width="720" height="750"', 720, 750, '2016-05-24 12:27:21'),
(29, 'Images/2016-05-24/ddef84a4536d9c5e62de0cb105b29e665743d9492432c.jpg', 'image/jpeg', 'width="720" height="750"', 720, 750, '2016-05-24 12:27:21'),
(30, 'Images/2016-05-24/1df611f1de1ff612d6540123674413695743d950891ca.jpg', 'image/jpeg', 'width="720" height="750"', 720, 750, '2016-05-24 12:27:21'),
(33, 'Images/2016-05-24/efdb2a501d9ea2341af10d5fa94f77735743da9ecceb8.jpg', 'image/jpeg', 'width="720" height="780"', 720, 780, '2016-05-24 12:37:50'),
(46, 'Images/2016-07-08/db64c0311e9475c87e370a765b3e4556577f6001d0114.jpg', 'image/jpeg', 'width="608" height="68"', 608, 68, '2016-07-08 16:10:41'),
(51, 'Images/2016-07-08/14cfbc67d2435bb076945f753cdd9e77577f600254f51.jpg', 'image/jpeg', 'width="497" height="85"', 497, 85, '2016-07-08 16:10:42'),
(53, 'Images/2016-07-08/fbd4cb1a6ada2f5db1e410574d9ade28577f600281b28.jpg', 'image/jpeg', 'width="470" height="74"', 470, 74, '2016-07-08 16:10:42'),
(56, 'Images/2016-07-08/b172effffa44be6d0a62b590ed5d7236577f6002d18c2.jpg', 'image/jpeg', 'width="447" height="117"', 447, 117, '2016-07-08 16:10:42'),
(58, 'Images/2016-07-08/34b23ac1fed047bddd49de2963c19288577f60033a021.png', 'image/png', 'width="287" height="145"', 287, 145, '2016-07-08 16:10:43'),
(64, 'Images/2016-07-08/9ae4be28703f49e37d8a716cea0a287b577f6003db505.jpg', 'image/jpeg', 'width="370" height="48"', 370, 48, '2016-07-08 16:10:43'),
(65, 'Images/2016-07-08/20e8852969590b1a2b5db86be2860517577f600406c34.jpg', 'image/jpeg', 'width="284" height="49"', 284, 49, '2016-07-08 16:10:44');

CREATE TABLE IF NOT EXISTS `<%_dbp_%>src_img_2` (
  `DOC_ID` int(11) NOT NULL,
  `LOCATION` varchar(256) DEFAULT NULL,
  `MIME` char(128) NOT NULL,
  `IMAGE_SIZE` varchar(64) NOT NULL,
  `WIDTH` int(11) NOT NULL,
  `HEIGHT` int(11) NOT NULL,
  `KEY_CTIME` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

TRUNCATE TABLE `<%_dbp_%>src_img_2`;
INSERT INTO `<%_dbp_%>src_img_2` (`DOC_ID`, `LOCATION`, `MIME`, `IMAGE_SIZE`, `WIDTH`, `HEIGHT`, `KEY_CTIME`) VALUES
(6, 'Images/DefultAvatars/yangram.jpg', 'image/jpeg', 'width="800" height="800"', 800, 800, '2016-03-23 18:59:35'),
(8, 'Images/DefultAvatars/captain.jpg', 'image/jpeg', 'width="800" height="800"', 800, 800, '2016-03-23 18:59:35'),
(9, 'Images/2016-04-02/5be45b7c489dba90471cc8ae4b82daf456ff6c0505a92.jpg', 'image/jpeg', 'width="1180" height="320"', 1180, 320, '2016-04-02 14:51:49'),
(11, 'Images/2016-05-24/b5e64bb764f9a861ed303137b0a4bbce5744466d14060.jpg', 'image/jpeg', 'width="1180" height="320"', 1180, 320, '2016-04-02 14:51:59'),
(15, 'Images/2016-04-03/546b168d7820e408d7d22a50df6248dd5700bfcc0f666.jpg', 'image/jpeg', 'width="1920" height="1200"', 1920, 1200, '2016-04-03 15:01:32'),
(18, 'Images/2016-05-24/dfe10694244953ce888fdf20b3ded9ff5743cbaad3321.jpg', 'image/jpeg', 'width="720" height="326"', 720, 326, '2016-05-24 11:34:02'),
(20, 'Images/2016-05-24/899432402e53f3beacd639152cc1325b5743cbab1a6ad.jpg', 'image/jpeg', 'width="720" height="473"', 720, 473, '2016-05-24 11:34:03'),
(25, 'Images/2016-05-24/1d34f277e3bb1045fcd5f23546e00b025743d70a89ed8.jpg', 'image/jpeg', 'width="720" height="230"', 720, 230, '2016-05-24 12:22:34'),
(47, 'Images/2016-07-08/2bf33469282c2e86aa56cc4adeedfd11577f600201aff.jpg', 'image/jpeg', 'width="415" height="72"', 415, 72, '2016-07-08 16:10:42'),
(50, 'Images/2016-07-08/2ce2efc22f4533a71a741e814df49f13577f600244ca3.jpg', 'image/jpeg', 'width="682" height="89"', 682, 89, '2016-07-08 16:10:42'),
(52, 'Images/2016-07-08/106458fdfd61a886ecdad9d6034c7e79577f60026551c.jpg', 'image/jpeg', 'width="504" height="82"', 504, 82, '2016-07-08 16:10:42'),
(55, 'Images/2016-07-08/ab4fd9223b2637a726bad22858460544577f6002bd5f0.jpg', 'image/jpeg', 'width="609" height="95"', 609, 95, '2016-07-08 16:10:42'),
(57, 'Images/2016-07-08/750cf510b051bc8046d5c056fc39da61577f60030c6d9.jpg', 'image/jpeg', 'width="437" height="76"', 437, 76, '2016-07-08 16:10:43'),
(59, 'Images/2016-07-08/3fcf1a4bae281fd9b3bc20ee5d3e943b577f600357a1d.jpg', 'image/jpeg', 'width="426" height="65"', 426, 65, '2016-07-08 16:10:43');

CREATE TABLE IF NOT EXISTS `<%_dbp_%>src_txt` (
  `ID` int(11) NOT NULL,
  `ALIAS` char(32) NOT NULL,
  `FLD_ID` int(11) NOT NULL DEFAULT '6',
  `FILE_NAME` varchar(128) NOT NULL,
  `FILE_TYPE` char(32) NOT NULL DEFAULT 'archive',
  `FILE_SIZE` int(11) NOT NULL DEFAULT '0',
  `SUFFIX` char(32) DEFAULT NULL,
  `KEY_MTIME` datetime NOT NULL,
  `KEY_IS_RECYCLED` int(11) NOT NULL DEFAULT '0',
  `USR_ID` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

TRUNCATE TABLE `<%_dbp_%>src_txt`;
INSERT INTO `<%_dbp_%>src_txt` (`ID`, `ALIAS`, `FLD_ID`, `FILE_NAME`, `FILE_TYPE`, `FILE_SIZE`, `SUFFIX`, `KEY_MTIME`, `KEY_IS_RECYCLED`, `USR_ID`) VALUES
(1, '095ba3684b1e4ccc5282', 12, 'MuseLog.txt', 'text', 2138, 'txt', '2016-05-24 18:12:32', 0, 1);

CREATE TABLE IF NOT EXISTS `<%_dbp_%>src_txt_0` (
  `DOC_ID` int(11) NOT NULL,
  `LOCATION` varchar(256) DEFAULT NULL,
  `MIME` char(128) NOT NULL,
  `KEY_CTIME` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

TRUNCATE TABLE `<%_dbp_%>src_txt_0`;
INSERT INTO `<%_dbp_%>src_txt_0` (`DOC_ID`, `LOCATION`, `MIME`, `KEY_CTIME`) VALUES
(1, 'Docs/2016-05-24/469e2685f4d0eac21abdaac3a7d9d462574429101027d.txt', 'text/plain', '2016-05-24 18:12:32');

CREATE TABLE IF NOT EXISTS `<%_dbp_%>src_vod` (
  `ID` int(11) NOT NULL,
  `ALIAS` char(32) NOT NULL,
  `FLD_ID` int(11) NOT NULL DEFAULT '6',
  `FILE_NAME` varchar(128) NOT NULL,
  `FILE_TYPE` char(32) NOT NULL DEFAULT 'archive',
  `FILE_SIZE` int(11) NOT NULL DEFAULT '0',
  `SUFFIX` char(32) DEFAULT NULL,
  `KEY_MTIME` datetime NOT NULL,
  `KEY_IS_RECYCLED` int(11) NOT NULL DEFAULT '0',
  `USR_ID` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

TRUNCATE TABLE `<%_dbp_%>src_vod`;
INSERT INTO `<%_dbp_%>src_vod` (`ID`, `ALIAS`, `FLD_ID`, `FILE_NAME`, `FILE_TYPE`, `FILE_SIZE`, `SUFFIX`, `KEY_MTIME`, `KEY_IS_RECYCLED`, `USR_ID`) VALUES
(1, '0SampleVideo', 2, 'FlyToSpace.mp4', 'video', 14020722, 'mp4', '2015-12-23 00:00:00', 0, 1);

CREATE TABLE IF NOT EXISTS `<%_dbp_%>src_vod_0` (
  `DOC_ID` int(11) NOT NULL,
  `LOCATION` varchar(256) DEFAULT NULL,
  `MIME` char(128) NOT NULL,
  `DURATION` int(11) NOT NULL DEFAULT '0',
  `WIDTH` int(11) NOT NULL,
  `HEIGHT` int(11) NOT NULL,
  `KEY_CTIME` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

TRUNCATE TABLE `<%_dbp_%>src_vod_0`;
INSERT INTO `<%_dbp_%>src_vod_0` (`DOC_ID`, `LOCATION`, `MIME`, `DURATION`, `WIDTH`, `HEIGHT`, `KEY_CTIME`) VALUES
(1, 'Videos/Sample/FlyToSpace.mp4', 'video/mp4', 20, 0, 0, '2015-12-23 00:00:00');

CREATE TABLE IF NOT EXISTS `<%_dbp_%>src_wav` (
  `ID` int(11) NOT NULL,
  `ALIAS` char(32) NOT NULL,
  `FLD_ID` int(11) NOT NULL DEFAULT '6',
  `FILE_NAME` varchar(128) NOT NULL,
  `FILE_TYPE` char(32) NOT NULL DEFAULT 'archive',
  `FILE_SIZE` int(11) NOT NULL DEFAULT '0',
  `SUFFIX` char(32) DEFAULT NULL,
  `KEY_MTIME` datetime NOT NULL,
  `KEY_IS_RECYCLED` int(11) NOT NULL DEFAULT '0',
  `USR_ID` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

TRUNCATE TABLE `<%_dbp_%>src_wav`;
INSERT INTO `<%_dbp_%>src_wav` (`ID`, `ALIAS`, `FLD_ID`, `FILE_NAME`, `FILE_TYPE`, `FILE_SIZE`, `SUFFIX`, `KEY_MTIME`, `KEY_IS_RECYCLED`, `USR_ID`) VALUES
(2, '0SampleOggAudio', 2, 'LaCampanella.ogg', 'audio', 2224551, 'ogg', '2015-12-23 00:00:00', 0, 1);

CREATE TABLE IF NOT EXISTS `<%_dbp_%>src_wav_0` (
  `DOC_ID` int(11) NOT NULL,
  `LOCATION` varchar(256) DEFAULT NULL,
  `MIME` char(128) NOT NULL,
  `DURATION` int(11) NOT NULL DEFAULT '0',
  `KEY_CTIME` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

TRUNCATE TABLE `<%_dbp_%>src_wav_0`;
INSERT INTO `<%_dbp_%>src_wav_0` (`DOC_ID`, `LOCATION`, `MIME`, `DURATION`, `KEY_CTIME`) VALUES
(1, 'Audios/Sample/LaCampanella.mp3', 'audio/mpeg', 120, '2015-12-23 00:00:00'),
(2, 'Audios/Sample/LaCampanella.ogg', 'audio/ogg', 120, '2015-12-23 00:00:00');


ALTER TABLE `<%_dbp_%>cnt_in_common_use`
  ADD PRIMARY KEY (`ID`);

ALTER TABLE `<%_dbp_%>cnt_in_custom_use`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `ALIAS` (`ALIAS`);

ALTER TABLE `<%_dbp_%>cnt_in_special_use`
  ADD PRIMARY KEY (`ID`);

ALTER TABLE `<%_dbp_%>cnt_of_articles`
  ADD PRIMARY KEY (`CNT_ID`);

ALTER TABLE `<%_dbp_%>src_doc`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `DOC_Alias` (`ALIAS`);

ALTER TABLE `<%_dbp_%>src_doc_0`
  ADD PRIMARY KEY (`DOC_ID`);

ALTER TABLE `<%_dbp_%>src_doc_1`
  ADD PRIMARY KEY (`DOC_ID`);

ALTER TABLE `<%_dbp_%>src_doc_2`
  ADD PRIMARY KEY (`DOC_ID`);

ALTER TABLE `<%_dbp_%>src_doc_3`
  ADD PRIMARY KEY (`DOC_ID`);

ALTER TABLE `<%_dbp_%>src_doc_4`
  ADD PRIMARY KEY (`DOC_ID`);

ALTER TABLE `<%_dbp_%>src_doc_5`
  ADD PRIMARY KEY (`DOC_ID`);

ALTER TABLE `<%_dbp_%>src_img`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `docId` (`ALIAS`);

ALTER TABLE `<%_dbp_%>src_img_0`
  ADD PRIMARY KEY (`DOC_ID`);

ALTER TABLE `<%_dbp_%>src_img_1`
  ADD PRIMARY KEY (`DOC_ID`);

ALTER TABLE `<%_dbp_%>src_img_2`
  ADD PRIMARY KEY (`DOC_ID`);

ALTER TABLE `<%_dbp_%>src_txt`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `docId` (`ALIAS`);

ALTER TABLE `<%_dbp_%>src_txt_0`
  ADD PRIMARY KEY (`DOC_ID`);

ALTER TABLE `<%_dbp_%>src_vod`
  ADD PRIMARY KEY (`ID`);

ALTER TABLE `<%_dbp_%>src_vod_0`
  ADD PRIMARY KEY (`DOC_ID`);

ALTER TABLE `<%_dbp_%>src_wav`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `docId` (`ALIAS`);

ALTER TABLE `<%_dbp_%>src_wav_0`
  ADD PRIMARY KEY (`DOC_ID`);


ALTER TABLE `<%_dbp_%>cnt_in_common_use`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `<%_dbp_%>cnt_in_custom_use`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `<%_dbp_%>cnt_in_special_use`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
ALTER TABLE `<%_dbp_%>src_doc`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
ALTER TABLE `<%_dbp_%>src_img`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=66;
ALTER TABLE `<%_dbp_%>src_txt`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
ALTER TABLE `<%_dbp_%>src_vod`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
ALTER TABLE `<%_dbp_%>src_wav`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
