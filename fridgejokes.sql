-- phpMyAdmin SQL Dump
-- version 3.4.5deb1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 11-09-2012 a las 20:16:10
-- Versión del servidor: 5.1.58
-- Versión de PHP: 5.3.6-13ubuntu3.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `fridgejokes`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dislikes`
--

CREATE TABLE IF NOT EXISTS `dislikes` (
  `user_ID` int(6) NOT NULL,
  `joke_ID` int(6) NOT NULL,
  `timestamp` int(12) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `jokes`
--

CREATE TABLE IF NOT EXISTS `jokes` (
  `joke_ID` int(11) NOT NULL AUTO_INCREMENT,
  `status` char(1) NOT NULL DEFAULT 'P',
  `text` text NOT NULL,
  `submitter` varchar(16) NOT NULL DEFAULT 'Anonymous MC',
  `timestamp` int(12) NOT NULL,
  `spoiler` tinyint(1) NOT NULL DEFAULT '0',
  `approved_by` int(11) DEFAULT NULL,
  `views` int(11) DEFAULT NULL,
  `dislikes` int(11) DEFAULT NULL,
  `loves` int(11) DEFAULT NULL,
  `likes_total` int(11) DEFAULT NULL,
  `likes_anon` int(11) DEFAULT NULL,
  `last_like_anon` int(12) DEFAULT NULL,
  `followup_ID` int(11) DEFAULT NULL,
  PRIMARY KEY (`joke_ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Volcado de datos para la tabla `jokes`
--

INSERT INTO `jokes` (`joke_ID`, `status`, `text`, `submitter`, `timestamp`, `spoiler`, `approved_by`, `views`, `dislikes`, `loves`, `likes_total`, `likes_anon`, `last_like_anon`, `followup_ID`) VALUES
(1, 'A', '>You opened the fridge.\r\n>You read an error message: 404. Fridge not found.\r\n>You reboot the fridge.', 'Anonymous MC', 1346879147, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `likes`
--

CREATE TABLE IF NOT EXISTS `likes` (
  `user_ID` int(6) NOT NULL,
  `joke_ID` int(6) NOT NULL,
  `timestamp` int(12) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `loves`
--

CREATE TABLE IF NOT EXISTS `loves` (
  `user_ID` int(6) NOT NULL,
  `joke_ID` int(6) NOT NULL,
  `timestamp` int(12) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `user_ID` int(6) NOT NULL,
  `username` varchar(16) NOT NULL,
  `password` varchar(48) NOT NULL,
  `email` varchar(128) NOT NULL,
  `status` char(1) NOT NULL DEFAULT 'B',
  `login_type` char(1) NOT NULL DEFAULT '0',
  `first_signup` int(12) NOT NULL,
  `last_login` int(12) NOT NULL,
  `see_spoilers` tinyint(1) NOT NULL DEFAULT '0',
  `location` varchar(32) DEFAULT NULL,
  `bio` varchar(160) DEFAULT NULL,
  `website` varchar(32) DEFAULT NULL,
  `jokes_read` int(11) NOT NULL,
  `social` int(11) NOT NULL,
  PRIMARY KEY (`user_ID`),
  UNIQUE KEY `Username_unique` (`username`),
  UNIQUE KEY `email_unique` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
