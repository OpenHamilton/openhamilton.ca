-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 03, 2011 at 03:23 PM
-- Server version: 5.5.8
-- PHP Version: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `openhamilton`
--

-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

DROP TABLE IF EXISTS `comment`;
CREATE TABLE IF NOT EXISTS `comment` (
  `commentID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `comment` text,
  `upvotes` int(11) DEFAULT NULL,
  `downvotes` int(11) DEFAULT NULL,
  `submittime` datetime DEFAULT NULL,
  PRIMARY KEY (`commentID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=78 ;

--
-- Dumping data for table `comment`
--

INSERT INTO `comment` (`commentID`, `comment`, `upvotes`, `downvotes`, `submittime`) VALUES
(65, 'This is a test.', 0, 0, '2011-11-17 13:55:01'),
(66, 'Here is some more stuff.<br />\r\nWith extra<br />\r\nlines.', 0, 0, '2011-11-17 13:55:23'),
(67, 'Here''s a list;<br />\r\n<br />\r\n1. Hello<br />\r\n2. Goodbye<br />\r\n3. Hello, again.', 0, 0, '2011-11-17 13:56:03'),
(68, 'Sample comments:<br />\r\n<br />\r\n"Hey guys, this is great! Keep up the good work."', 0, 0, '2011-11-17 13:56:43'),
(69, 'Random comments found on the net left by people on Facebook.', 0, 0, '2011-11-17 13:57:41'),
(70, 'Peanut Butter Chicken McNuggets', 0, 0, '2011-11-17 13:58:15'),
(71, 'someone needs to keep this page updated!', 0, 0, '2011-11-17 13:58:28'),
(72, 'Put a banana in your ear!', 0, 0, '2011-11-17 13:58:45'),
(73, 'What is a jiffy you say? "A ‘jiffy’ is an actual unit of time for 1/100th of a second."', 0, 0, '2011-11-17 13:59:01'),
(74, 'sometimes when my parents leave me alone at home, i like to go outside and pretend im a tree', 0, 0, '2011-11-17 13:59:34'),
(75, 'I think anyone who dislikes Llamas, dislikes america. And they think you fat. So... yeah.', 0, 0, '2011-11-17 14:00:15'),
(76, 'asfd', 0, 0, '2011-12-03 14:41:35'),
(77, 'Here it is', 0, 0, '2011-12-03 14:41:41');
