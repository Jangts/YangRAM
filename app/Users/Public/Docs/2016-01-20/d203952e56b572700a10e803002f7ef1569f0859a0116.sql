-- phpMyAdmin SQL Dump
-- version 4.0.10.10
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2016-01-14 21:41:57
-- 服务器版本: 5.6.24-log
-- PHP 版本: 5.6.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `demo`
--

-- --------------------------------------------------------

--
-- 表的结构 `tni_reg_apps`
--

CREATE TABLE IF NOT EXISTS `tni_reg_apps` (
  `id` int(11) NOT NULL,
  `APP_Type` int(11) NOT NULL DEFAULT '1',
  `APP_Suit` varchar(50) DEFAULT NULL,
  `APP_Name` varchar(100) NOT NULL,
  `APP_EnAb` char(2) NOT NULL,
  `APP_Brief` varchar(1000) DEFAULT NULL,
  `APP_Icon` varchar(30) DEFAULT 'yangram-logo',
  `APP_Path` varchar(150) DEFAULT NULL,
  `APP_Operator` varchar(64) NOT NULL,
  `APP_Render` varchar(64) DEFAULT NULL,
  `APP_Getter` varchar(64) DEFAULT NULL,
  `APP_Setter` varchar(64) DEFAULT NULL,
  `APP_AuthorId` int(11) NOT NULL DEFAULT '0',
  `APP_AuthorName` varchar(50) NOT NULL,
  `APP_DevID` int(11) NOT NULL DEFAULT '1',
  `APP_Version` varchar(30) NOT NULL,
  `APP_IsOnDock` int(11) NOT NULL DEFAULT '0',
  `APP_ViewType` varchar(6) NOT NULL DEFAULT 'center',
  `APP_ReleaseTime` date NOT NULL,
  `APP_BuildTime` datetime NOT NULL,
  `APP_LastRunTime` datetime NOT NULL,
  `APP_Count` int(11) NOT NULL DEFAULT '0',
  `APP_IsNew` int(11) NOT NULL DEFAULT '0',
  UNIQUE KEY `app_id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `tni_reg_apps`
--

INSERT INTO `tni_reg_apps` (`id`, `APP_Type`, `APP_Suit`, `APP_Name`, `APP_EnAb`, `APP_Brief`, `APP_Icon`, `APP_Path`, `APP_Operator`, `APP_Render`, `APP_Getter`, `APP_Setter`, `APP_AuthorId`, `APP_AuthorName`, `APP_DevID`, `APP_Version`, `APP_IsOnDock`, `APP_ViewType`, `APP_ReleaseTime`, `APP_BuildTime`, `APP_LastRunTime`, `APP_Count`, `APP_IsNew`) VALUES
(0, 0, '', 'Homepage', 'Hp', NULL, 'screen-desktop', 'Homepage/', 'Homepage.php', NULL, NULL, NULL, 0, 'Taihe Adv', 1, '1.6.0.0', 0, 'cover', '2015-07-11', '2015-07-11 00:00:03', '2015-11-06 03:45:33', 44, 0),
(1, 0, '', 'Data Explorer', 'De', NULL, 'compass', 'Explorer/', 'Explorer.php', 'Previewer.php', 'Getter.php', 'Setter.php', 0, 'Taihe Adv', 1, '1.0.0.1', 1, 'center', '2015-07-11', '2015-07-11 00:00:00', '2015-07-11 00:00:00', 26, 0),
(2, 0, '', 'Trash Can', 'Tc', NULL, 'trash', 'Recycle.Bin/', 'Recycle.php', NULL, NULL, 'Setter.php', 0, 'Taihe Adv', 1, '0.0.0.0', 1, 'center', '2015-07-11', '2015-07-11 00:00:00', '2015-07-11 00:00:00', 4, 0),
(3, 0, '', 'Control Panel', 'Cp', NULL, 'settings', 'Control.Panel/', 'Control.php', NULL, NULL, NULL, 0, 'Taihe Adv', 1, '1.0.0.0', 1, 'center', '2015-07-11', '2015-07-11 00:00:00', '2015-07-11 00:00:00', 0, 0),
(4, 0, '', 'Statistics', 'St', NULL, 'graph', '', '', NULL, NULL, NULL, 0, 'Taihe Adv', 1, '0.0.0.4', 1, 'center', '2015-07-11', '2015-07-11 00:00:00', '2015-07-11 00:00:00', 0, 0),
(5, 0, '', 'Usermgr', 'Um', NULL, 'users', '', '', NULL, NULL, NULL, 0, 'Taihe Adv', 1, '0.0.0.3', 1, 'center', '2015-07-11', '2015-07-11 00:00:00', '2015-07-11 00:00:00', 0, 0),
(6, 0, '', 'Contacts', 'Ct', NULL, 'user', '', 'Kalendar', NULL, NULL, NULL, 0, 'Taihe Adv', 1, '0.0.0.2', 1, 'center', '2015-07-11', '2015-07-11 00:00:00', '2015-11-06 03:45:34', 1, 0),
(7, 0, '', 'Messager', 'Mr', NULL, 'female', '', '', NULL, NULL, NULL, 0, 'Taihe Adv', 1, '0.0.0.1', 0, 'center', '2015-07-11', '2015-07-11 00:00:00', '2015-07-11 00:00:00', 0, 0),
(8, 0, '', 'Y-Mall', 'Ym', NULL, 'bag', 'NULL', 'Store.php', NULL, NULL, NULL, 0, 'Taihe Adv', 1, '0.0.0.6', 1, 'cover', '2015-07-11', '2015-07-11 00:00:00', '2015-07-11 00:00:00', 2, 1),
(9, 0, '', 'Open Data', 'Od', NULL, 'feed', 'NULL', 'Store.php', NULL, NULL, NULL, 0, 'Taihe Adv', 1, '0.0.0.6', 1, 'cover', '2015-07-11', '2015-07-11 00:00:00', '2015-07-11 00:00:00', 0, 0),
(10, 0, '', 'Front Pages', 'Fp', NULL, 'docs', 'NULL', 'Store.php', NULL, NULL, NULL, 0, 'Taihe Adv', 1, '0.0.0.6', 1, 'cover', '2015-07-11', '2015-07-11 00:00:00', '2015-07-11 00:00:00', 0, 0),
(52, 0, '', 'Registry', '', NULL, 'key', '', '', NULL, NULL, NULL, 0, 'Taihe Adv', 1, '0.0.0.1', 0, 'center', '2015-07-11', '2015-07-11 00:00:00', '2015-07-11 00:00:00', 0, 0),
(87, 0, '', 'Web FTP', '', NULL, 'folder-alt', '', '', NULL, NULL, NULL, 0, 'Taihe Adv', 1, '0.0.0.1', 0, 'center', '2015-07-11', '2015-07-11 00:00:00', '2015-07-11 00:00:00', 0, 0),
(1001, 1, 'Dataroom', 'Developer', 'Dv', NULL, 'dataroom-developer', 'Yangram.Dataroom/', 'Developer.php', NULL, 'Getter.php', 'Setter.php', 0, 'Taihe Adv', 1, '1.10.11.01', 1, 'center', '2015-07-11', '2015-07-11 00:00:00', '2015-08-17 06:58:28', 2, 0),
(1002, 1, 'Dataroom', 'Publisher', 'Pb', NULL, 'dataroom-publisher', 'Yangram.DataRoom/', 'Publisher.php', NULL, 'Getter.php', 'Setter.php', 0, 'Taihe Adv', 1, '1.10.11.01', 1, 'center', '2015-07-11', '2015-07-11 00:00:00', '2015-08-17 06:58:28', 1, 0),
(1003, 1, 'Dataroom', 'Labels Manager', 'Lb', NULL, 'dataroom-labelsmgr', 'Yangram.DataRoom/', 'Labelsmgr.php', NULL, 'Getter.php', 'Setter.php', 0, 'Taihe Adv', 1, '1.10.11.01', 1, 'center', '2015-07-11', '2015-07-11 00:00:00', '2015-07-11 00:00:00', 0, 0),
(1004, 1, 'Dataroom', 'Collector', 'Cl', NULL, 'dataroom-collector', 'Yangram.DataRoom/', 'Collector.php', NULL, 'Getter.php', 'Setter.php', 0, 'Taihe Adv', 1, '1.10.11.01', 1, 'center', '2015-07-11', '2015-07-11 00:00:00', '2015-07-11 00:00:00', 0, 0),
(1005, 1, 'Dataroom', 'Rapporteur', 'Rp', NULL, 'dataroom-rapporteur', 'Yangram.DataRoom/', 'Rapporteur.php', 'Report.php', 'Getter.php', 'Setter.php', 0, 'Taihe Adv', 1, '1.10.11.01', 1, 'center', '2015-07-11', '2015-07-11 00:00:00', '2015-08-17 06:58:28', 0, 0),
(1006, 1, 'Dataroom', 'Designer', 'Ds', NULL, 'dataroom-designer', 'Yangram.DataRoom/', 'Designer.php', NULL, 'Getter.php', 'Setter.php', 0, 'Taihe Adv', 1, '1.10.11.01', 1, 'center', '2015-07-11', '2015-07-11 00:00:00', '2015-07-11 00:00:00', 0, 0),
(1007, 1, 'Dataroom', 'Investigator', 'Iv', NULL, 'dataroom-investigator', 'Yangram.DataRoom/', 'Investigator.php', 'Vote.php', 'Getter.php', 'Setter.php', 0, 'Taihe Adv', 1, '1.10.11.01', 1, 'center', '2015-07-11', '2015-07-11 00:00:00', '2015-07-11 00:00:00', 0, 0),
(1015, 1, 'SNS.Plus', 'Book+', 'Bk', NULL, 'speech', 'SNS.Plus/Book.Plus/', 'Main.php', NULL, NULL, NULL, 0, 'Taihe Adv', 1, '1.10.11.09', 1, 'center', '2015-07-11', '2015-07-11 00:00:00', '2015-07-11 00:00:00', 0, 0),
(1016, 1, 'SNS.Plus', 'Like+', 'Lk', NULL, 'like', 'SNS.Plus/Like.Plus/', 'Main.php', NULL, NULL, NULL, 0, 'Taihe Adv', 1, '1.10.11.10', 1, 'center', '2015-07-11', '2015-07-11 00:00:00', '2015-07-11 00:00:00', 0, 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
