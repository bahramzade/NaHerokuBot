-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1
-- Время создания: Ноя 28 2019 г., 13:46
-- Версия сервера: 5.5.25
-- Версия PHP: 5.3.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `testerbotoff`
--

-- --------------------------------------------------------

--
-- Структура таблицы `zayavka`
--

CREATE TABLE IF NOT EXISTS `zayavka` (
  `id_client` int(20) DEFAULT NULL,
  `id_zakaz` int(20) DEFAULT NULL,
  `vibor` varchar(20) DEFAULT NULL,
  `monet` varchar(20) DEFAULT NULL,
  `kol_monet` double DEFAULT NULL,
  `valuta` varchar(20) DEFAULT NULL,
  `cena` double DEFAULT NULL,
  `itog` double DEFAULT NULL,
  `bank` text,
  `flag_isp` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
