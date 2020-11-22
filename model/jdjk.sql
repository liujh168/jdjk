-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- 主机： localhost
-- 生成日期： 2020-11-18 22:45:33
-- 服务器版本： 5.7.26
-- PHP 版本： 7.3.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 数据库： `jdjk`
--

-- --------------------------------------------------------

--
-- 表的结构 `clients`
--

CREATE TABLE `clients` (
  `username` char(50) COLLATE utf8_unicode_ci DEFAULT '张三',
  `sexuality` char(2) COLLATE utf8_unicode_ci DEFAULT '男',
  `phone` char(15) COLLATE utf8_unicode_ci DEFAULT '07372985123',
  `address` varchar(50) COLLATE utf8_unicode_ci DEFAULT '沅江市莲子塘集镇',
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `product` char(50) COLLATE utf8_unicode_ci DEFAULT ' 格力空调',
  `unit` char(8) COLLATE utf8_unicode_ci DEFAULT '台',
  `price` int(11) DEFAULT '169',
  `nums` int(11) DEFAULT '1000',
  `print` int(11) DEFAULT '5686',
  `memo` varchar(50) COLLATE utf8_unicode_ci DEFAULT '备注'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='客户销售信息表';

--
-- 转存表中的数据 `clients`
--

INSERT INTO `clients` (`username`, `sexuality`, `phone`, `address`, `date`, `product`, `unit`, `price`, `nums`, `print`, `memo`) VALUES
('京东健康电器', '男', '07372982123', '香铺仑', '2020-10-31 16:00:00', '格力空调', '台', 472, 1000, 5686, '备注信息');

-- --------------------------------------------------------

--
-- 表的结构 `login`
--

CREATE TABLE `login` (
  `id` char(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'ljh',
  `pwd` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `rank` int(11) DEFAULT NULL,
  `date` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='用户数据库';

--
-- 转存表中的数据 `login`
--

INSERT INTO `login` (`id`, `pwd`, `rank`, `date`) VALUES
('ljk', '133', 3, '2020-10-13 16:00:00');

--
-- 转储表的索引
--

--
-- 表的索引 `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`date`),
  ADD KEY `username` (`username`),
  ADD KEY `phone` (`phone`);

--
-- 表的索引 `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
